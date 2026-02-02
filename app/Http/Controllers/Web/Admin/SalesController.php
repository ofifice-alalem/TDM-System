<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('admin.sales.index', compact('token'));
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('admin.sales.show', compact('token', 'id'));
    }
}
