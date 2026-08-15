<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $fillable = ['product_id', 'name', 'hex'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}