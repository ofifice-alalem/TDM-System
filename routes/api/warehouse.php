<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Warehouse\WarehouseRequestController;

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
    Route::post('requests/{id}/document', [WarehouseRequestController::class, 'document']);
    
});