<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerReturnController extends Controller
{
    /**
     * عرض جميع طلبات الإرجاع للمسوق
     */
    public function index(Request $request)
    {
        $query = DB::table('marketer_return_requests')
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

        $returns = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'message' => 'قائمة طلبات الإرجاع',
            'data' => $returns
        ]);
    }

    /**
     * إنشاء طلب إرجاع جديد
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
            // التحقق من توفر الكميات في مخزون المسوق الفعلي
            foreach ($request->items as $item) {
                $actualStock = DB::table('marketer_actual_stock')
                    ->where('marketer_id', $request->user()->id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$actualStock || $actualStock->quantity < $item['quantity']) {
                    return response()->json([
                        'message' => 'الكمية المطلوبة غير متوفرة في مخزونك'
                    ], 400);
                }
            }

            // إنشاء رقم فاتورة فريد
            $invoiceNumber = 'MRR-' . date('Ymd') . '-' . str_pad(DB::table('marketer_return_requests')->count() + 1, 4, '0', STR_PAD_LEFT);

            // إنشاء طلب الإرجاع
            $returnId = DB::table('marketer_return_requests')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'marketer_id' => $request->user()->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // إضافة المنتجات
            foreach ($request->items as $item) {
                DB::table('marketer_return_items')->insert([
                    'return_request_id' => $returnId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'تم إنشاء طلب الإرجاع بنجاح',
                'data' => [
                    'id' => $returnId,
                    'invoice_number' => $invoiceNumber
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'فشل إنشاء طلب الإرجاع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل طلب إرجاع محدد
     */
    public function show(Request $request, $id)
    {
        $returnData = DB::table('marketer_return_requests')
            ->leftJoin('users as approver', 'marketer_return_requests.approved_by', '=', 'approver.id')
            ->leftJoin('users as documenter', 'marketer_return_requests.documented_by', '=', 'documenter.id')
            ->where('marketer_return_requests.id', $id)
            ->where('marketer_return_requests.marketer_id', $request->user()->id)
            ->select(
                'marketer_return_requests.*',
                'approver.full_name as approver_name',
                'documenter.full_name as documenter_name'
            )
            ->first();

        if (!$returnData) {
            return response()->json(['message' => 'طلب الإرجاع غير موجود'], 404);
        }

        if ($returnData->stamped_image) {
            $returnData->stamped_image = asset('storage/' . $returnData->stamped_image);
        }

        $items = DB::table('marketer_return_items')
            ->join('products', 'marketer_return_items.product_id', '=', 'products.id')
            ->where('marketer_return_items.return_request_id', $id)
            ->select(
                'marketer_return_items.*',
                'products.name as product_name',
                'products.current_price'
            )
            ->get();

        return response()->json([
            'message' => 'تفاصيل طلب الإرجاع',
            'data' => [
                'return' => $returnData,
                'items' => $items
            ]
        ]);
    }

    /**
     * إلغاء طلب إرجاع
     */
    public function cancel(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string']);

        DB::beginTransaction();
        try {
            $returnRequest = DB::table('marketer_return_requests')
                ->where('id', $id)
                ->where('marketer_id', $request->user()->id)
                ->first();

            if (!$returnRequest || !in_array($returnRequest->status, ['pending', 'approved'])) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو لا يمكن إلغاؤه'], 404);
            }

            DB::table('marketer_return_requests')->where('id', $id)->update([
                'status' => 'cancelled',
                'notes' => $request->notes,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم إلغاء طلب الإرجاع بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء طلب الإرجاع'], 500);
        }
    }
}
