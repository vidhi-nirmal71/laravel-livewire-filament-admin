<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','email','phone','address','city','state','zip','total_amount','status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
