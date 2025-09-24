<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartPage extends Component
{
    public $cartItems = [];

    protected $layout = null;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = Cart::where('user_id', Auth::id())->get();
        } else {
            $this->cartItems = session()->get('cart', []);
        }
    }

    public function increaseQuantity($productId)
    {
        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->increment('quantity');
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
                session()->put('cart', $cart);
            }
        }
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function decreaseQuantity($productId)
    {
        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
            if ($cartItem && $cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId]) && $cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
                session()->put('cart', $cart);
            }
        }
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function removeItem($productId)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
        }
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
