<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketerController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('admin.marketers.index', compact('token'));
    }
}
