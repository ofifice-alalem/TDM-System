<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductPromotionController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('admin.promotions.index', compact('token'));
    }

    public function create(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('admin.promotions.create', compact('token'));
    }
}
