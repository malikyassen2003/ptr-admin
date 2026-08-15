<?php
// FILE: app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'subtotal', 'shipping_cost',
        'discount', 'total', 'payment_method', 'payment_status', 'notes',
    ];

    protected $casts = [
        'subtotal'      => 'float',
        'shipping_cost' => 'float',
        'discount'      => 'float',
        'total'         => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}