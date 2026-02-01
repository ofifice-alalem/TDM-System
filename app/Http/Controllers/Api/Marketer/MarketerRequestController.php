<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerRequestController extends Controller
{
    /**
     * عرض جميع طلبات المسوق
     * GET /api/marketer/requests
     */
    public function index(Request $request)
    {
        $requests = DB::table('marketer_requests')
            ->where('marketer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة طلبات المسوق',
            'data' => $requests
        ]);
    }

    /**
     * إنشاء طلب بضاعة جديد
     * POST /api/marketer/requests
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // إنشاء رقم فاتورة فريد
            $invoiceNumber = 'MR-' . date('Ymd') . '-' . str_pad(DB::table('marketer_requests')->count() + 1, 4, '0', STR_PAD_LEFT);

            // إنشاء الطلب
            $requestId = DB::table('marketer_requests')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'marketer_id' => $request->user()->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // إضافة المنتجات
            foreach ($request->items as $item) {
                DB::table('marketer_request_items')->insert([
                    'request_id' => $requestId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => [
                    'id' => $requestId,
                    'invoice_number' => $invoiceNumber
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'فشل إنشاء الطلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل طلب محدد
     * GET /api/marketer/requests/{id}
     */
    public function show(Request $request, $id)
    {
        $requestData = DB::table('marketer_requests')
            ->where('id', $id)
            ->where('marketer_id', $request->user()->id)
            ->first();

        if (!$requestData) {
            return response()->json([
                'message' => 'الطلب غير موجود'
            ], 404);
        }

        $items = DB::table('marketer_request_items')
            ->join('products', 'marketer_request_items.product_id', '=', 'products.id')
            ->where('marketer_request_items.request_id', $id)
            ->select(
                'marketer_request_items.*',
                'products.name as product_name',
                'products.current_price'
            )
            ->get();

        return response()->json([
            'message' => 'تفاصيل الطلب',
            'data' => [
                'request' => $requestData,
                'items' => $items
            ]
        ]);
    }

    /**
     * إلغاء طلب
     * PUT /api/marketer/requests/{id}/cancel
     */
    public function cancel($id)
    {
        // TODO: Implementation
        return response()->json([
            'message' => 'تم إلغاء الطلب بنجاح'
        ]);
    }
}
