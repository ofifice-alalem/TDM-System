<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', 'users.username', 'users.full_name', 'users.is_active', 'roles.display_name as role_name', 'users.commission_rate')
            ->get();
        
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'full_name' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::table('users')->insert([
            'username' => $validated['username'],
            'full_name' => $validated['full_name'],
            'password_hash' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'commission_rate' => $validated['commission_rate'] ?? 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json(['message' => 'تم إضافة المستخدم بنجاح'], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'full_name' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $updateData = [
            'username' => $validated['username'],
            'full_name' => $validated['full_name'],
            'role_id' => $validated['role_id'],
            'commission_rate' => $validated['commission_rate'] ?? 0,
            'updated_at' => now()
        ];

        if (!empty($validated['password'])) {
            $updateData['password_hash'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);
        
        return response()->json(['message' => 'تم تحديث المستخدم بنجاح']);
    }

    public function toggleActive($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        DB::table('users')->where('id', $id)->update([
            'is_active' => !$user->is_active,
            'updated_at' => now()
        ]);
        
        return response()->json(['message' => 'تم تحديث حالة المستخدم']);
    }

    public function getRoles()
    {
        $roles = DB::table('roles')->select('id', 'display_name')->get();
        return response()->json(['data' => $roles]);
    }
}