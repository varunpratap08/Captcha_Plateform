<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WithdrawalRequestController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AgentController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.admin');
    Route::get('/user', [AuthController::class, 'getUser'])->middleware('jwt.admin');

    Route::middleware('jwt.admin')->group(function () {
        Route::apiResource('withdrawal-requests', WithdrawalRequestController::class);
        Route::apiResource('subscription-plans', SubscriptionPlanController::class)->only(['index', 'store']);
        Route::apiResource('users', UserController::class)->only(['index']);
        Route::apiResource('agents', AgentController::class)->only(['index', 'store']);
    });
});