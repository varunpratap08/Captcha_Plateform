<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

// Clean test endpoint for CORS and JSON response
Route::get('/test-clean', function (Request $request): JsonResponse {
    return new JsonResponse([
        'status' => 'success',
        'message' => 'Clean test endpoint',
        'timestamp' => now()->toDateTimeString(),
        'headers' => [
            'accept' => $request->header('accept'),
            'content_type' => $request->header('content-type'),
        ]
    ]);
});

// Test endpoint to verify API is working with JSON response
Route::get('/test-json', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'JSON test endpoint',
        'timestamp' => now()->toDateTimeString(),
        'is_api' => $request->is('api/*'),
        'middleware' => $request->route() ? $request->route()->gatherMiddleware() : [],
        'accept_header' => $request->header('Accept'),
        'content_type' => $request->header('Content-Type'),
    ]);
});

// Simple test endpoint
Route::get('/test-simple', function () {
    return [
        'status' => 'success',
        'message' => 'Simple test endpoint',
        'timestamp' => now()->toDateTimeString(),
    ];
});

// API v1 Routes
Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('send-otp', [\App\Http\Controllers\Api\Auth\OtpController::class, 'sendOtp']);
    Route::post('verify-otp', [\App\Http\Controllers\Api\Auth\OtpController::class, 'verifyOtp']);
    Route::post('register', [RegisterController::class, 'register']);
    
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
    
    // Protected routes (require JWT authentication)
    Route::middleware('auth:api')->group(function () {
        // Profile routes
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

    