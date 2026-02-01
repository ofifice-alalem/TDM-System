<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Marketer\RequestController;

/*
|--------------------------------------------------------------------------
| Marketer API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:salesman'])->group(function () {
    
    // Marketer Requests
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::get('/requests/{id}', [RequestController::class, 'show']);
    Route::put('/requests/{id}/cancel', [RequestController::class, 'cancel']);
    
});