<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoreDebtController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/stores', [StoreController::class, 'index']);
    
    // Products Management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
    });
    
    // Stores Management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::post('/stores', [StoreController::class, 'store']);
    });
    
    Route::get('/stores/{id}/debt', [StoreController::class, 'getDebt']);
    
    // Users management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::put('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);
        Route::get('/roles', [UserController::class, 'getRoles']);
    });
    
    Route::get('/warehouse/main-stock', function(\Illuminate\Http\Request $request) {
        $query = \DB::table('main_stock')
            ->select('product_id', 'quantity');
        
        // Filter by product_id
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $stock = $query->get();
        return response()->json(['data' => $stock]);
    });
    
    // Active Invoice Discounts (accessible by all authenticated users)
    Route::get('/discounts/active', function(\Illuminate\Http\Request $request) {
        $query = \DB::table('invoice_discount_tiers')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
        
        // Filter by discount_type
        if ($request->has('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }
        
        $discounts = $query->orderBy('min_amount', 'desc')->get();
        return response()->json(['data' => $discounts]);
    });
    // Store Debts Routes
    Route::get('/stores/debts', [StoreDebtController::class, 'index']);
    Route::get('/stores/debts/{id}', [StoreDebtController::class, 'show']);
    Route::put('/stores/{id}/toggle-active', [StoreDebtController::class, 'toggleActive'])->middleware('role:admin');
});
