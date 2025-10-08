<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DepartamentController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/{alias}/{departamentId}/dp', [DepartamentController::class, 'index'])->name('departament.index');
Route::get('/{alias}/{productId}/p', [ProductController::class, 'index'])->name('product.index');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');