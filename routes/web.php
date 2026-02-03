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
    Route::get('/requests/{id}/documentation', [App\Http\Controllers\Web\Marketer\RequestController::class, 'documentation'])->name('marketer.requests.documentation');
    
    Route::get('/returns', [App\Http\Controllers\Web\Marketer\ReturnController::class, 'index']);
    Route::get('/returns/create', [App\Http\Controllers\Web\Marketer\ReturnController::class, 'create']);
    Route::get('/returns/{id}', [App\Http\Controllers\Web\Marketer\ReturnController::class, 'show']);
    
    Route::get('/sales', [App\Http\Controllers\Web\Marketer\SalesController::class, 'index']);
    Route::get('/sales/create', [App\Http\Controllers\Web\Marketer\SalesController::class, 'create']);
    Route::get('/sales/{id}', [App\Http\Controllers\Web\Marketer\SalesController::class, 'show']);
    Route::get('/sales/{id}/print', [App\Http\Controllers\Web\Marketer\SalesController::class, 'print'])->name('marketer.sales.print');
    
    Route::get('/payments', [App\Http\Controllers\Web\Marketer\PaymentController::class, 'index']);
    Route::get('/payments/create', [App\Http\Controllers\Web\Marketer\PaymentController::class, 'create']);
    Route::get('/payments/{id}', [App\Http\Controllers\Web\Marketer\PaymentController::class, 'show']);
    
    Route::get('/withdrawals', [App\Http\Controllers\Web\Marketer\WithdrawalController::class, 'index'])->name('marketer.withdrawals.index');
    Route::get('/withdrawals/create', [App\Http\Controllers\Web\Marketer\WithdrawalController::class, 'create'])->name('marketer.withdrawals.create');
    Route::get('/withdrawals/{id}', [App\Http\Controllers\Web\Marketer\WithdrawalController::class, 'show'])->name('marketer.withdrawals.show');
    
    Route::get('/store-returns', [App\Http\Controllers\Web\Marketer\StoreReturnController::class, 'index'])->name('marketer.store-returns.index');
    Route::get('/store-returns/create', [App\Http\Controllers\Web\Marketer\StoreReturnController::class, 'create'])->name('marketer.store-returns.create');
    Route::get('/store-returns/{id}', [App\Http\Controllers\Web\Marketer\StoreReturnController::class, 'show'])->name('marketer.store-returns.show');
});

// Warehouse Routes
Route::middleware(['auth'])->prefix('warehouse')->group(function () {
    Route::get('/requests', [App\Http\Controllers\Web\Warehouse\RequestController::class, 'index']);
    Route::get('/requests/{id}', [App\Http\Controllers\Web\Warehouse\RequestController::class, 'show']);
    
    Route::get('/returns', [App\Http\Controllers\Web\Warehouse\ReturnController::class, 'index']);
    Route::get('/returns/{id}', [App\Http\Controllers\Web\Warehouse\ReturnController::class, 'show']);
    
    Route::get('/sales', [App\Http\Controllers\Web\Warehouse\SalesController::class, 'index']);
    Route::get('/sales/{id}', [App\Http\Controllers\Web\Warehouse\SalesController::class, 'show']);
    
    Route::get('/payments', [App\Http\Controllers\Web\Warehouse\PaymentController::class, 'index']);
    Route::get('/payments/{id}', [App\Http\Controllers\Web\Warehouse\PaymentController::class, 'show']);
    
    Route::get('/store-returns', [App\Http\Controllers\Web\Warehouse\StoreReturnController::class, 'index'])->name('warehouse.store-returns.index');
    Route::get('/store-returns/{id}', [App\Http\Controllers\Web\Warehouse\StoreReturnController::class, 'show'])->name('warehouse.store-returns.show');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/withdrawals', [App\Http\Controllers\Web\Admin\WithdrawalController::class, 'index'])->name('admin.withdrawals.index');
    Route::get('/withdrawals/{id}', [App\Http\Controllers\Web\Admin\WithdrawalController::class, 'show'])->name('admin.withdrawals.show');
    Route::get('/marketers', [App\Http\Controllers\Web\Admin\MarketerController::class, 'index'])->name('admin.marketers.index');
    Route::get('/sales', [App\Http\Controllers\Web\Admin\SalesController::class, 'index'])->name('admin.sales.index');
    Route::get('/sales/{id}', [App\Http\Controllers\Web\Admin\SalesController::class, 'show'])->name('admin.sales.show');
    Route::get('/products', function() {
        $token = request()->user()->createToken('web-token')->plainTextToken;
        return view('admin.products', ['token' => $token]);
    })->name('admin.products');
    Route::get('/users', function() {
        $token = request()->user()->createToken('web-token')->plainTextToken;
        return view('admin.users', ['token' => $token]);
    })->name('admin.users');
    Route::get('/discounts', [App\Http\Controllers\Web\Admin\InvoiceDiscountController::class, 'index'])->name('admin.discounts.index');
    Route::get('/discounts/create', [App\Http\Controllers\Web\Admin\InvoiceDiscountController::class, 'create'])->name('admin.discounts.create');
    Route::get('/promotions', [App\Http\Controllers\Web\Admin\ProductPromotionController::class, 'index'])->name('admin.promotions.index');
    Route::get('/promotions/create', [App\Http\Controllers\Web\Admin\ProductPromotionController::class, 'create'])->name('admin.promotions.create');
});

// Stores Routes (Shared for all users)
Route::middleware(['auth'])->group(function () {
    Route::get('/stores', [App\Http\Controllers\Web\StoreDebtController::class, 'index'])->name('stores.index');
    Route::get('/stores/{id}', [App\Http\Controllers\Web\StoreDebtController::class, 'show'])->name('stores.show');
});