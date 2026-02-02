<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.sales.index', ['token' => $token]);
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.sales.create', ['token' => $token]);
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.sales.show', ['token' => $token, 'invoiceId' => $id]);
    }
}
