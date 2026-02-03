<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\SalesReturn;
use Illuminate\Http\Request;

class StoreReturnController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('warehouse.store-returns.index', compact('token'));
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('warehouse.store-returns.show', compact('id', 'token'));
    }
}
