<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\SalesReturn;
use App\Models\StoreReturnPendingStock;
use App\Models\StoreActualStock;
use App\Models\MarketerActualStock;
use App\Models\StoreDebtLedger;
use App\Http\Resources\SalesReturnResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStoreReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesReturn::with(['store:id,name', 'marketer:id,full_name', 'salesInvoice:id,invoice_number', 'keeper:id,full_name'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->get();

        return response()->json([
            'message' => 'قائمة طلبات الإرجاع',
            'data' => SalesReturnResource::collection($returns)
        ]);
    }

    public function show($id)
    {
        $return = SalesReturn::with(['items.product', 'store', 'marketer', 'salesInvoice', 'keeper'])
            ->find($id);

        if (!$return) {
            return response()->json(['message' => 'طلب الإرجاع غير موجود'], 404);
        }

        return response()->json([
            'message' => 'تفاصيل طلب الإرجاع',
            'data' => new SalesReturnResource($return)
        ]);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'stamped_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $return = SalesReturn::where('id', $id)
                ->where('status', 'pending')
                ->with('items')
                ->first();

            if (!$return) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو تمت معالجته'], 404);
            }

            $imagePath = $request->file('stamped_image')->store('stamped_return', 'public');

            foreach ($return->items as $item) {
                $marketerStock = MarketerActualStock::where('marketer_id', $return->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($marketerStock) {
                    $marketerStock->increment('quantity', $item->quantity);
                } else {
                    MarketerActualStock::create([
                        'marketer_id' => $return->marketer_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
            }

            StoreReturnPendingStock::where('return_id', $return->id)->delete();

            StoreDebtLedger::create([
                'store_id' => $return->store_id,
                'entry_type' => 'return',
                'sales_invoice_id' => null,
                'return_id' => $return->id,
                'payment_id' => null,
                'amount' => -$return->total_amount
            ]);

            $return->update([
                'status' => 'approved',
                'keeper_id' => $request->user()->id,
                'stamped_image' => $imagePath,
                'confirmed_at' => now()
            ]);

            DB::commit();
            return response()->json([
                'message' => 'تم توثيق طلب الإرجاع بنجاح',
                'data' => new SalesReturnResource($return->load('items.product'))
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل توثيق طلب الإرجاع', 'error' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);

        DB::beginTransaction();
        try {
            $return = SalesReturn::where('id', $id)
                ->where('status', 'pending')
                ->with('items')
                ->first();

            if (!$return) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو تمت معالجته'], 404);
            }

            foreach ($return->items as $item) {
                $storeStock = StoreActualStock::where('store_id', $return->store_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($storeStock) {
                    $storeStock->increment('quantity', $item->quantity);
                } else {
                    StoreActualStock::create([
                        'store_id' => $return->store_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
            }

            StoreReturnPendingStock::where('return_id', $return->id)->delete();

            $return->update([
                'status' => 'rejected',
                'keeper_id' => $request->user()->id,
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json([
                'message' => 'تم رفض طلب الإرجاع',
                'data' => new SalesReturnResource($return)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل رفض طلب الإرجاع'], 500);
        }
    }
}
