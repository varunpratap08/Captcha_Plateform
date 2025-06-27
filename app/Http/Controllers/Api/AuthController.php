<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginWithOtpRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Authenticate a user using phone number and OTP
     *
     * @param LoginWithOtpRequest $request
     * @return JsonResponse
     */
    public function login(LoginWithOtpRequest $request): JsonResponse
    {
        $startTime = microtime(true);
        $requestId = (string) \Illuminate\Support\Str::uuid();
        
        try {
            Log::info('Login attempt started', [
                'request_id' => $requestId,
                'phone' => $request->phone,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Validate request data
            $validated = $request->validated();
            Log::debug('Request validated', ['request_id' => $requestId]);
            // Find the user by phone number
            $user = User::where('phone', $request->phone)->first();

            // Check if user exists
            if (!$user) {
                Log::error('Login attempt with non-existent phone number', [
                    'phone' => $request->phone,
                    'ip' => request()->ip()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No account found with this phone number.',
                ], 404);
            }

            // Verify OTP exists and is correct
            if (empty($user->otp) || !Hash::check($request->otp, $user->otp)) {
                Log::warning('Invalid OTP attempt', [
                    'user_id' => $user->id,
                    'phone' => $request->phone,
                    'ip' => request()->ip()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
                ], 400);
            }

            // Check if OTP has expired
            if (empty($user->otp_expires_at) || now()->gt($user->otp_expires_at)) {
                Log::warning('Expired OTP attempt', [
                    'user_id' => $user->id,
                    'phone' => $request->phone,
                    'otp_expires_at' => $user->otp_expires_at,
                    'current_time' => now(),
                    'ip' => request()->ip()
                ]);
                
                // Clear expired OTP
                $user->otp = null;
                $user->otp_expires_at = null;
                $user->save();
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired. Please request a new one.',
                ], 400);
            }

            // Check if user is active
            if (isset($user->is_active) && $user->is_active === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account has been deactivated. Please contact support.'
                ], 403);
            }

            // Clear the OTP after successful verification
            $user->otp = null;
            $user->otp_expires_at = null;
            
            // Update last login time
            $user->last_login_at = now();
            $user->save();

            // Generate JWT token
            $token = auth('api')->login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60,
                    'user' => $user->load('profile'),
                    'profile_completed' => (bool) $user->name,
                    'redirect_to' => $user->name ? '/dashboard' : '/complete-profile'
                ]
            ]);
            
        } catch (\Exception $e) {
            // Log the full exception with request context
            $errorContext = [
                'request_id' => $requestId,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'trace' => config('app.debug', false) ? $e->getTraceAsString() : null,
                'request_data' => [
                    'phone' => $request->phone ?? null,
                    'has_otp' => !empty($request->otp),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ],
                'execution_time_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ];
            
            Log::error('Login error occurred', $errorContext);
            
            // Prepare error response
            $response = [
                'status' => 'error',
                'message' => 'Login failed. Please try again.',
                'request_id' => $requestId
            ];
            
            // Add debug info if in debug mode
            if (config('app.debug', false)) {
                $response['debug'] = [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'exception' => get_class($e)
                ];
                
                // For database errors, include more details
                if ($e instanceof \Illuminate\Database\QueryException) {
                    $response['debug']['sql'] = $e->getSql();
                    $response['debug']['bindings'] = $e->getBindings();
                }
            }
            
            return response()->json($response, 500);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout. Please try again.'
            ], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function getUser(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'user' => $user->load('profile')
            ]);
            
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Invalid or expired token.'
            ], 401);
        }
    }
    
    /**
     * Send OTP to the provided phone number
     * 
     * @param string $phone
     * @return JsonResponse
     */
    protected function sendOtp(string $phone): JsonResponse
    {
        try {
            // Generate a random 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // In production, you would send this OTP via SMS
            // For demo, we'll just log it
            Log::info("Login OTP for {$phone}: $otp");
            
            // Find user by phone
            $user = User::where('phone', $phone)->first();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No account found with this phone number.'
                ], 404);
            }
            
            // Save OTP to user
            $user->otp = Hash::make($otp);
            $user->otp_expires_at = now()->addMinutes(10); // OTP valid for 10 minutes
            $user->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'data' => [
                    'phone' => $user->phone,
                    'otp_expires_in' => 10, // minutes
                    // In production, don't return the OTP in the response
                    'otp' => $otp // Remove this in production
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('OTP send error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}