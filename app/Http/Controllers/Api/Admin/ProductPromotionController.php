<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductPromotion;
use App\Http\Resources\ProductPromotionResource;
use Illuminate\Http\Request;

class ProductPromotionController extends Controller
{
    public function index()
    {
        $promotions = ProductPromotion::with(['product:id,name', 'creator:id,full_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة العروض الترويجية',
            'data' => ProductPromotionResource::collection($promotions)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'min_quantity' => 'required|integer|min:1',
            'free_quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $promotion = ProductPromotion::create([
            'product_id' => $request->product_id,
            'min_quantity' => $request->min_quantity,
            'free_quantity' => $request->free_quantity,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => true,
            'created_by' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'تم إنشاء العرض الترويجي بنجاح',
            'data' => new ProductPromotionResource($promotion->load(['product', 'creator']))
        ], 201);
    }

    public function show($id)
    {
        $promotion = ProductPromotion::with(['product', 'creator'])->find($id);

        if (!$promotion) {
            return response()->json(['message' => 'العرض الترويجي غير موجود'], 404);
        }

        return response()->json([
            'message' => 'تفاصيل العرض الترويجي',
            'data' => new ProductPromotionResource($promotion)
        ]);
    }

    public function toggleActive(Request $request, $id)
    {
        $promotion = ProductPromotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'العرض الترويجي غير موجود'], 404);
        }

        $promotion->update(['is_active' => !$promotion->is_active]);

        return response()->json([
            'message' => $promotion->is_active ? 'تم تفعيل العرض الترويجي' : 'تم تعطيل العرض الترويجي',
            'data' => new ProductPromotionResource($promotion->load(['product', 'creator']))
        ]);
    }

    public function destroy($id)
    {
        $promotion = ProductPromotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'العرض الترويجي غير موجود'], 404);
        }

        $promotion->update(['is_active' => false]);

        return response()->json(['message' => 'تم تعطيل العرض الترويجي نهائياً']);
    }
}
