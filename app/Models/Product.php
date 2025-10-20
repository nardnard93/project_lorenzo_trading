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

    // Convert image blob to Base64 for display
    public function getImageUrlAttribute()
    {
        return $this->image ? 'data:image/jpeg;base64,' . base64_encode($this->image) : null;
    }
}
