<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Warehouse API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:warehouse_keeper'])->group(function () {
    
    // Future: Warehouse Operations
    // GET    /api/warehouse/requests
    // PUT    /api/warehouse/requests/{id}/approve
    // POST   /api/warehouse/requests/{id}/document
    
});