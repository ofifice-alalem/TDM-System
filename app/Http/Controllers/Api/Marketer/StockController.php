<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function actual(Request $request)
    {
        $query = DB::table('marketer_actual_stock')
            ->where('marketer_id', $request->user()->id);
        
        // Filter by product_id
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $stock = $query->get();

        return response()->json(['data' => $stock]);
    }

    public function reserved(Request $request)
    {
        $query = DB::table('marketer_reserved_stock')
            ->where('marketer_id', $request->user()->id);
        
        // Filter by product_id
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $stock = $query->get();

        return response()->json(['data' => $stock]);
    }
}
