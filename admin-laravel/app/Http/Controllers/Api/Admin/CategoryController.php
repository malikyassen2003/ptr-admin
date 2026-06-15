<?php
// FILE: app/Http/Controllers/Api/Admin/CategoryController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
        'icon' => 'nullable|string|max:255',
    ]);

    // Generate unique slug
    $baseSlug = Str::slug($validated['name']);
    $slug     = $baseSlug;
    $count    = 1;
    while (Category::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $count++;
    }
    $validated['slug'] = $slug;

    $category = Category::create($validated);

    return response()->json([
        'message'  => 'Category created successfully.',
        'category' => $category,
    ], 201);
}

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:255',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json([
            'message'  => 'Category updated successfully.',
            'category' => $category->fresh(),
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing products.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
