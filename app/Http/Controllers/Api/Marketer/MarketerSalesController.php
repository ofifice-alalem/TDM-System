<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerSalesController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('sales_invoices')
            ->join('stores', 'sales_invoices.store_id', '=', 'stores.id')
            ->where('sales_invoices.marketer_id', $request->user()->id)
            ->select('sales_invoices.*', 'stores.name as store_name');

        if ($request->has('status')) {
            $query->where('sales_invoices.status', $request->status);
        }

        $invoices = $query->orderBy('sales_invoices.created_at', 'desc')->paginate(20);

        return response()->json(['message' => 'قائمة الفواتير', 'data' => $invoices]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $productDiscount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = DB::table('products')->where('id', $item['product_id'])->first();
                
                if (!$product) {
                    return response()->json(['message' => 'المنتج غير موجود'], 404);
                }

                if (!$product->is_active) {
                    return response()->json(['message' => 'المنتج ' . $product->name . ' غير نشط'], 400);
                }

                $actualStock = DB::table('marketer_actual_stock')
                    ->where('marketer_id', $request->user()->id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                $promotion = DB::table('product_promotions')
                    ->where('product_id', $item['product_id'])
                    ->where('is_active', true)
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->first();

                $freeQuantity = 0;
                $promotionId = null;
                if ($promotion && $item['quantity'] >= $promotion->min_quantity) {
                    $times = floor($item['quantity'] / $promotion->min_quantity);
                    $freeQuantity = $times * $promotion->free_quantity;
                    $promotionId = $promotion->id;
                    $productDiscount += $freeQuantity * $product->current_price;
                }

                $totalQuantity = $item['quantity'] + $freeQuantity;
                if (!$actualStock || $actualStock->quantity < $totalQuantity) {
                    return response()->json(['message' => 'الكمية المطلوبة غير متوفرة في مخزونك'], 400);
                }

                $totalPrice = $item['quantity'] * $product->current_price;
                $subtotal += $totalPrice;

                $itemsData[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'free_quantity' => $freeQuantity,
                    'unit_price' => $product->current_price,
                    'total_price' => $totalPrice,
                    'promotion_id' => $promotionId,
                    'total_quantity' => $totalQuantity
                ];
            }

            $invoiceDiscountType = null;
            $invoiceDiscountValue = null;
            $invoiceDiscountAmount = 0;

            $discount = DB::table('invoice_discount_tiers')
                ->where('min_amount', '<=', $subtotal)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->orderBy('min_amount', 'desc')
                ->first();

            if ($discount) {
                $invoiceDiscountType = $discount->discount_type;
                if ($discount->discount_type === 'percentage') {
                    $invoiceDiscountValue = $discount->discount_percentage;
                    $invoiceDiscountAmount = $subtotal * ($discount->discount_percentage / 100);
                } else {
                    $invoiceDiscountValue = $discount->discount_amount;
                    $invoiceDiscountAmount = $discount->discount_amount;
                }
            }

            $totalAmount = $subtotal - $invoiceDiscountAmount;

            $invoiceNumber = 'SI-' . date('Ymd') . '-' . str_pad(DB::table('sales_invoices')->count() + 1, 4, '0', STR_PAD_LEFT);

            $invoiceId = DB::table('sales_invoices')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'marketer_id' => $request->user()->id,
                'store_id' => $request->store_id,
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'product_discount' => $productDiscount,
                'invoice_discount_type' => $invoiceDiscountType,
                'invoice_discount_value' => $invoiceDiscountValue,
                'invoice_discount_amount' => $invoiceDiscountAmount,
                'status' => 'pending',
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($itemsData as $itemData) {
                DB::table('sales_invoice_items')->insert([
                    'invoice_id' => $invoiceId,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'free_quantity' => $itemData['free_quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['total_price'],
                    'promotion_id' => $itemData['promotion_id']
                ]);

                DB::table('marketer_actual_stock')
                    ->where('marketer_id', $request->user()->id)
                    ->where('product_id', $itemData['product_id'])
                    ->decrement('quantity', $itemData['total_quantity']);

                DB::table('store_pending_stock')->insert([
                    'store_id' => $request->store_id,
                    'sales_invoice_id' => $invoiceId,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['total_quantity'],
                    'created_at' => now()
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'تم إنشاء فاتورة البيع بنجاح', 'data' => ['id' => $invoiceId, 'invoice_number' => $invoiceNumber]], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل إنشاء فاتورة البيع', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $invoice = DB::table('sales_invoices')->where('id', $id)->first();
        
        if (!$invoice) {
            return response()->json(['message' => 'الفاتورة غير موجودة'], 404);
        }

        if ($invoice->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية الوصول لهذه الفاتورة'], 403);
        }

        $invoice = DB::table('sales_invoices')
            ->join('stores', 'sales_invoices.store_id', '=', 'stores.id')
            ->leftJoin('users as keeper', 'sales_invoices.keeper_id', '=', 'keeper.id')
            ->where('sales_invoices.id', $id)
            ->select('sales_invoices.*', 'stores.name as store_name', 'keeper.full_name as keeper_name')
            ->first();

        if ($invoice->stamped_invoice_image) {
            $invoice->stamped_invoice_image = asset('storage/' . $invoice->stamped_invoice_image);
        }

        $items = DB::table('sales_invoice_items')
            ->join('products', 'sales_invoice_items.product_id', '=', 'products.id')
            ->where('sales_invoice_items.invoice_id', $id)
            ->select('sales_invoice_items.*', 'products.name as product_name')
            ->get();

        return response()->json(['message' => 'تفاصيل الفاتورة', 'data' => ['invoice' => $invoice, 'items' => $items]]);
    }

    public function cancel(Request $request, $id)
    {
        $invoice = DB::table('sales_invoices')->where('id', $id)->first();
        
        if (!$invoice) {
            return response()->json(['message' => 'الفاتورة غير موجودة'], 404);
        }

        if ($invoice->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية إلغاء هذه الفاتورة'], 403);
        }

        if ($invoice->status != 'pending') {
            return response()->json(['message' => 'يمكن إلغاء الفواتير في حالة pending فقط'], 400);
        }

        DB::beginTransaction();
        try {

            $items = DB::table('sales_invoice_items')->where('invoice_id', $id)->get();

            foreach ($items as $item) {
                $totalQuantity = $item->quantity + $item->free_quantity;

                DB::table('marketer_actual_stock')
                    ->where('marketer_id', $request->user()->id)
                    ->where('product_id', $item->product_id)
                    ->increment('quantity', $totalQuantity);

                DB::table('store_pending_stock')
                    ->where('sales_invoice_id', $id)
                    ->where('product_id', $item->product_id)
                    ->delete();
            }

            DB::table('sales_invoices')->where('id', $id)->update(['status' => 'cancelled', 'updated_at' => now()]);

            DB::commit();
            return response()->json(['message' => 'تم إلغاء الفاتورة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء الفاتورة'], 500);
        }
    }
    
    public function getRejection(Request $request, $id)
    {
        $invoice = DB::table('sales_invoices')->where('id', $id)->first();

        if (!$invoice) {
            return response()->json(['message' => 'الفاتورة غير موجودة'], 404);
        }

        if ($invoice->marketer_id != $request->user()->id) {
            return response()->json(['message' => 'ليس لديك صلاحية الوصول'], 403);
        }

        $rejection = DB::table('sales_invoice_rejections')
            ->join('users', 'sales_invoice_rejections.rejected_by', '=', 'users.id')
            ->where('sales_invoice_rejections.sales_invoice_id', $id)
            ->select('sales_invoice_rejections.*', 'users.full_name as rejected_by_name')
            ->first();

        if (!$rejection) {
            return response()->json(['message' => 'لا توجد معلومات رفض'], 404);
        }

        return response()->json(['message' => 'معلومات الرفض', 'data' => $rejection]);
    }
}
