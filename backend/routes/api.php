<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
