<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 1_000_000;        // 10 lakh products
        $batchSize = 1_000;        // tune for your memory
        $faker = Faker::create();

        DB::disableQueryLog();

        $this->command->info("Preparing category & brand lists...");

        // Fetch categories and build parent -> [children] map
        $categories = DB::table('categories')->select('id', 'parent_id')->get();
        $parentCategories = $categories->whereNull('parent_id')->pluck('id')->values()->all(); // top-level
        $childMap = [];

        foreach ($categories->whereNotNull('parent_id') as $row) {
            $pid = (int) $row->parent_id;
            $childMap[$pid][] = (int) $row->id;
        }

        // If there are no parents, treat all categories as parents
        if (empty($parentCategories)) {
            $parentCategories = $categories->pluck('id')->map(fn($v) => (int)$v)->values()->all();
        }

        // Fetch brand ids
        $brandIds = DB::table('brands')->pluck('id')->map(fn($v) => (int)$v)->values()->all();

        $this->command->info(sprintf(
            "Found %d parent categories, %d child-parent relations, %d brands",
            count($parentCategories),
            count($childMap),
            count($brandIds)
        ));

        // Prepare image filenames from public/storage/p1/download (60).png -> download (89).png
        $imageFiles = [];
        for ($n = 60; $n <= 89; $n++) {
            $imageFiles[] = "download ({$n}).png";
        }
        // Full stored path used in DB (adjust if you want full URL)
        $imagePaths = array_map(fn($f) => 'storage/p1/' . $f, $imageFiles);

        $now = now();
        $start = 1;
        $sizeOptions = ['S', 'M', 'L', 'XL'];
        $conditionOptions = ['default', 'new', 'hot'];

        $logEvery = 10; // print progress every 10 batches
        $batchNumber = 0;

        $this->command->info("Seeding {$total} products in batches of {$batchSize}...");

        while ($start <= $total) {
            $batchNumber++;
            $end = min($start + $batchSize - 1, $total);
            $batch = [];

            for ($i = $start; $i <= $end; $i++) {
                $baseName = $faker->randomElement([
                    'iPhone', 'Galaxy', 'Pixel', 'ThinkPad', 'MacBook', 'Surface',
                    'Sony Headphones', 'Bose Headphones', 'Logitech Mouse', 'Dell Monitor',
                    'Asus Router', 'Canon Camera', 'Speaker', 'Charger'
                ]);

                $title = $baseName . ' ' . $faker->bothify('??-####');
                $isActiveBool = $faker->boolean(80); // ~80% active

                $price = $faker->randomFloat(2, 500, 200000);
                $discount = $faker->boolean(20) ? $faker->randomFloat(2, 10, min(30000, $price * 0.6)) : null;

                // pick a random parent category (if available)
                $catId = null;
                $childCatId = null;
                if (!empty($parentCategories)) {
                    $catId = $faker->randomElement($parentCategories);

                    // try to pick a random child of this parent if exists
                    if (isset($childMap[$catId]) && is_array($childMap[$catId]) && count($childMap[$catId]) > 0) {
                        // ~50% chance to have child assigned
                        if ($faker->boolean(50)) {
                            $childCatId = $faker->randomElement($childMap[$catId]);
                        }
                    }
                }

                // pick random brand if available
                $brandId = null;
                if (!empty($brandIds)) {
                    $brandId = $faker->randomElement($brandIds);
                }

                // pick a random real image from public/storage/p1/
                $image = $faker->randomElement($imagePaths);

                $batch[] = [
                    'title'         => $title,
                    'slug'          => Str::slug($title) . '-' . $i,
                    'summary'       => $faker->sentence(8),
                    'description'   => $faker->paragraphs(2, true),
                    'stock'         => $faker->numberBetween(0, 500),
                    'size'          => $faker->randomElement($sizeOptions),
                    'condition'     => $faker->randomElement($conditionOptions),
                    'status'        => $isActiveBool ? 'active' : 'inactive',
                    'image'         => $image,
                    'price'         => $price,
                    'discount'      => $discount,
                    'is_featured'   => $faker->boolean(10) ? 1 : 0,
                    'cat_id'        => $catId,
                    'child_cat_id'  => $childCatId,
                    'brand_id'      => $brandId,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }

            DB::table('products')->insert($batch);

            // free memory
            unset($batch);
            gc_collect_cycles();

            if ($batchNumber % $logEvery === 0 || $end === $total) {
                $this->command->info("Inserted rows {$start} to {$end}");
            } else {
                $this->command->getOutput()->write('.');
            }

            $start = $end + 1;
        }

        $this->command->info("\nFinished seeding {$total} products.");
    }
}
