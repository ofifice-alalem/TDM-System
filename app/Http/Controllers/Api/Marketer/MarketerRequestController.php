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
        $query = DB::table('marketer_requests')
            ->where('marketer_id', $request->user()->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

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
        $requestData = DB::table('marketer_requests')->where('id', $id)->first();
        
        if (!$requestData) {
            return response()->json(['message' => 'الطلب غير موجود'], 404);
        }

        if ($requestData->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية الوصول لهذا الطلب'], 403);
        }

        $requestData = DB::table('marketer_requests')
            ->leftJoin('users as approver', 'marketer_requests.approved_by', '=', 'approver.id')
            ->leftJoin('users as documenter', 'marketer_requests.documented_by', '=', 'documenter.id')
            ->where('marketer_requests.id', $id)
            ->select(
                'marketer_requests.*',
                'approver.full_name as approver_name',
                'documenter.full_name as documenter_name'
            )
            ->first();

        if ($requestData->stamped_image) {
            $requestData->stamped_image = asset('storage/' . $requestData->stamped_image);
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
    public function cancel(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string']);

        $marketerRequest = DB::table('marketer_requests')->where('id', $id)->first();
        
        if (!$marketerRequest) {
            return response()->json(['message' => 'الطلب غير موجود'], 404);
        }

        if ($marketerRequest->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية إلغاء هذا الطلب'], 403);
        }

        if (!in_array($marketerRequest->status, ['pending', 'approved'])) {
            return response()->json(['message' => 'لا يمكن إلغاء طلب موثق أو مرفوض'], 400);
        }

        DB::beginTransaction();
        try {

            if ($marketerRequest->status === 'approved') {
                $items = DB::table('marketer_request_items')->where('request_id', $id)->get();
                foreach ($items as $item) {
                    DB::table('main_stock')->where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity);

                    DB::table('marketer_reserved_stock')
                        ->where('marketer_id', $marketerRequest->marketer_id)
                        ->where('product_id', $item->product_id)
                        ->decrement('quantity', $item->quantity);
                }
            }

            DB::table('marketer_requests')->where('id', $id)->update([
                'status' => 'cancelled',
                'notes' => $request->notes,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم إلغاء الطلب بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء الطلب'], 500);
        }
    }
}
