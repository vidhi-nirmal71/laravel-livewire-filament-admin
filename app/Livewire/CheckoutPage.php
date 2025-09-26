<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutPage extends Component
{
    public $cartItems = [];
    public $name, $email, $phone, $address, $city, $state, $zip;
    public $grandTotal = 0;
    public $paymentIntentSecret;
    protected $listeners = ['placeOrder', 'validateForm'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|numeric|digits_between:7,15',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'zip' => 'required|string|max:10',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name.',
        'email.required' => 'Please enter your email.',
        'email.email' => 'Enter a valid email address.',
        'phone.required' => 'Please enter your phone number.',
        'phone.numeric' => 'Phone must be numeric.',
        'phone.digits_between' => 'Phone must be between 7 and 15 digits.',
        'address.required' => 'Please enter your address.',
        'city.required' => 'Please enter your city.',
        'state.required' => 'Please enter your state.',
        'zip.required' => 'Please enter your ZIP code.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function validateForm()
    {
        $this->validate();
        return true;
    }

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->loadCart();

        if ($this->grandTotal > 0 && !$this->paymentIntentSecret) {
            $this->createPaymentIntent();
        }
    }

    public function createPaymentIntent()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => $this->grandTotal * 100,
            'currency' => 'inr',
            'payment_method_types' => ['card'],
        ]);

        $this->paymentIntentSecret = $intent->client_secret;
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $this->cartItems = session()->get('cart', []);
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->grandTotal = 0;

        foreach ($this->cartItems as $item) {
            $isArray = is_array($item);
            $price = $isArray ? $item['price'] : ($item->product->price ?? 0);
            $quantity = $isArray ? $item['quantity'] : $item->quantity;
            $this->grandTotal += $price * $quantity;
        }
    }

    public function placeOrder()
    {
        $validated = $this->validate();

        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'total_amount' => $this->grandTotal,
            'status' => 'Completed',
        ]);

        foreach ($this->cartItems as $item) {
            $isArray = is_array($item);
            $productId = $isArray ? $item['product_id'] : $item->product_id;
            $quantity = $isArray ? $item['quantity'] : $item->quantity;
            $price = $isArray ? $item['price'] : ($item->product->price ?? 0);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        $this->dispatch('cartUpdated');
        session()->flash('success', 'Payment successful & order placed!');
        return redirect()->route('thank-you', $order->id);
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}