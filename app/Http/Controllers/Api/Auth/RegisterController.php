<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Register a new user with phone number and OTP
     *
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            // Check if user with this phone already exists
            $user = User::where('phone', $request->phone)->first();
            
            if (!$user) {
                // Create new user with minimal details
                $user = User::create([
                    'phone' => $request->phone,
                    'otp' => Hash::make($request->otp),
                    'otp_expires_at' => now()->addMinutes(10), // OTP valid for 10 minutes
                ]);
            } else {
                // Update OTP for existing user
                $user->update([
                    'otp' => Hash::make($request->otp),
                    'otp_expires_at' => now()->addMinutes(10),
                ]);
            }
            
            // In production, you would send this OTP via SMS
            // For demo, we'll return success response
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'data' => [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'is_verified' => false,
                    'profile_completed' => false,
                ]
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process registration',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Verify OTP and complete registration
     * 
     * @return JsonResponse
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'otp' => 'required|string|size:4',
                'device_name' => 'required|string',
            ]);

            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            if (!Hash::check($request->otp, $user->otp)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
                    'errors' => [
                        'otp' => ['The provided OTP is invalid.']
                    ]
                ], 422);
            }

            if ($user->otp_expires_at && $user->otp_expires_at->isPast()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired',
                    'errors' => [
                        'otp' => ['The OTP has expired. Please request a new one.']
                    ]
                ], 422);
            }

            // Mark phone as verified
            $user->update([
                'phone_verified_at' => now(),
                'otp' => null,
                'otp_expires_at' => null,
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Create token for the device
            $token = $user->createToken($request->device_name)->plainTextToken;

            // Format response according to Figma design
            return response()->json([
                'status' => 'success',
                'message' => 'Phone number verified successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'country_code' => $user->country_code,
                        'is_verified' => (bool)$user->phone_verified_at,
                        'profile_completed' => $user->isProfileComplete(),
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => config('sanctum.expiration') * 60, // in seconds
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('OTP Verification error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Generate a unique referral code
     * 
     * @return string
     */
    private function generateUniqueReferralCode(): string
    {
        $code = Str::upper(Str::random(8));
        
        // Ensure the code is unique
        while (User::where('referral_code', $code)->exists()) {
            $code = Str::upper(Str::random(8));
        }
        
        return $code;
    }
}
