<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketerRequestResource;
use App\Models\MarketerRequest;
use App\Models\MainStock;
use App\Models\MarketerReservedStock;
use App\Models\MarketerActualStock;
use App\Models\WarehouseStockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $requests = MarketerRequest::with(['items.product', 'marketer', 'approvedBy', 'rejectedBy', 'documentedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return MarketerRequestResource::collection($requests);
    }

    public function show($id)
    {
        $request = MarketerRequest::with(['items.product', 'marketer', 'approvedBy', 'rejectedBy', 'documentedBy'])
            ->findOrFail($id);

        return new MarketerRequestResource($request);
    }

    public function approve(Request $request, $id)
    {
        $marketerRequest = MarketerRequest::with('items')->where('status', 'pending')->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($marketerRequest->items as $item) {
                $mainStock = MainStock::where('product_id', $item->product_id)->lockForUpdate()->first();
                
                if (!$mainStock || $mainStock->quantity < $item->quantity) {
                    DB::rollBack();
                    return response()->json(['message' => 'الكمية غير متوفرة في المخزن الرئيسي'], 400);
                }

                $mainStock->decrement('quantity', $item->quantity);

                $reservedStock = MarketerReservedStock::firstOrCreate(
                    ['marketer_id' => $marketerRequest->marketer_id, 'product_id' => $item->product_id],
                    ['quantity' => 0]
                );
                $reservedStock->increment('quantity', $item->quantity);
            }

            $marketerRequest->update([
                'status' => 'approved',
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            return new MarketerRequestResource($marketerRequest->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل الموافقة على الطلب', 'error' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        $marketerRequest = MarketerRequest::with('items')->whereIn('status', ['pending', 'approved'])->findOrFail($id);

        DB::beginTransaction();
        try {
            if ($marketerRequest->status === 'approved') {
                foreach ($marketerRequest->items as $item) {
                    $mainStock = MainStock::where('product_id', $item->product_id)->first();
                    if ($mainStock) {
                        $mainStock->increment('quantity', $item->quantity);
                    }

                    $reservedStock = MarketerReservedStock::where('marketer_id', $marketerRequest->marketer_id)
                        ->where('product_id', $item->product_id)
                        ->first();
                    if ($reservedStock) {
                        $reservedStock->decrement('quantity', $item->quantity);
                    }
                }
            }

            $marketerRequest->update([
                'status' => 'rejected',
                'rejected_by' => $request->user()->id,
                'rejected_at' => now(),
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return new MarketerRequestResource($marketerRequest->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل رفض الطلب', 'error' => $e->getMessage()], 500);
        }
    }

    public function document(Request $request, $id)
    {
        $validated = $request->validate([
            'stamped_image' => 'required|image|max:2048',
        ]);

        $marketerRequest = MarketerRequest::with('items')->where('status', 'approved')->findOrFail($id);

        DB::beginTransaction();
        try {
            $imagePath = $request->file('stamped_image')->store('stamped_invoices', 'public');

            foreach ($marketerRequest->items as $item) {
                $reservedStock = MarketerReservedStock::where('marketer_id', $marketerRequest->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->first();
                if ($reservedStock) {
                    $reservedStock->decrement('quantity', $item->quantity);
                }

                $actualStock = MarketerActualStock::firstOrCreate(
                    ['marketer_id' => $marketerRequest->marketer_id, 'product_id' => $item->product_id],
                    ['quantity' => 0]
                );
                $actualStock->increment('quantity', $item->quantity);
            }

            WarehouseStockLog::create([
                'invoice_type' => 'marketer_request',
                'invoice_id' => $marketerRequest->id,
                'keeper_id' => $request->user()->id,
                'action' => 'withdraw',
            ]);

            $marketerRequest->update([
                'status' => 'documented',
                'documented_by' => $request->user()->id,
                'documented_at' => now(),
                'stamped_image' => $imagePath,
            ]);

            DB::commit();

            return new MarketerRequestResource($marketerRequest->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل توثيق الطلب', 'error' => $e->getMessage()], 500);
        }
    }
}
