<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OtpController extends Controller
{
    /**
     * Send OTP to the provided phone number
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendOtp(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'phone' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{10}$/',
                    'max:15',
                ]
            ]);

            // Generate a random 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // In production, you would send this OTP via SMS
            // For demo, we'll just log it
            Log::info("OTP for {$request->phone}: $otp");
            
            // Find or create user
            $user = User::firstOrNew(['phone' => $request->phone]);
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

    /**
     * Verify OTP and mark user as verified
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'phone' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{10}$/',
                    'exists:users,phone',
                ],
                'otp' => [
                    'required',
                    'string',
                    'size:6',
                ]
            ]);

            $user = User::where('phone', $request->phone)->first();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }

            // Check if OTP is valid and not expired
            if (!Hash::check($request->otp, $user->otp)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
                ], 400);
            }

            if (now()->gt($user->otp_expires_at)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired',
                ], 400);
            }

            // Mark user as verified
            $user->is_verified = true;
            $user->phone_verified_at = now();
            $user->otp = null; // Clear OTP after successful verification
            $user->otp_expires_at = null;
            $user->save();

            // Generate JWT token for the user
            $token = auth('api')->login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'is_verified' => true,
                        'profile_completed' => (bool) $user->name, // Profile is complete if name is set
                    ],
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth('api')->factory()->getTTL() * 60 // in seconds
                    ],
                    'redirect_to' => $user->name ? '/dashboard' : '/complete-profile'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('OTP verification error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to verify OTP',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
