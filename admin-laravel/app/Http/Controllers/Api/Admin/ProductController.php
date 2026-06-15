<?php
// FILE: app/Http/Controllers/Api/Admin/ProductController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSpec;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('id')
            ->get()
            ->map(fn ($p) => [
                'id'              => $p->id,
                'name'            => $p->name,
                'brand'           => $p->brand,
                'short_spec'      => $p->short_spec,
                'category'        => $p->category->name,
                'base_price'      => $p->base_price,
                'original_price'  => $p->original_price,
                'discount'        => $p->discount,
                'badge'           => $p->badge,
                'stock'           => $p->stock,
                'description'     => $p->description,
                'image'           => $p->image,
                'has_variants'    => $p->has_variants,
                'is_featured'     => $p->is_featured,
                'is_best_selling' => $p->is_best_selling,
            ]);

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'products'   => $products,
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $product = Product::with([
            'category',
            'specs'          => fn ($q) => $q->orderBy('sort_order'),
            'colors.variants',
            'colors.images'  => fn ($q) => $q->orderBy('sort_order'),
            'images'         => fn ($q) => $q->orderBy('sort_order'),
        ])->findOrFail($id);

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'brand'           => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'short_spec'      => 'required|string|max:255',
            'base_price'      => 'required|numeric|min:0',
            'original_price'  => 'nullable|numeric|min:0',
            'discount'        => 'nullable|integer|min:0|max:100',
            'badge'           => 'nullable|in:Hot,New,Sale',
            'description'     => 'nullable|string',
            'image'           => 'nullable|string',
            'is_featured'     => 'boolean',
            'is_best_selling' => 'boolean',
            'has_variants'    => 'boolean',
            'stock'           => 'integer|min:0',
            'specs'           => 'nullable|array',
            'specs.*.key'     => 'required|string|max:255',
            'specs.*.value'   => 'required|string|max:255',
            'colors'          => 'nullable|array',
        ]);

        $product = Product::create([
            'category_id'    => $validated['category_id'],
            'brand'          => $validated['brand'],
            'name'           => $validated['name'],
            'short_spec'     => $validated['short_spec'],
            'base_price'     => $validated['base_price'],
            'original_price' => $validated['original_price'] ?? null,
            'discount'       => $validated['discount'] ?? 0,
            'badge'          => $validated['badge'] ?? null,
            'description'    => $validated['description'] ?? null,
            'image'          => $validated['image'] ?? null,
            'is_featured'    => $validated['is_featured'] ?? false,
            'is_best_selling'=> $validated['is_best_selling'] ?? false,
            'has_variants'   => $validated['has_variants'] ?? false,
            'stock'          => $validated['stock'] ?? 0,
        ]);

        // Save specs
        $this->syncSpecs($product, $request->input('specs', []));

        // Save colors & variants
        $this->syncColors($product, $request->input('colors', []));

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product->load(['specs', 'colors.variants', 'colors.images']),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id'     => 'sometimes|exists:categories,id',
            'brand'           => 'sometimes|string|max:255',
            'name'            => 'sometimes|string|max:255',
            'short_spec'      => 'sometimes|string|max:255',
            'base_price'      => 'sometimes|numeric|min:0',
            'original_price'  => 'nullable|numeric|min:0',
            'discount'        => 'nullable|integer|min:0|max:100',
            'badge'           => 'nullable|in:Hot,New,Sale',
            'description'     => 'nullable|string',
            'image'           => 'nullable|string',
            'is_featured'     => 'boolean',
            'is_best_selling' => 'boolean',
            'has_variants'    => 'boolean',
            'stock'           => 'integer|min:0',
            'specs'           => 'nullable|array',
            'specs.*.key'     => 'required|string|max:255',
            'specs.*.value'   => 'required|string|max:255',
            'colors'          => 'nullable|array',
        ]);

        $product->update([
            'category_id'    => $validated['category_id']     ?? $product->category_id,
            'brand'          => $validated['brand']           ?? $product->brand,
            'name'           => $validated['name']            ?? $product->name,
            'short_spec'     => $validated['short_spec']      ?? $product->short_spec,
            'base_price'     => $validated['base_price']      ?? $product->base_price,
            'original_price' => $validated['original_price']  ?? null,
            'discount'       => $validated['discount']        ?? 0,
            'badge'          => $validated['badge']           ?? null,
            'description'    => $validated['description']     ?? null,
            'image'          => $validated['image']           ?? null,
            'is_featured'    => $validated['is_featured']     ?? $product->is_featured,
            'is_best_selling'=> $validated['is_best_selling'] ?? $product->is_best_selling,
            'has_variants'   => $validated['has_variants']    ?? $product->has_variants,
            'stock'          => $validated['stock']           ?? $product->stock,
        ]);

        // Sync specs
        if ($request->has('specs')) {
            $this->syncSpecs($product, $request->input('specs', []));
        }

        // Sync colors & variants
        if ($request->has('colors')) {
            $this->syncColors($product, $request->input('colors', []));
        }

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product->fresh()->load(['specs', 'colors.variants', 'colors.images']),
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }

    // ── Helpers ────────────────────────────────────────────────────

    private function syncSpecs(Product $product, array $specs): void
    {
        // Delete all existing specs and re-create
        $product->specs()->delete();

        foreach ($specs as $i => $spec) {
            if (!empty($spec['key']) && !empty($spec['value'])) {
                ProductSpec::create([
                    'product_id' => $product->id,
                    'key'        => $spec['key'],
                    'value'      => $spec['value'],
                    'sort_order' => $spec['sort_order'] ?? $i,
                ]);
            }
        }
    }

    private function syncColors(Product $product, array $colors): void
    {
        $incomingColorIds = collect($colors)->pluck('id')->filter()->values();

        // Delete colors not in the incoming list
        $product->colors()->whereNotIn('id', $incomingColorIds)->each(function ($color) {
            $color->variants()->delete();
            $color->images()->delete();
            $color->delete();
        });

        foreach ($colors as $colorData) {
            // Upsert color
            if (!empty($colorData['id'])) {
                $color = ProductColor::find($colorData['id']);
                if ($color) {
                    $color->update(['name' => $colorData['name'], 'hex' => $colorData['hex']]);
                }
            } else {
                $color = ProductColor::create([
                    'product_id' => $product->id,
                    'name'       => $colorData['name'],
                    'hex'        => $colorData['hex'],
                ]);
            }

            if (!$color) continue;

            // Sync images for this color
            $color->images()->delete();
            foreach ($colorData['images'] ?? [] as $i => $img) {
                if (!empty($img['url'])) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url'        => $img['url'],
                        'sort_order' => $i,
                    ]);
                }
            }

            // Sync variants
            $incomingVariantIds = collect($colorData['variants'] ?? [])->pluck('id')->filter()->values();
            $color->variants()->whereNotIn('id', $incomingVariantIds)->delete();

            foreach ($colorData['variants'] ?? [] as $varData) {
                if (empty($varData['label'])) continue;

                if (!empty($varData['id'])) {
                    $variant = ProductVariant::find($varData['id']);
                    if ($variant) {
                        $variant->update([
                            'label' => $varData['label'],
                            'price' => $varData['price'] ?? 0,
                            'stock' => $varData['stock'] ?? 0,
                        ]);
                    }
                } else {
                    ProductVariant::create([
                        'product_color_id' => $color->id,
                        'label'            => $varData['label'],
                        'price'            => $varData['price'] ?? 0,
                        'stock'            => $varData['stock'] ?? 0,
                    ]);
                }
            }
        }
    }
}