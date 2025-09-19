<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    public $products;

    public function mount()
    {
        $this->products = Product::where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.product-list');
    }
}
