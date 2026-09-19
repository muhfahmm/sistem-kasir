<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Products CRUD
Route::resource('products', ProductController::class);

// Terminal Kasir (POS)
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::get('/pos/barcode/{barcode}', [PosController::class, 'getProductByBarcode'])->name('pos.barcode');
Route::post('/pos/quick-product', [PosController::class, 'quickStoreProduct'])->name('pos.quick-product');
Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.checkout');
Route::get('/pos/receipt/{id}', [PosController::class, 'printReceipt'])->name('pos.receipt');

// Riwayat Transaksi
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
