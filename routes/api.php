<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// مسار البحث والفلترة (أضفه هنا)
Route::get('products/search', [ProductController::class, 'search']);

// مسارات التصنيفات (Categories API)
Route::apiResource('categories', CategoryController::class);

// مسارات المنتجات/القهوة (Products API)
Route::apiResource('products', ProductController::class);