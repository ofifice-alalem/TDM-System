<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoreDebtController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    
    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{id}/debt', [StoreController::class, 'getDebt']);
    
    // Users management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::put('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);
        Route::get('/roles', [UserController::class, 'getRoles']);
    });
    
    Route::get('/warehouse/main-stock', function() {
        $stock = \DB::table('main_stock')
            ->select('product_id', 'quantity')
            ->get();
        return response()->json(['data' => $stock]);
    });
});

// Store Debts Routes (temporary without auth for testing)
Route::get('/stores/debts', [StoreDebtController::class, 'index']);
Route::get('/stores/debts/{id}', [StoreDebtController::class, 'show']);
Route::put('/stores/{id}/toggle-active', [StoreDebtController::class, 'toggleActive'])->middleware('role:admin');
