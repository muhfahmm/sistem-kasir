<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;

// Routes Guest (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::resource('products', ProductController::class);

    // Categories CRUD
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    Route::post('/categories/quick-store', [CategoryController::class, 'quickStore'])->name('categories.quick-store');

    // Terminal Kasir (POS)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/barcode/{barcode}', [PosController::class, 'getProductByBarcode'])->name('pos.barcode');
    Route::post('/pos/quick-product', [PosController::class, 'quickStoreProduct'])->name('pos.quick-product');
    Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.checkout');
    Route::get('/pos/receipt/{id}', [PosController::class, 'printReceipt'])->name('pos.receipt');

    // Riwayat Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
});
