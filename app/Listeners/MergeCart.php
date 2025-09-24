<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class MergeCart
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $sessionCart = Session::get('cart', []);

        foreach ($sessionCart as $productId => $item) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id, 'product_id' => $productId],
                ['quantity' => 0]
            );
            $cart->increment('quantity', $item['quantity']);
        }

        Session::forget('cart');
    }
}
