<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketerRequestResource;
use App\Models\MarketerRequest;
use App\Models\MarketerRequestItem;
use App\Models\MainStock;
use App\Models\MarketerReservedStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = MarketerRequest::with(['items.product', 'approvedBy', 'rejectedBy', 'documentedBy'])
            ->where('marketer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return MarketerRequestResource::collection($requests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $invoiceNumber = 'MR-' . date('Ymd') . '-' . str_pad(MarketerRequest::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $marketerRequest = MarketerRequest::create([
                'invoice_number' => $invoiceNumber,
                'marketer_id' => $request->user()->id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                MarketerRequestItem::create([
                    'request_id' => $marketerRequest->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return new MarketerRequestResource($marketerRequest->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل إنشاء الطلب', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $request = MarketerRequest::with(['items.product', 'approvedBy', 'rejectedBy', 'documentedBy'])
            ->where('marketer_id', auth()->user()->id)
            ->findOrFail($id);

        return new MarketerRequestResource($request);
    }

    public function cancel($id)
    {
        $marketerRequest = MarketerRequest::where('marketer_id', auth()->user()->id)
            ->whereIn('status', ['pending', 'approved'])
            ->findOrFail($id);

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

            $marketerRequest->update(['status' => 'cancelled']);

            DB::commit();

            return new MarketerRequestResource($marketerRequest->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل إلغاء الطلب', 'error' => $e->getMessage()], 500);
        }
    }
}
