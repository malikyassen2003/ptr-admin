<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'brand', 'name', 'short_spec',
        'base_price', 'original_price', 'discount', 'badge',
        'description', 'image', 'is_featured', 'is_best_selling',
        'has_variants', 'stock',
    ];

    protected $casts = [
        'is_featured'     => 'boolean',
        'is_best_selling' => 'boolean',
        'has_variants'    => 'boolean',
        'base_price'      => 'float',
        'original_price'  => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
    }
}