<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with('mainStock')
                          ->active()
                          ->paginate(15);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'barcode' => 'nullable|string|max:50|unique:products',
            'description' => 'nullable|string',
            'current_price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($request->all());

        return (new ProductResource($product))
            ->additional(['message' => 'تم إنشاء المنتج بنجاح'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load('mainStock'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'current_price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return (new ProductResource($product))
            ->additional(['message' => 'تم تحديث المنتج بنجاح']);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);

        return response()->json([
            'message' => 'تم إلغاء تفعيل المنتج بنجاح'
        ]);
    }
}