<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use App\Models\SalesReturn;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;

class StoreReturnController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.store-returns.index', compact('token'));
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.store-returns.create', compact('token'));
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.store-returns.show', compact('id', 'token'));
    }
}
