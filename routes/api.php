<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Test Routes - For API response testing
|--------------------------------------------------------------------------
*/

// Test JSON response
Route::get('/test-json', function () {
    return [
        'message' => 'This is a test JSON response',
        'timestamp' => now()->toDateTimeString(),
        'success' => true
    ];
});

// Test HTML response (should be converted to JSON)
Route::get('/test-html', function () {
    return "<h1>This is an HTML response</h1>";
});

// Test error response
Route::get('/test-error', function () {
    return response()->json([
        'error' => 'Test error message',
        'code' => 400
    ], 400);
});

// Test user data response
Route::get('/test-user', function (Request $request) {
    return [
        'user' => [
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ],
        'auth' => [
            'authenticated' => $request->user() ? true : false,
            'token' => $request->bearerToken() ? '***' . substr($request->bearerToken(), -8) : null
        ]
    ];
});

/*
|--------------------------------------------------------------------------
| Test Login Route - For debugging only
|--------------------------------------------------------------------------
*/
Route::get('/test-login', function () {
    $phone = '9457508075'; // Test with the user's phone number
    $otp = '123456'; // Test OTP - should match what's in the database
    
    try {
        Log::info('Test login endpoint called', ['phone' => $phone]);
        
        // 1. Check if user exists
        $user = User::where('phone', $phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'user_exists' => false
            ]);
        }
        
        // 2. Check if OTP is set
        if (empty($user->otp)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No OTP set for user',
                'user_exists' => true,
                'has_otp' => false,
                'user' => [
                    'id' => $user->id,
                    'phone' => $user->phone,
                    'otp' => $user->otp,
                    'otp_expires_at' => $user->otp_expires_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]);
        }
        
        // 3. Verify OTP hash
        $otpMatches = Hash::check($otp, $user->otp);
        
        // 4. Check OTP expiration
        $isExpired = $user->otp_expires_at && now()->gt($user->otp_expires_at);
        
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'has_otp' => !empty($user->otp),
                'otp_expires_at' => $user->otp_expires_at,
                'is_expired' => $isExpired,
                'otp_matches' => $otpMatches
            ],
            'debug' => [
                'otp_stored' => $user->otp,
                'otp_provided' => $otp,
                'otp_matches' => $otpMatches,
                'current_time' => now(),
                'is_expired' => $isExpired
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('Test login error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Test failed',
            'error' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode()
            ]
        ], 500);
    }
});

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

// Test database connection
Route::get('/test-db', function() {
    try {
        // Test database connection
        DB::connection()->getPdo();
        
        // Test users table
        $user = \App\Models\User::first();
        
        return response()->json([
            'status' => 'success',
            'database' => 'Connected successfully',
            'users_table' => $user ? 'Has users' : 'No users found',
            'time' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

// Test OTP sending
Route::post('/test-send-otp', function(\Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9]{10}$/'
        ]);
        
        $phone = $request->phone;
        $user = \App\Models\User::where('phone', $phone)->first();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found with this phone number'
            ], 404);
        }
        
        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp = \Illuminate\Support\Facades\Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully',
            'data' => [
                'phone' => $user->phone,
                'otp' => $otp, // Only for testing
                'otp_expires_at' => $user->otp_expires_at
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
});

// API v1 Routes
Route::prefix('v1')->group(function () {
    // Public routes - Authentication
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
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
            Route::patch('/', [ProfileController::class, 'updateProfile']);
        });
        
        // Logout
        Route::post('logout', [AuthController::class, 'logout']);

        // New route for purchasing a plan (must be before admin group)
        Route::post('plans/purchase', [\App\Http\Controllers\Api\SubscriptionPlanController::class, 'purchase']);
        
        // Withdrawal requests (user create, admin list/approve/decline)
        Route::post('withdrawal-requests', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'store']); // user create
        Route::get('withdrawal-requests', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'index']); // admin list
        Route::post('withdrawal-requests/{id}/approve', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'approve']); // admin approve
        Route::post('withdrawal-requests/{id}/decline', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'decline']); // admin decline

        // Captcha solve routes
        Route::post('captcha/solve', [\App\Http\Controllers\Api\CaptchaSolveController::class, 'solveCaptcha']);
        Route::get('captcha/level', [\App\Http\Controllers\Api\CaptchaSolveController::class, 'getLevel']);
        Route::get('captcha/level/{user_id}', [\App\Http\Controllers\Api\CaptchaSolveController::class, 'getLevelByUserId']);
        Route::post('captcha/level-by-user', [\App\Http\Controllers\Api\CaptchaSolveController::class, 'getLevelByUserIdFromBody']);

        // New route for getting wallet
        Route::get('wallet', [\App\Http\Controllers\Api\WalletController::class, 'show']);
        Route::post('wallet/by-user', [\App\Http\Controllers\Api\WalletController::class, 'showByUserId']);
    });
    
    // New route for getting plans
    Route::get('plans', [\App\Http\Controllers\Api\SubscriptionPlanController::class, 'index']);
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

    