<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseSalesController extends Controller
{
    public function index(Request $request)
    {
        $invoices = DB::table('sales_invoices')
            ->join('users', 'sales_invoices.marketer_id', '=', 'users.id')
            ->join('stores', 'sales_invoices.store_id', '=', 'stores.id')
            ->select('sales_invoices.*', 'users.full_name as marketer_name', 'stores.name as store_name');

        // Filter by status
        if ($request->has('status')) {
            $invoices->where('sales_invoices.status', $request->status);
        }

        // Filter by marketer
        if ($request->has('marketer_id')) {
            $invoices->where('sales_invoices.marketer_id', $request->marketer_id);
        }

        // Filter by store
        if ($request->has('store_id')) {
            $invoices->where('sales_invoices.store_id', $request->store_id);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $invoices->whereDate('sales_invoices.created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $invoices->whereDate('sales_invoices.created_at', '<=', $request->to_date);
        }

        $invoices = $invoices->orderBy('sales_invoices.created_at', 'desc')->paginate(20);

        return response()->json(['message' => 'قائمة فواتير البيع', 'data' => $invoices]);
    }

    public function show($id)
    {
        $invoice = DB::table('sales_invoices')
            ->join('users', 'sales_invoices.marketer_id', '=', 'users.id')
            ->join('stores', 'sales_invoices.store_id', '=', 'stores.id')
            ->leftJoin('users as keeper', 'sales_invoices.keeper_id', '=', 'keeper.id')
            ->where('sales_invoices.id', $id)
            ->select('sales_invoices.*', 'users.full_name as marketer_name', 'stores.name as store_name', 'keeper.full_name as keeper_name')
            ->first();

        if (!$invoice) {
            return response()->json(['message' => 'الفاتورة غير موجودة'], 404);
        }

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

    public function approve(Request $request, $id)
    {
        $request->validate(['stamped_invoice_image' => 'required|image|max:10240']);

        DB::beginTransaction();
        try {
            $invoice = DB::table('sales_invoices')->where('id', $id)->where('status', 'pending')->first();

            if (!$invoice) {
                return response()->json(['message' => 'الفاتورة غير موجودة أو تم معالجتها مسبقاً'], 404);
            }

            $imagePath = null;
            if ($request->hasFile('stamped_invoice_image')) {
                $directory = "stamped_invoices/{$invoice->invoice_number}";
                $imagePath = $request->file('stamped_invoice_image')->store($directory, 'public');
            }

            $items = DB::table('sales_invoice_items')->where('invoice_id', $id)->get();

            foreach ($items as $item) {
                $totalQuantity = $item->quantity + $item->free_quantity;

                DB::table('store_pending_stock')
                    ->where('sales_invoice_id', $id)
                    ->where('product_id', $item->product_id)
                    ->delete();

                $existingStock = DB::table('store_actual_stock')
                    ->where('store_id', $invoice->store_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($existingStock) {
                    DB::table('store_actual_stock')
                        ->where('store_id', $invoice->store_id)
                        ->where('product_id', $item->product_id)
                        ->increment('quantity', $totalQuantity);
                } else {
                    DB::table('store_actual_stock')->insert([
                        'store_id' => $invoice->store_id,
                        'product_id' => $item->product_id,
                        'quantity' => $totalQuantity
                    ]);
                }
            }

            DB::table('store_debt_ledger')->insert([
                'store_id' => $invoice->store_id,
                'entry_type' => 'sale',
                'sales_invoice_id' => $id,
                'return_id' => null,
                'payment_id' => null,
                'amount' => $invoice->total_amount,
                'created_at' => now()
            ]);

            DB::table('sales_invoices')->where('id', $id)->update([
                'status' => 'approved',
                'keeper_id' => auth()->id(),
                'stamped_invoice_image' => $imagePath,
                'confirmed_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم توثيق الفاتورة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء التوثيق', 'error' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string|max:1000']);

        DB::beginTransaction();
        try {
            $invoice = DB::table('sales_invoices')->where('id', $id)->where('status', 'pending')->first();

            if (!$invoice) {
                return response()->json(['message' => 'الفاتورة غير موجودة أو تم معالجتها مسبقاً'], 404);
            }

            $items = DB::table('sales_invoice_items')->where('invoice_id', $id)->get();

            foreach ($items as $item) {
                $totalQuantity = $item->quantity + $item->free_quantity;

                DB::table('store_pending_stock')
                    ->where('sales_invoice_id', $id)
                    ->where('product_id', $item->product_id)
                    ->delete();

                DB::table('marketer_actual_stock')
                    ->where('marketer_id', $invoice->marketer_id)
                    ->where('product_id', $item->product_id)
                    ->increment('quantity', $totalQuantity);
            }

            DB::table('sales_invoice_rejections')->insert([
                'sales_invoice_id' => $id,
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->notes,
                'rejected_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('sales_invoices')->where('id', $id)->update([
                'status' => 'rejected',
                'keeper_id' => auth()->id(),
                'notes' => $request->notes,
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'تم رفض الفاتورة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'حدث خطأ أثناء الرفض', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function getRejection($id)
    {
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
