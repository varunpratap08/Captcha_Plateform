<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Simple test route to verify basic functionality
Route::get('/test-simple', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Simple test route is working',
        'timestamp' => now()->toDateTimeString(),
        'env' => app()->environment(),
        'debug' => config('app.debug')
    ]);
});

// Direct test endpoint
Route::get('/direct-test', function () {
    return [
        'status' => 'success',
        'message' => 'Direct test endpoint is working',
        'timestamp' => now()->toDateTimeString()
    ];
});

// Simple test route to verify basic functionality
Route::get('/test-simple', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Simple test route is working',
        'timestamp' => now()->toDateTimeString(),
        'env' => app()->environment(),
        'debug' => config('app.debug')
    ]);
});

// Test registration endpoint
Route::post('/test-register', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Test register endpoint is working',
        'data' => [
            'request_data' => request()->all(),
            'headers' => request()->headers->all(),
            'timestamp' => now()->toDateTimeString()
        ]
    ]);
});

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working!',
        'debug' => [
            'route' => request()->path(),
            'method' => request()->method(),
            'headers' => request()->headers->all(),
            'accept' => request()->header('accept'),
            'content_type' => request()->header('content-type')
        ]
    ]);
});

// Test POST route
Route::post('/test-post', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'POST request received',
        'data' => request()->all(),
        'debug' => [
            'route' => request()->path(),
            'method' => request()->method(),
            'headers' => request()->headers->all()
        ]
    ]);
});

// Debug route to list all registered routes
Route::get('/debug-routes', function() {
    return response()->json([
        'routes' => array_map(function($route) {
            return [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'action' => $route->getActionName(),
            ];
        }, \Route::getRoutes()->getRoutes())
    ]);
});

    // API v1 Routes
    Route::prefix('v1')->group(function () {
        // Public routes
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('verify-otp', [RegisterController::class, 'verifyOtp']);
        
        // Debug route
        Route::get('debug', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'Debug route is working',
                'data' => [
                    'request' => [
                        'headers' => request()->headers->all(),
                        'method' => request()->method(),
                        'path' => request()->path(),
                        'url' => request()->url(),
                        'full_url' => request()->fullUrl(),
                        'ip' => request()->ip(),
                        'secure' => request()->secure(),
                        'ajax' => request()->ajax(),
                        'wants_json' => request()->wantsJson(),
                        'accepts_json' => request()->wantsJson(),
                        'accepts_html' => request()->acceptsHtml(),
                        'content_type' => request()->header('content-type'),
                        'accept' => request()->header('accept'),
                    ],
                    'app' => [
                        'env' => app()->environment(),
                        'debug' => config('app.debug'),
                    ]
                ]
            ]);
        });
        
        // Protected routes (require authentication)
        Route::middleware('auth:sanctum')->group(function () {
            // User profile
            Route::prefix('profile')->group(function () {
                Route::get('/', [ProfileController::class, 'getProfile']);
                Route::post('/complete', [ProfileController::class, 'completeProfile']);
            });
            
            // Logout
            Route::post('logout', [AuthController::class, 'logout']);
            
            // Admin routes (require admin role)
            Route::middleware(['role:admin'])->group(function () {
                Route::get('user', [AuthController::class, 'getUser']);
                Route::apiResource('withdrawal-requests', WithdrawalRequestController::class);
                Route::apiResource('subscription-plans', SubscriptionPlanController::class)->only(['index', 'store']);
                Route::apiResource('users', UserController::class)->only(['index']);
                Route::apiResource('agents', AgentController::class)->only(['index', 'store']);
            });
        });
    });