<?php

use App\Filament\Resources\Categories\Pages\SortCategories;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/admin/categories/update-tree', [SortCategories::class, 'updateTree'])
    ->name('admin.categories.update-tree')
    ->middleware('auth'); 
