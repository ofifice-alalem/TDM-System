<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Marketer\MarketerRequestController;

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
    
});