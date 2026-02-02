<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseRequestController extends Controller
{
    /**
     * عرض جميع طلبات المسوقين (للمخزن)
     * GET /api/warehouse/requests
     */
    public function index(Request $request)
    {
        $requests = DB::table('marketer_requests')
            ->join('users', 'marketer_requests.marketer_id', '=', 'users.id')
            ->select('marketer_requests.*', 'users.full_name as marketer_name')
            ->orderBy('marketer_requests.created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة طلبات المسوقين',
            'data' => $requests
        ]);
    }

    /**
     * عرض تفاصيل طلب محدد
     * GET /api/warehouse/requests/{id}
     */
    public function show($id)
    {
        $request = DB::table('marketer_requests')
            ->join('users', 'marketer_requests.marketer_id', '=', 'users.id')
            ->select('marketer_requests.*', 'users.full_name as marketer_name')
            ->where('marketer_requests.id', $id)
            ->first();

        if (!$request) {
            return response()->json(['message' => 'الطلب غير موجود'], 404);
        }

        $items = DB::table('marketer_request_items')
            ->join('products', 'marketer_request_items.product_id', '=', 'products.id')
            ->select('marketer_request_items.*', 'products.name as product_name')
            ->where('marketer_request_items.request_id', $id)
            ->get();

        return response()->json([
            'message' => 'تفاصيل الطلب',
            'data' => [
                'request' => $request,
                'items' => $items
            ]
        ]);
    }

    /**
     * الموافقة على طلب
     * PUT /api/warehouse/requests/{id}/approve
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $request = DB::table('marketer_requests')->where('id', $id)->where('status', 'pending')->first();
            if (!$request) {
                return response()->json(['message' => 'الطلب غير موجود أو تم معالجته مسبقاً'], 404);
            }

            $items = DB::table('marketer_request_items')->where('request_id', $id)->get();

            foreach ($items as $item) {
                $stock = DB::table('main_stock')->where('product_id', $item->product_id)->first();
                if (!$stock || $stock->quantity < $item->quantity) {
                    DB::rollBack();
                    return response()->json(['message' => 'المخزون غير كافٍ'], 400);
                }

                DB::table('main_stock')->where('product_id', $item->product_id)
                    ->decrement('quantity', $item->quantity);

                $reserved = DB::table('marketer_reserved_stock')
                    ->where('marketer_id', $request->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($reserved) {
                    DB::table('marketer_reserved_stock')
                        ->where('marketer_id', $request->marketer_id)
                        ->where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                } else {
                    DB::table('marketer_reserved_stock')->insert([
                        'marketer_id' => $request->marketer_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::table('marketer_requests')->where('id', $id)->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تمت الموافقة على الطلب بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء الموافقة'], 500);
        }
    }

    /**
     * رفض طلب
     * PUT /api/warehouse/requests/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);

        DB::beginTransaction();
        try {
            $marketerRequest = DB::table('marketer_requests')->where('id', $id)->first();
            if (!$marketerRequest || !in_array($marketerRequest->status, ['pending', 'approved'])) {
                return response()->json(['message' => 'الطلب غير موجود أو لا يمكن رفضه'], 404);
            }

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
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'notes' => $request->notes,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم رفض الطلب']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء رفض الطلب'], 500);
        }
    }

    /**
     * توثيق استلام البضاعة
     * POST /api/warehouse/requests/{id}/document
     */
    public function document(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $marketerRequest = DB::table('marketer_requests')->where('id', $id)->where('status', 'approved')->first();
            if (!$marketerRequest) {
                return response()->json(['message' => 'الطلب غير موجود أو غير موافق عليه'], 404);
            }

            $items = DB::table('marketer_request_items')->where('request_id', $id)->get();

            foreach ($items as $item) {
                DB::table('marketer_reserved_stock')
                    ->where('marketer_id', $marketerRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->decrement('quantity', $item->quantity);

                $actual = DB::table('marketer_actual_stock')
                    ->where('marketer_id', $marketerRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($actual) {
                    DB::table('marketer_actual_stock')
                        ->where('marketer_id', $marketerRequest->marketer_id)
                        ->where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                } else {
                    DB::table('marketer_actual_stock')->insert([
                        'marketer_id' => $marketerRequest->marketer_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
            }

            DB::table('marketer_requests')->where('id', $id)->update([
                'status' => 'documented',
                'documented_by' => auth()->id(),
                'documented_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم توثيق استلام البضاعة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء التوثيق'], 500);
        }
    }

    /**
     * إلغاء طلب
     * PUT /api/warehouse/requests/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);

        DB::beginTransaction();
        try {
            $marketerRequest = DB::table('marketer_requests')->where('id', $id)->first();
            if (!$marketerRequest || !in_array($marketerRequest->status, ['pending', 'approved'])) {
                return response()->json(['message' => 'الطلب غير موجود أو لا يمكن إلغاؤه'], 404);
            }

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
