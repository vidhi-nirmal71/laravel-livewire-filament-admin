<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'is_active',
        'image'
    ];
    public function getImageUrlAttribute(): ?string
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : null;
    }

}
