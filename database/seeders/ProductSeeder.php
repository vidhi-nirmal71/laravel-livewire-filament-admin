<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        
        $faker = Faker::create();
        $products = [];

        for ($i = 1; $i <= 2000; $i++) {
            $products[] = [
                'name' => $faker->words(3, true) . " $i",
                'description' => $faker->sentence(10),
                'price' => $faker->randomFloat(2, 100, 200000),
                'stock' => $faker->numberBetween(1, 100),
                'is_active' => $faker->boolean(90),
                'image' => "images/smartphone.jpeg",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Product::insert($products);
    }
}
