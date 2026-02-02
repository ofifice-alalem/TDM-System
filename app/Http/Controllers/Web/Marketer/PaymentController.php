<?php

namespace App\Http\Controllers\Web\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.payments.index', ['token' => $token]);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $token = $user->createToken('web-token')->plainTextToken;
        return view('marketer.payments.create', [
            'token' => $token,
            'commission_rate' => $user->commission_rate
        ]);
    }

    public function show(Request $request, $id)
    {
        $token = $request->user()->createToken('web-token')->plainTextToken;
        return view('marketer.payments.show', ['token' => $token, 'paymentId' => $id]);
    }
}
