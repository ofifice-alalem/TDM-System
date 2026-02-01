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
        try {
            $requests = MarketerRequest::with(['items.product', 'marketer'])
                ->where('marketer_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' => $requests->map(function($req) {
                    return [
                        'id' => $req->id,
                        'invoice_number' => $req->invoice_number,
                        'status' => $req->status,
                        'notes' => $req->notes,
                        'created_at' => $req->created_at->format('Y-m-d H:i:s'),
                        'user' => [
                            'full_name' => $req->marketer->full_name ?? 'Unknown'
                        ],
                        'items' => $req->items
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        $request = MarketerRequest::with(['items.product', 'marketer', 'approvedBy', 'rejectedBy', 'documentedBy'])
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
