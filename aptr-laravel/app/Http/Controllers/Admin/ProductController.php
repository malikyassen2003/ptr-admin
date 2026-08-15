<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('id')
            ->get()
            ->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'brand'       => $p->brand,
                'category'    => $p->category->name,
                'base_price'  => $p->base_price,
                'badge'       => $p->badge,
                'stock'       => $p->stock,
                'has_variants'=> $p->has_variants,
                'is_featured' => $p->is_featured,
                'is_best_selling' => $p->is_best_selling,
                'image'       => $p->image,
            ]);

        $categories = Category::orderBy('name')->get(['id','name']);

        return Inertia::render('Admin/Products/Index', compact('products', 'categories'));
    }
}