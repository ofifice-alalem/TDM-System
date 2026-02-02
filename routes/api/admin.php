<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\Admin\AdminWithdrawalController;
use App\Http\Controllers\Api\Admin\AdminMarketerController;
use App\Http\Controllers\Api\Admin\AdminSalesController;
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
    
    // Marketers Management
    Route::get('marketers', [AdminMarketerController::class, 'index']);
    Route::put('marketers/{id}/commission-rate', [AdminMarketerController::class, 'updateCommissionRate']);
    
    // Sales Management
    Route::get('sales', [AdminSalesController::class, 'index']);
    Route::get('sales/{id}', [AdminSalesController::class, 'show']);
    Route::get('sales/{id}/rejection', [AdminSalesController::class, 'getRejection']);
    
    // Future: Reports, Users Management, etc.
    
});