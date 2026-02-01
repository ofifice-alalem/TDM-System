<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Marketer\MarketerRequestController;
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
    // │ Marketer Stock API - مخزون المسوق                        │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('stock/actual', [StockController::class, 'actual']);
    Route::get('stock/reserved', [StockController::class, 'reserved']);
    
});