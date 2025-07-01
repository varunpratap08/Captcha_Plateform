<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;

// Admin Panel Routes

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('withdrawal-requests', WithdrawalRequestController::class);
        Route::resource('subscription_plans', SubscriptionPlanController::class);
        Route::resource('agent-plans', \App\Http\Controllers\Admin\AgentPlanController::class);
        Route::resource('users', UserController::class);
        Route::resource('agents', AgentController::class);
        Route::get('/all-withdrawal-requests', [AdminController::class, 'allWithdrawalRequests'])->name('all-withdrawal-requests');
        Route::post('agent-withdrawal-requests/{id}/approve', [\App\Http\Controllers\Admin\AgentWithdrawalRequestController::class, 'approve'])->name('agent-withdrawal-requests.approve');
        Route::post('agent-withdrawal-requests/{id}/decline', [\App\Http\Controllers\Admin\AgentWithdrawalRequestController::class, 'decline'])->name('agent-withdrawal-requests.decline');
        Route::get('subscription_plans', [SubscriptionPlanController::class, 'index'])->name('subscription_plans.index');
        Route::get('subscription_plans/{subscription_plan}', [SubscriptionPlanController::class, 'show'])->name('subscription_plans.show');
        Route::get('subscription_plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription_plans.create');
        Route::get('subscription_plans/{subscription_plan}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription_plans.edit');
        Route::delete('subscription_plans/{subscription_plan}', [SubscriptionPlanController::class, 'destroy'])->name('subscription_plans.destroy');
    }); 