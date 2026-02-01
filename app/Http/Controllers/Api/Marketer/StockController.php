<?php

namespace App\Http\Controllers\Api\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function actual(Request $request)
    {
        $stock = DB::table('marketer_actual_stock')
            ->where('marketer_id', $request->user()->id)
            ->get();

        return response()->json(['data' => $stock]);
    }

    public function reserved(Request $request)
    {
        $stock = DB::table('marketer_reserved_stock')
            ->where('marketer_id', $request->user()->id)
            ->get();

        return response()->json(['data' => $stock]);
    }
}
