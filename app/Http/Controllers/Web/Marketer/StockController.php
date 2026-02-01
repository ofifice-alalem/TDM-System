<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        $token = Auth::user()->createToken('web-token')->plainTextToken;
        return view('marketer.stock', ['token' => $token]);
    }
}
