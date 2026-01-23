<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('category', CategoryController::class);
Route::get('/get-category', [CategoryController::class, 'getCategories'])->name('getcategories');
Route::resource('product', ProductController::class);
