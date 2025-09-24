<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $cartCount = 0;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->updateCartCount();
    }

    public function render()
    {
        $products = Product::where('is_active', true)->paginate(20);
        return view('livewire.product-list', ['products' => $products]);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Product not available.');
            return;
        }

        if (Auth::check()) {
            // Store directly in DB
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productId],
                ['quantity' => 0]
            );
            $cart->increment('quantity');
        } else {
            // Store in session
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
            } else {
                $cart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => 1,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                ];
            }

            session()->put('cart', $cart);
        }

        $this->updateCartCount();
        $this->dispatch('cartUpdated');

        session()->flash('success', $product->name . ' added to cart!');
    }

    private function updateCartCount()
    {
        if (Auth::check()) {
            $this->cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $this->cartCount = Cart::where('session_id', session()->getId())->sum('quantity');
        }
    }
}
