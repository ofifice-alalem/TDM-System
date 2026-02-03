<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreDebtController extends Controller
{
    public function index()
    {
        $stores = DB::table('stores')
            ->select('stores.*')
            ->get()
            ->map(function($store) {
                $totalSales = DB::table('store_debt_ledger')
                    ->where('store_id', $store->id)
                    ->where('entry_type', 'sale')
                    ->sum('amount');

                $totalPayments = DB::table('store_debt_ledger')
                    ->where('store_id', $store->id)
                    ->where('entry_type', 'payment')
                    ->sum('amount');

                $totalReturns = DB::table('store_debt_ledger')
                    ->where('store_id', $store->id)
                    ->where('entry_type', 'return')
                    ->sum('amount');

                $remaining = $totalSales + $totalPayments + $totalReturns;

                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'owner_name' => $store->owner_name,
                    'phone' => $store->phone,
                    'location' => $store->location,
                    'is_active' => $store->is_active,
                    'total_sales' => $totalSales,
                    'total_payments' => $totalPayments,
                    'total_returns' => $totalReturns,
                    'remaining_debt' => $remaining
                ];
            });

        return response()->json(['message' => 'قائمة المتاجر', 'data' => $stores]);
    }

    public function show($id)
    {
        $store = DB::table('stores')->where('id', $id)->first();
        
        if (!$store) {
            return response()->json(['message' => 'المتجر غير موجود'], 404);
        }

        $totalSales = DB::table('store_debt_ledger')
            ->where('store_id', $id)
            ->where('entry_type', 'sale')
            ->sum('amount');

        $totalPayments = DB::table('store_debt_ledger')
            ->where('store_id', $id)
            ->where('entry_type', 'payment')
            ->sum('amount');

        $totalReturns = DB::table('store_debt_ledger')
            ->where('store_id', $id)
            ->where('entry_type', 'return')
            ->sum('amount');

        $remaining = $totalSales + $totalPayments + $totalReturns;

        $ledger = DB::table('store_debt_ledger')
            ->leftJoin('sales_invoices', 'store_debt_ledger.sales_invoice_id', '=', 'sales_invoices.id')
            ->leftJoin('store_payments', 'store_debt_ledger.payment_id', '=', 'store_payments.id')
            ->leftJoin('sales_returns', 'store_debt_ledger.return_id', '=', 'sales_returns.id')
            ->where('store_debt_ledger.store_id', $id)
            ->select(
                'store_debt_ledger.*',
                'sales_invoices.invoice_number as sale_invoice_number',
                'sales_invoices.marketer_id as sale_marketer_id',
                'store_payments.payment_number as payment_receipt_number',
                'store_payments.marketer_id as payment_marketer_id',
                'sales_returns.return_number as return_number',
                'sales_returns.marketer_id as return_marketer_id'
            )
            ->orderBy('store_debt_ledger.created_at', 'desc')
            ->get()
            ->map(function($item) {
                $marketerId = $item->sale_marketer_id ?? $item->payment_marketer_id ?? $item->return_marketer_id;
                $marketerName = null;
                if ($marketerId) {
                    $marketer = DB::table('users')->where('id', $marketerId)->first();
                    $marketerName = $marketer ? $marketer->full_name : null;
                }
                $item->marketer_name = $marketerName;
                unset($item->sale_marketer_id, $item->payment_marketer_id, $item->return_marketer_id);
                return $item;
            });

        return response()->json([
            'message' => 'تفاصيل المتجر',
            'data' => [
                'store' => $store,
                'summary' => [
                    'total_sales' => $totalSales,
                    'total_payments' => $totalPayments,
                    'total_returns' => $totalReturns,
                    'remaining_debt' => $remaining
                ],
                'ledger' => $ledger
            ]
        ]);
    }

    public function toggleActive(Request $request, $id)
    {
        DB::table('stores')->where('id', $id)->update([
            'is_active' => $request->is_active
        ]);

        return response()->json(['message' => 'تم تحديث حالة المتجر']);
    }
}
