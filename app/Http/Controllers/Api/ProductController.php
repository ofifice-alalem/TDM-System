<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->leftJoin('main_stock', 'products.id', '=', 'main_stock.product_id')
            ->select('products.id', 'products.name', 'products.current_price', 'products.description', 'products.barcode', 'products.is_active', 'main_stock.quantity as main_stock_quantity');
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('products.is_active', $request->is_active);
        } else {
            $query->where('products.is_active', true);
        }
        
        $products = $query->get();
        
        return response()->json(['data' => $products]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:50',
        ]);

        $product = DB::table('products')->insertGetId([
            'name' => $validated['name'],
            'current_price' => $validated['price'],
            'description' => $validated['description'],
            'barcode' => $validated['barcode'],
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json(['message' => 'تم إضافة المنتج بنجاح'], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:50',
        ]);

        DB::table('products')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'current_price' => $validated['price'],
                'description' => $validated['description'],
                'barcode' => $validated['barcode'],
                'updated_at' => now()
            ]);
        
        return response()->json(['message' => 'تم تحديث المنتج بنجاح']);
    }
}
