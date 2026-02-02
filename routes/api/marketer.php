<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Marketer\MarketerRequestController;
use App\Http\Controllers\Api\Marketer\MarketerReturnController;
use App\Http\Controllers\Api\Marketer\MarketerSalesController;
use App\Http\Controllers\Api\Marketer\MarketerPaymentController;
use App\Http\Controllers\Api\Marketer\StockController;

/*
|--------------------------------------------------------------------------
| Marketer API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:salesman'])->group(function () {
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Marketer Requests API - طلبات البضاعة من المسوق                │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('requests', [MarketerRequestController::class, 'index']);
    Route::post('requests', [MarketerRequestController::class, 'store']);
    Route::get('requests/{id}', [MarketerRequestController::class, 'show']);
    Route::put('requests/{id}/cancel', [MarketerRequestController::class, 'cancel']);
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Marketer Returns API - إرجاع البضاعة من المسوق              │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('returns', [MarketerReturnController::class, 'index']);
    Route::post('returns', [MarketerReturnController::class, 'store']);
    Route::get('returns/{id}', [MarketerReturnController::class, 'show']);
    Route::put('returns/{id}/cancel', [MarketerReturnController::class, 'cancel']);
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Marketer Sales API - فواتير البيع من المسوق              │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('sales', [MarketerSalesController::class, 'index']);
    Route::post('sales', [MarketerSalesController::class, 'store']);
    Route::get('sales/{id}', [MarketerSalesController::class, 'show']);
    Route::get('sales/{id}/rejection', [MarketerSalesController::class, 'getRejection']);
    Route::put('sales/{id}/cancel', [MarketerSalesController::class, 'cancel']);
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Marketer Payments API - إيصالات القبض من المسوق              │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('payments', [MarketerPaymentController::class, 'index']);
    Route::post('payments', [MarketerPaymentController::class, 'store']);
    Route::get('payments/{id}', [MarketerPaymentController::class, 'show']);
    Route::put('payments/{id}/cancel', [MarketerPaymentController::class, 'cancel']);
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Marketer Stock API - مخزون المسوق                        │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('stock/actual', [StockController::class, 'actual']);
    Route::get('stock/reserved', [StockController::class, 'reserved']);
    
});