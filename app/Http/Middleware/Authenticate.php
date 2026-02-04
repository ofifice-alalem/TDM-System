<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return null;
    }

    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'message' => 'غير مصرح. يرجى تسجيل الدخول أولاً',
            'error' => 'Unauthenticated'
        ], 401));
    }
}
