<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\SalesInvoice;
use App\Models\StoreActualStock;
use App\Models\StoreReturnPendingStock;
use App\Http\Resources\SalesReturnResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerStoreReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesReturn::where('marketer_id', $request->user()->id)
            ->with(['store:id,name', 'salesInvoice:id,invoice_number', 'keeper:id,full_name']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by store
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
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
            'data' => [
                'current_page' => $returns->currentPage(),
                'data' => SalesReturnResource::collection($returns->items()),
                'first_page_url' => $returns->url(1),
                'from' => $returns->firstItem(),
                'last_page' => $returns->lastPage(),
                'last_page_url' => $returns->url($returns->lastPage()),
                'next_page_url' => $returns->nextPageUrl(),
                'path' => $returns->path(),
                'per_page' => $returns->perPage(),
                'prev_page_url' => $returns->previousPageUrl(),
                'to' => $returns->lastItem(),
                'total' => $returns->total()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_invoice_id' => 'required|exists:sales_invoices,id',
            'items' => 'required|array|min:1',
            'items.*.sales_invoice_item_id' => 'required|exists:sales_invoice_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $invoice = SalesInvoice::with('items')->findOrFail($request->sales_invoice_id);
            
            if ($invoice->status !== 'approved') {
                return response()->json(['message' => 'لا يمكن الإرجاع من فاتورة غير موثقة'], 400);
            }

            if ($invoice->marketer_id !== $request->user()->id) {
                return response()->json(['message' => 'غير مصرح لك بإرجاع هذه الفاتورة'], 403);
            }

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $invoiceItem = $invoice->items->firstWhere('id', $item['sales_invoice_item_id']);
                if (!$invoiceItem) {
                    return response()->json(['message' => 'بند غير موجود في الفاتورة'], 400);
                }
                
                $alreadyReturned = SalesReturnItem::whereHas('salesReturn', function($q) use ($invoice) {
                    $q->where('sales_invoice_id', $invoice->id)
                      ->whereIn('status', ['pending', 'approved']);
                })->where('sales_invoice_item_id', $item['sales_invoice_item_id'])
                  ->sum('quantity');

                if ($item['quantity'] + $alreadyReturned > $invoiceItem->quantity) {
                    return response()->json(['message' => 'الكمية المرجعة أكبر من المتاحة'], 400);
                }

                $totalAmount += $item['quantity'] * $invoiceItem->unit_price;
            }

            $returnNumber = 'RET-' . date('Ymd') . '-' . str_pad(SalesReturn::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $salesReturn = SalesReturn::create([
                'return_number' => $returnNumber,
                'sales_invoice_id' => $invoice->id,
                'store_id' => $invoice->store_id,
                'marketer_id' => $request->user()->id,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            foreach ($request->items as $item) {
                $invoiceItem = $invoice->items->firstWhere('id', $item['sales_invoice_item_id']);
                
                SalesReturnItem::create([
                    'return_id' => $salesReturn->id,
                    'sales_invoice_item_id' => $item['sales_invoice_item_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $invoiceItem->unit_price
                ]);

                $storeStock = StoreActualStock::where('store_id', $invoice->store_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$storeStock || $storeStock->quantity < $item['quantity']) {
                    throw new \Exception('مخزون المتجر غير كافٍ');
                }

                $storeStock->decrement('quantity', $item['quantity']);

                StoreReturnPendingStock::create([
                    'return_id' => $salesReturn->id,
                    'store_id' => $invoice->store_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'تم إنشاء طلب الإرجاع بنجاح',
                'data' => new SalesReturnResource($salesReturn->load('items.product'))
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل إنشاء طلب الإرجاع', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $return = SalesReturn::where('id', $id)
            ->where('marketer_id', $request->user()->id)
            ->with(['items.product', 'store', 'salesInvoice', 'keeper'])
            ->first();

        if (!$return) {
            return response()->json(['message' => 'طلب الإرجاع غير موجود'], 404);
        }

        return response()->json([
            'message' => 'تفاصيل طلب الإرجاع',
            'data' => new SalesReturnResource($return)
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $request->validate(['notes' => 'nullable|string']);

        DB::beginTransaction();
        try {
            $return = SalesReturn::where('id', $id)
                ->where('marketer_id', $request->user()->id)
                ->where('status', 'pending')
                ->with('items')
                ->first();

            if (!$return) {
                return response()->json(['message' => 'طلب الإرجاع غير موجود أو لا يمكن إلغاؤه'], 404);
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
                'status' => 'cancelled',
                'notes' => $request->notes
            ]);

            DB::commit();
            return response()->json(['message' => 'تم إلغاء طلب الإرجاع بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء طلب الإرجاع'], 500);
        }
    }
}
