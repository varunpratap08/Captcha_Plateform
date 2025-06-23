<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Test route to verify routing is working
Route::get('/test-route', function () {
    return response()->json(['message' => 'Test route is working!']);
});

// Test API route in web.php
Route::prefix('api')->group(function () {
    Route::get('/test-api', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API route is working!',
            'data' => [
                'time' => now()->toDateTimeString(),
                'env' => app()->environment(),
            ]
        ]);
    });

    // Temporary register route for testing
    Route::post('/register', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'message' => 'Register endpoint is working!',
            'data' => $request->all()
        ], 200);
    });
});

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('withdrawal-requests', WithdrawalRequestController::class);
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('users', UserController::class);
    Route::resource('agents', AgentController::class);
});

// Home route
Route::get('/home', function () {
    return view('home');
})->name('home');

// Redirect root to login page
Route::redirect('/', '/login');