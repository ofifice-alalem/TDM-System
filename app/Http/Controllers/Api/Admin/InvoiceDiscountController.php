<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDiscountTier;
use App\Http\Resources\InvoiceDiscountResource;
use Illuminate\Http\Request;

class InvoiceDiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = InvoiceDiscountTier::with('creator:id,full_name');

        // Filter by status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter by discount type
        if ($request->has('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $discounts = $query->orderBy('min_amount', 'asc')->get();

        return response()->json([
            'message' => 'قائمة قواعد الخصم',
            'data' => InvoiceDiscountResource::collection($discounts)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|numeric|min:0|max:100',
            'discount_amount' => 'required_if:discount_type,fixed|nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $discount = InvoiceDiscountTier::create([
            'min_amount' => $request->min_amount,
            'discount_type' => $request->discount_type,
            'discount_percentage' => $request->discount_type === 'percentage' ? $request->discount_percentage : null,
            'discount_amount' => $request->discount_type === 'fixed' ? $request->discount_amount : null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => true,
            'created_by' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'تم إنشاء قاعدة الخصم بنجاح',
            'data' => new InvoiceDiscountResource($discount->load('creator'))
        ], 201);
    }

    public function show($id)
    {
        $discount = InvoiceDiscountTier::with('creator')->find($id);

        if (!$discount) {
            return response()->json(['message' => 'قاعدة الخصم غير موجودة'], 404);
        }

        return response()->json([
            'message' => 'تفاصيل قاعدة الخصم',
            'data' => new InvoiceDiscountResource($discount)
        ]);
    }

    public function toggleActive(Request $request, $id)
    {
        $discount = InvoiceDiscountTier::find($id);

        if (!$discount) {
            return response()->json(['message' => 'قاعدة الخصم غير موجودة'], 404);
        }

        $discount->update(['is_active' => !$discount->is_active]);

        return response()->json([
            'message' => $discount->is_active ? 'تم تفعيل قاعدة الخصم' : 'تم تعطيل قاعدة الخصم',
            'data' => new InvoiceDiscountResource($discount->load('creator'))
        ]);
    }

    public function destroy($id)
    {
        $discount = InvoiceDiscountTier::find($id);

        if (!$discount) {
            return response()->json(['message' => 'قاعدة الخصم غير موجودة'], 404);
        }

        $discount->update(['is_active' => false]);

        return response()->json(['message' => 'تم تعطيل قاعدة الخصم نهائياً']);
    }
}
