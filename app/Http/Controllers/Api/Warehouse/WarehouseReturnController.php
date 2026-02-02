<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseReturnController extends Controller
{
    /**
     * عرض جميع طلبات الإرجاع
     */
    public function index()
    {
        $returns = DB::table('marketer_return_requests')
            ->join('users', 'marketer_return_requests.marketer_id', '=', 'users.id')
            ->select('marketer_return_requests.*', 'users.full_name as marketer_name')
            ->orderBy('marketer_return_requests.created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'قائمة طلبات الإرجاع',
            'data' => $returns
        ]);
    }

    /**
     * عرض تفاصيل طلب إرجاع محدد
     */
    public function show($id)
    {
        $returnData = DB::table('marketer_return_requests')
            ->join('users', 'marketer_return_requests.marketer_id', '=', 'users.id')
            ->leftJoin('users as approver', 'marketer_return_requests.approved_by', '=', 'approver.id')
            ->leftJoin('users as documenter', 'marketer_return_requests.documented_by', '=', 'documenter.id')
            ->leftJoin('users as rejecter', 'marketer_return_requests.rejected_by', '=', 'rejecter.id')
            ->select(
                'marketer_return_requests.*',
                'users.full_name as marketer_name',
                'approver.full_name as approver_name',
                'documenter.full_name as documenter_name',
                'rejecter.full_name as rejecter_name'
            )
            ->where('marketer_return_requests.id', $id)
            ->first();

        if (!$returnData) {
            return response()->json(['message' => 'طلب الإرجاع غير موجود'], 404);
        }

        if ($returnData->stamped_image) {
            $returnData->stamped_image = asset('storage/' . $returnData->stamped_image);
        }

        $items = DB::table('marketer_return_items')
            ->join('products', 'marketer_return_items.product_id', '=', 'products.id')
            ->select('marketer_return_items.*', 'products.name as product_name')
            ->where('marketer_return_items.return_request_id', $id)
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
     * الموافقة على طلب إرجاع
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $returnRequest = DB::table('marketer_return_requests')
                ->where('id', $id)
                ->where('status', 'pending')
                ->first();

            if (!$returnRequest) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو تم معالجته مسبقاً'], 404);
            }

            // التحقق من توفر الكميات في مخزون المسوق الفعلي
            $items = DB::table('marketer_return_items')->where('return_request_id', $id)->get();

            foreach ($items as $item) {
                $actualStock = DB::table('marketer_actual_stock')
                    ->where('marketer_id', $returnRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$actualStock || $actualStock->quantity < $item->quantity) {
                    DB::rollBack();
                    return response()->json(['message' => 'الكمية غير متوفرة في مخزون المسوق'], 400);
                }
            }

            DB::table('marketer_return_requests')->where('id', $id)->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تمت الموافقة على طلب الإرجاع بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء الموافقة'], 500);
        }
    }

    /**
     * رفض طلب إرجاع
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);

        DB::beginTransaction();
        try {
            $returnRequest = DB::table('marketer_return_requests')->where('id', $id)->first();

            if (!$returnRequest || !in_array($returnRequest->status, ['pending', 'approved'])) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو لا يمكن رفضه'], 404);
            }

            DB::table('marketer_return_requests')->where('id', $id)->update([
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'notes' => $request->notes,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم رفض طلب الإرجاع']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء رفض طلب الإرجاع'], 500);
        }
    }

    /**
     * توثيق استلام البضاعة المرجعة
     */
    public function document(Request $request, $id)
    {
        $request->validate([
            'stamped_image' => 'required|image|max:10240'
        ]);

        DB::beginTransaction();
        try {
            $returnRequest = DB::table('marketer_return_requests')
                ->where('id', $id)
                ->where('status', 'approved')
                ->first();

            if (!$returnRequest) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو غير موافق عليه'], 404);
            }

            $imagePath = null;
            if ($request->hasFile('stamped_image')) {
                $invoiceNumber = $returnRequest->invoice_number;
                $directory = "stamped_return/{$invoiceNumber}";
                $imagePath = $request->file('stamped_image')->store($directory, 'public');
            }

            $items = DB::table('marketer_return_items')->where('return_request_id', $id)->get();

            foreach ($items as $item) {
                // خصم من مخزون المسوق الفعلي
                DB::table('marketer_actual_stock')
                    ->where('marketer_id', $returnRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->decrement('quantity', $item->quantity);

                // إضافة إلى المخزن الرئيسي مباشرة
                DB::table('main_stock')
                    ->where('product_id', $item->product_id)
                    ->increment('quantity', $item->quantity);
            }

            DB::table('marketer_return_requests')->where('id', $id)->update([
                'status' => 'documented',
                'documented_by' => auth()->id(),
                'documented_at' => now(),
                'stamped_image' => $imagePath,
                'updated_at' => now()
            ]);

            DB::table('warehouse_stock_logs')->insert([
                'invoice_type' => 'marketer_return',
                'invoice_id' => $id,
                'keeper_id' => auth()->id(),
                'action' => 'return',
                'created_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم توثيق استلام البضاعة المرجعة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء التوثيق'], 500);
        }
    }
}
