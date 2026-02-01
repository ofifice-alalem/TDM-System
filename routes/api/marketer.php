<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Marketer API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:salesman'])->group(function () {
    
    // Future: Marketer Requests, Sales, Returns, etc.
    // GET    /api/marketer/requests
    // POST   /api/marketer/requests
    // GET    /api/marketer/sales
    // POST   /api/marketer/sales
    
});