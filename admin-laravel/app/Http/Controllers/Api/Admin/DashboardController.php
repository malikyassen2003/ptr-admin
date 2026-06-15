<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $featured        = Product::where('is_featured', true)->count();
        $bestSelling     = Product::where('is_best_selling', true)->count();
        $lowStock        = Product::where('has_variants', false)
                            ->where('stock', '<=', 5)
                            ->where('stock', '>', 0)
                            ->count();
        $outOfStock      = Product::where('has_variants', false)
                            ->where('stock', 0)
                            ->count();

        $byCategory = Category::withCount('products')
                        ->orderByDesc('products_count')
                        ->get()
                        ->map(fn ($c) => [
                            'name'  => $c->name,
                            'count' => $c->products_count,
                        ]);

        return response()->json([
            'totalProducts'   => $totalProducts,
            'totalCategories' => $totalCategories,
            'featured'        => $featured,
            'bestSelling'     => $bestSelling,
            'lowStock'        => $lowStock,
            'outOfStock'      => $outOfStock,
            'byCategory'      => $byCategory,
        ]);
    }
}