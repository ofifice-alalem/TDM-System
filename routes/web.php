<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Marketer Routes
Route::prefix('marketer')->group(function () {
    Route::get('/requests', fn() => view('marketer.requests.index'));
    Route::get('/requests/create', fn() => view('marketer.requests.create'));
    Route::get('/requests/{id}', fn() => view('marketer.requests.show'));
});

// Warehouse Routes
Route::prefix('warehouse')->group(function () {
    Route::get('/requests', fn() => view('warehouse.requests.index'));
    Route::get('/requests/{id}', fn() => view('warehouse.requests.show'));
});