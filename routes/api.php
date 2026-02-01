<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes
Route::prefix('auth')->group(base_path('routes/api/auth.php'));

// Admin Routes
Route::prefix('admin')->group(base_path('routes/api/admin.php'));

// Marketer Routes
Route::prefix('marketer')->group(base_path('routes/api/marketer.php'));

// Warehouse Routes
Route::prefix('warehouse')->group(base_path('routes/api/warehouse.php'));