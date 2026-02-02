<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/login', [App\Http\Controllers\Web\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\Web\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Web\AuthController::class, 'logout'])->name('logout');
Route::get('/register', [App\Http\Controllers\Web\AuthController::class, 'showRegister'])->name('register');

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Dashboard placeholder
Route::get('/dashboard', function () {
    $token = request()->user() ? request()->user()->createToken('web-token')->plainTextToken : null;
    return view('dashboard', ['token' => $token]);
})->middleware('auth')->name('dashboard');

// Marketer Routes
Route::middleware(['auth'])->prefix('marketer')->group(function () {
    Route::get('/stock', [App\Http\Controllers\Web\Marketer\StockController::class, 'index']);
    Route::get('/requests', [App\Http\Controllers\Web\Marketer\RequestController::class, 'index']);
    Route::get('/requests/create', [App\Http\Controllers\Web\Marketer\RequestController::class, 'create']);
    Route::get('/requests/{id}', [App\Http\Controllers\Web\Marketer\RequestController::class, 'show']);
    Route::get('/requests/{id}/print', [App\Http\Controllers\Web\Marketer\RequestController::class, 'print'])->name('marketer.requests.print');
});

// Warehouse Routes
Route::middleware(['auth'])->prefix('warehouse')->group(function () {
    Route::get('/requests', [App\Http\Controllers\Web\Warehouse\RequestController::class, 'index']);
    Route::get('/requests/{id}', [App\Http\Controllers\Web\Warehouse\RequestController::class, 'show']);
});