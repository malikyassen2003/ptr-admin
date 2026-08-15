<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_color_id', 'label', 'price', 'stock'];

    protected $casts = [
        'price' => 'float',
    ];
}