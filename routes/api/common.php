<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoreDebtController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    
    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{id}/debt', [StoreController::class, 'getDebt']);
    
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
