<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StoreController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/{alias}/{departmentId}/dp', [DepartmentController::class, 'index'])->name('department.index');
Route::get('/{id}/{slug}/p', [ProductController::class, 'index'])->name('product.show');
Route::get('/share/whatsapp/{id}', [ProductController::class, 'shareWhatsapp'])->name('product.share.whatsapp');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::get('/lojas', [StoreController::class, 'index'])->name('stores.index');
Route::get('/{slug}/{id}/loja', [StoreController::class, 'show'])->name('store.show');
