<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\Admin\AdminWithdrawalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    
    // Products Management
    Route::apiResource('products', ProductController::class);
    
    // Stores Management  
    Route::apiResource('stores', StoreController::class);
    
    // Withdrawals Management
    Route::get('withdrawals', [AdminWithdrawalController::class, 'index']);
    Route::get('withdrawals/{id}', [AdminWithdrawalController::class, 'show']);
    Route::post('withdrawals/{id}/approve', [AdminWithdrawalController::class, 'approve']);
    Route::post('withdrawals/{id}/reject', [AdminWithdrawalController::class, 'reject']);
    
    // Future: Reports, Users Management, etc.
    
});