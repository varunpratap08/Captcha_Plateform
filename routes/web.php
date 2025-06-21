<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout route (must be outside auth middleware to prevent redirect loop)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected admin routes
Route::middleware(['auth.jwt', 'jwt.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('withdrawal-requests', WithdrawalRequestController::class);
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('users', UserController::class);
    Route::resource('agents', AgentController::class);
});

// Redirect root to login page
Route::redirect('/', '/login');