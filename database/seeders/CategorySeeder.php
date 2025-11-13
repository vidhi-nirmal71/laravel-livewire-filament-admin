<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $categories = [
            [
                'title' => 'Electronics',
                'slug' => Str::slug('Electronics'),
                'summary' => 'All kinds of electronics and gadgets.',
                'photo' => 'https://via.placeholder.com/300x200.png?text=Electronics',
                'level' => 0,
                'status' => 'active',
                'is_featured' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Fashion',
                'slug' => Str::slug('Fashion'),
                'summary' => 'Men and Women fashion products.',
                'photo' => 'https://via.placeholder.com/300x200.png?text=Fashion',
                'level' => 0,
                'status' => 'active',
                'is_featured' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Home Appliances',
                'slug' => Str::slug('Home Appliances'),
                'summary' => 'Appliances and tools for home use.',
                'photo' => 'https://via.placeholder.com/300x200.png?text=Home+Appliances',
                'level' => 0,
                'status' => 'active',
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Sports & Fitness',
                'slug' => Str::slug('Sports & Fitness'),
                'summary' => 'Sports equipment and fitness accessories.',
                'photo' => 'https://via.placeholder.com/300x200.png?text=Sports+Fitness',
                'level' => 0,
                'status' => 'active',
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
