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
Route::get('/register', [App\Http\Controllers\Web\AuthController::class, 'showRegister'])->name('register');

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Dashboard placeholder
Route::get('/dashboard', function () {
    return '<h1>Dashboard - قيد التطوير</h1><a href="/login">تسجيل الخروج</a>';
})->name('dashboard');