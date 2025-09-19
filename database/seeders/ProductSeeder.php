<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'name'        => $faker->words(3, true),   // e.g. "Smart LED TV"
                'description' => $faker->sentence(10),
                'price'       => $faker->randomFloat(2, 100, 10000),
                'stock'       => $faker->numberBetween(1, 500),
                'is_active'   => $faker->boolean(80), // 80% chance active
                'image'       => $faker->imageUrl(640, 480, 'products', true),
            ]);
        }
    }
}
