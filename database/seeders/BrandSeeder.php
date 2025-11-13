<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $brands = [
            ['title' => 'Apple',  'slug' => Str::slug('Apple'),  'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Samsung','slug' => Str::slug('Samsung'),'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Sony',   'slug' => Str::slug('Sony'),   'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Dell',   'slug' => Str::slug('Dell'),   'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Lenovo', 'slug' => Str::slug('Lenovo'), 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Asus',   'slug' => Str::slug('Asus'),   'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'HP',     'slug' => Str::slug('HP'),     'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Nike',   'slug' => Str::slug('Nike'),   'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];

        // Upsert by `slug` so duplicates won't throw errors; existing rows will be updated.
        DB::table('brands')->upsert($brands, ['slug'], ['title', 'status', 'updated_at']);
    }
}
