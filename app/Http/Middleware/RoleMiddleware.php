<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'غير مصرح'], 401);
        }

        $user = Auth::user();
        $userRole = $user->role->name ?? null;

        if ($userRole !== $role) {
            return response()->json(['message' => 'ليس لديك صلاحية للوصول'], 403);
        }

        return $next($request);
    }
}