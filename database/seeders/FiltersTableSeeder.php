<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiltersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('filters')->insert([
            [
                'id' => 1,
                'name' => 'brand',
                'title' => 'Brands',
                'status' => 'Active',
            ],
            [
                'id' => 2,
                'name' => 'rating',
                'title' => 'Customer Ratings',
                'status' => 'Active',
            ],
            [
                'id' => 3,
                'name' => 'discount',
                'title' => 'Discounts',
                'status' => 'Active',
            ],
            [
                'id' => 4,
                'name' => 'Britanni Sloan',
                'title' => 'Mollit dolor est und',
                'status' => 'Inactive',
            ],
            [
                'id' => 5,
                'name' => 'price',
                'title' => 'Price Range',
                'status' => 'Active',
            ],
            [
                'id' => 6,
                'name' => 'recently-viewed',
                'title' => 'Recently Viewed',
                'status' => 'Active',
            ],
        ]);
    }
}
