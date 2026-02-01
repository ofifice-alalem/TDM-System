<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of stores.
     */
    public function index()
    {
        $stores = Store::active()->paginate(15);

        return response()->json($stores);
    }

    /**
     * Store a newly created store.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
            'address' => 'nullable|string',
        ]);

        $store = Store::create($request->all());

        return response()->json([
            'message' => 'تم إنشاء المتجر بنجاح',
            'store' => $store
        ], 201);
    }

    /**
     * Display the specified store.
     */
    public function show(Store $store)
    {
        return response()->json($store);
    }

    /**
     * Update the specified store.
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
            'address' => 'nullable|string',
        ]);

        $store->update($request->all());

        return response()->json([
            'message' => 'تم تحديث المتجر بنجاح',
            'store' => $store
        ]);
    }

    /**
     * Remove the specified store.
     */
    public function destroy(Store $store)
    {
        $store->update(['is_active' => false]);

        return response()->json([
            'message' => 'تم إلغاء تفعيل المتجر بنجاح'
        ]);
    }
}