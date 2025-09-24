<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function thankYou(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('livewire.thank-you', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get();

        return view('livewire.profile', compact('user', 'orders'));
    }

    public function viewOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');
        return view('livewire.view-order', compact('order'));
    }
}
