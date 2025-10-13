<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Core
            $table->string('title');
            $table->string('slug')->unique();

            // Optional
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();

            // Inventory & status
            $table->integer('stock')->default(1);
            $table->string('size')->nullable()->default('M');
            $table->enum('condition', ['default', 'new', 'hot'])->default('default');
            $table->enum('status', ['active', 'inactive'])->default('inactive');

            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->boolean('is_featured')->default(false);

            // Foreign Keys
            $table->foreignId('cat_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('child_cat_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            // Timestamps
            $table->timestamps();

            // Common indexes
            $table->index(['status', 'is_featured'], 'idx_status_featured');
            $table->index(['status', 'created_at'], 'idx_status_created');
            $table->index('title', 'idx_title');
        });

        // ✅ Only for MySQL: add FULLTEXT index safely
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement("ALTER TABLE products ADD FULLTEXT INDEX products_title_fulltext (title)");
            } catch (\Exception $e) {
                // Ignore if fulltext already exists
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
