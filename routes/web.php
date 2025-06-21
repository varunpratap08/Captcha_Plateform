<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;

Route::middleware(['jwt.admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('withdrawal-requests', WithdrawalRequestController::class);
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('users', UserController::class);
    Route::resource('agents', AgentController::class);
});