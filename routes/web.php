<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/{alias}/{departmentId}/dp', [DepartmentController::class, 'index'])->name('department.index');
Route::get('/{alias}/{productId}/p', [ProductController::class, 'index'])->name('product.show');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    Route::get('/auth/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    Route::get('/auth/recovery', [PasswordResetController::class, 'showRecovery'])->name('auth.recovery');
    Route::post('/auth/recovery', [PasswordResetController::class, 'sendResetLink'])->name('auth.recovery.send');
    
    Route::get('/auth/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('auth.reset');
    Route::post('/auth/reset-password', [PasswordResetController::class, 'reset'])->name('auth.reset.password');
});

Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

// Account routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account/toggle-save', [AccountController::class, 'toggleSaveProduct'])->name('account.toggle-save');
    Route::post('/account/toggle-alert', [AccountController::class, 'togglePriceAlert'])->name('account.toggle-alert');
});