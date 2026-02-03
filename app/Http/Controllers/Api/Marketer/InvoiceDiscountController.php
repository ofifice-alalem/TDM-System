<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InvoiceDiscountController extends Controller
{
    public function active()
    {
        $discounts = DB::table('invoice_discount_tiers')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('min_amount', 'asc')
            ->get();

        return response()->json(['data' => $discounts]);
    }
}
