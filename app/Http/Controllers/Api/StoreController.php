<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::query();
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        } else {
            $query->where('is_active', true);
        }
        
        $stores = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
            'address' => 'nullable|string',
        ]);

        $store = Store::create($validated);
        return response()->json($store, 201);
    }

    public function getDebt($id)
    {
        $debt = DB::table('store_debt_ledger')
            ->where('store_id', $id)
            ->sum('amount');

        return response()->json(['debt' => $debt]);
    }
}
