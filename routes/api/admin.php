<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
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
    
    // Future: Reports, Users Management, etc.
    
});