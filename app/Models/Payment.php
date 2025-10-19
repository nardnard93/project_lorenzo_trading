<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'reference_number',
        'status',
        'paid_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
