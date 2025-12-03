<?php

use App\Filament\Resources\Categories\Pages\SortCategories;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/admin/categories/update-tree', [SortCategories::class, 'updateTree'])
    ->name('admin.categories.update-tree')
    ->middleware('auth'); 

Route::post('/admin/categories/create-from-sort', [SortCategories::class, 'storeSubCat'])
    ->name('admin.categories.create-from-sort')
    ->middleware('auth');

// routes/web.php (or admin routes file)
Route::get('/admin/order/{id}', [OrderController::class, 'show']);
Route::get('/admin/order/{id}/pdf', [OrderController::class, 'pdf']);

Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])
    ->name('admin.orders.destroy')
    ->middleware('auth');

