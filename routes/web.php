<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


require __DIR__.'/auth.php';

Route::get('/', function () { return view('home'); })->name('home');

Route::get('/cart', function () { return view('livewire.cart-wrapper'); })->name('cart.index');

Route::get('/checkout', function() { return view('livewire.checkout-wrapper'); })->name('checkout')->middleware('auth');

Route::get('/thank-you/{order}', [OrderController::class, 'thankYou'])->name('thank-you');
Route::get('/profile', [OrderController::class, 'profile'])->name('profile');
Route::get('/profile/order/{order}', [OrderController::class, 'viewOrder'])->name('profile.order.view');
