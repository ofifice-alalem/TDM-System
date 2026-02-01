<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Warehouse\RequestController;

/*
|--------------------------------------------------------------------------
| Warehouse API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:warehouse_keeper'])->group(function () {
    
    // Warehouse Operations - Marketer Requests
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/requests/{id}', [RequestController::class, 'show']);
    Route::put('/requests/{id}/approve', [RequestController::class, 'approve']);
    Route::put('/requests/{id}/reject', [RequestController::class, 'reject']);
    Route::post('/requests/{id}/document', [RequestController::class, 'document']);
    
});