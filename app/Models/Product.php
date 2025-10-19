<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'cost_price',
        'image',
        'current_stock',
        'min_stock_level',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
