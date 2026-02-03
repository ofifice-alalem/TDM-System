<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function active()
    {
        $promotions = DB::table('product_promotions')
            ->join('products', 'product_promotions.product_id', '=', 'products.id')
            ->where('product_promotions.is_active', true)
            ->whereDate('product_promotions.start_date', '<=', now())
            ->whereDate('product_promotions.end_date', '>=', now())
            ->select(
                'product_promotions.id',
                'product_promotions.product_id',
                'product_promotions.min_quantity',
                'product_promotions.free_quantity',
                'products.name as product_name'
            )
            ->get();

        return response()->json(['data' => $promotions]);
    }
}
