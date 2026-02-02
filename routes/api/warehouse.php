<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Warehouse\WarehouseRequestController;
use App\Http\Controllers\Api\Warehouse\WarehouseReturnController;
use App\Http\Controllers\Api\Warehouse\WarehouseSalesController;

/*
|--------------------------------------------------------------------------
| Warehouse API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:warehouse_keeper'])->group(function () {
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Warehouse Requests API - إدارة طلبات المسوقين              │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('requests', [WarehouseRequestController::class, 'index']);
    Route::get('requests/{id}', [WarehouseRequestController::class, 'show']);
    Route::put('requests/{id}/approve', [WarehouseRequestController::class, 'approve']);
    Route::put('requests/{id}/reject', [WarehouseRequestController::class, 'reject']);
    Route::put('requests/{id}/cancel', [WarehouseRequestController::class, 'cancel']);
    Route::post('requests/{id}/document', [WarehouseRequestController::class, 'document']);
    
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Warehouse Returns API - إدارة إرجاع البضاعة              │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('returns', [WarehouseReturnController::class, 'index']);
    Route::get('returns/{id}', [WarehouseReturnController::class, 'show']);
    Route::put('returns/{id}/approve', [WarehouseReturnController::class, 'approve']);
    Route::put('returns/{id}/reject', [WarehouseReturnController::class, 'reject']);
    Route::post('returns/{id}/document', [WarehouseReturnController::class, 'document']);
    // ┌─────────────────────────────────────────────────────────────────┐
    // │ Warehouse Sales API - إدارة فواتير البيع              │
    // └─────────────────────────────────────────────────────────────────┘
    Route::get('sales', [WarehouseSalesController::class, 'index']);
    Route::get('sales/{id}', [WarehouseSalesController::class, 'show']);
    Route::get('sales/{id}/rejection', [WarehouseSalesController::class, 'getRejection']);
    Route::post('sales/{id}/approve', [WarehouseSalesController::class, 'approve']);
    Route::put('sales/{id}/reject', [WarehouseSalesController::class, 'reject']);
    
});