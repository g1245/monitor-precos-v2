<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/{alias}/{departmentId}/dp', [DepartmentController::class, 'index'])->name('department.index');
Route::get('/{alias}/{productId}/p', [ProductController::class, 'index'])->name('product.show');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
