<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'stock',
        'size',
        'condition',
        'status',
        'price',
        'discount',
        // 'is_featured',
        'cat_id',
        'child_cat_id',
        'brand_id',
        'image',
        'sku',
    ];

    protected $casts = [
        // 'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'cat_id');
    }

    public function childCategory()
    {
        return $this->belongsTo(\App\Models\Category::class, 'child_cat_id');
    }

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id');
    }

    // Accessor that returns a readable category path similar to "Parent > Child"
    public function getCategoryPathAttribute()
    {
        // Prefer child category path if present
        if ($this->childCategory) {
            // use Category::getParentChainAttribute if defined on Category
            $chain = $this->childCategory->getParentChainAttribute() ?: '';
            return trim($chain ? $chain . ' > ' . $this->childCategory->title : $this->childCategory->title);
        }

        if ($this->category) {
            $chain = $this->category->getParentChainAttribute() ?: '';
            return trim($chain ? $chain . ' > ' . $this->category->title : $this->category->title);
        }

        return '-';
    }
}
