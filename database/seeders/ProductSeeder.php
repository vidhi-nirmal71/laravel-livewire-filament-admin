<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Product::insert([
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest Apple flagship smartphone with A17 Pro chip.',
                'price' => 129999.00,
                'stock' => 10,
                'is_active' => true,
                'image' => 'https://via.placeholder.com/300x200.png?text=iPhone+15+Pro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy S23',
                'description' => 'High-end Android smartphone with great cameras.',
                'price' => 99999.00,
                'stock' => 15,
                'is_active' => true,
                'image' => 'https://via.placeholder.com/300x200.png?text=Galaxy+S23',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-cancelling wireless headphones.',
                'price' => 29999.00,
                'stock' => 25,
                'is_active' => true,
                'image' => 'https://via.placeholder.com/300x200.png?text=Sony+XM5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MacBook Pro 14"',
                'description' => 'Apple M2 Pro laptop with Retina display.',
                'price' => 199999.00,
                'stock' => 5,
                'is_active' => false, // not active
                'image' => 'https://via.placeholder.com/300x200.png?text=MacBook+Pro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
