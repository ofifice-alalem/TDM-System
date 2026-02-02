<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSalesController extends Controller
{
    public function index()
    {
        $invoices = DB::table('sales_invoices')
            ->join('users', 'sales_invoices.marketer_id', '=', 'users.id')
            ->join('stores', 'sales_invoices.store_id', '=', 'stores.id')
            ->select('sales_invoices.*', 'users.full_name as marketer_name', 'stores.name as store_name')
            ->orderBy('sales_invoices.created_at', 'desc')
            ->get();

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
