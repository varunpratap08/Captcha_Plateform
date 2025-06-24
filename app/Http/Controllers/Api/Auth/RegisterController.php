<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Register a new user with OTP verification
     * 
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            return DB::transaction(function () use ($validated) {
                // Check if user already exists
                $user = User::where('phone', $validated['phone'])->first();
                
                if ($user && $user->is_verified) {
                    // User exists and is already verified
                    $token = auth('api')->login($user);
                    return $this->respondWithToken($token, $user, 'Login successful');
                }
                
                // Verify OTP for new or unverified user
                $user = User::where('phone', $validated['phone'])
                    ->whereNotNull('otp')
                    ->where('otp_expires_at', '>', now())
                    ->first();
                
                if (!$user || !Hash::check($validated['otp'], $user->otp)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid or expired O. Please request a new OTP.'
                    ], 400);
                }
                
                // Update user verification status
                $user->update([
                    'is_verified' => true,
                    'phone_verified_at' => now(),
                    'otp' => null,
                    'otp_expires_at' => null,
                    'referral_code' => $this->generateUniqueReferralCode()
                ]);
                
                // Generate JWT token
                $token = auth('api')->login($user);
                
                return $this->respondWithToken($token, $user, 'Registration successful');
                
            });
            
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to complete registration',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Format token response
     * 
     * @param string $token
     * @param User $user
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithToken($token, $user, $message): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'phone' => $user->phone,
                    'is_verified' => true,
                    'profile_completed' => (bool) $user->name,
                ],
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60
                ],
                'redirect_to' => $user->name ? '/dashboard' : '/complete-profile'
            ]
        ], 201);
    }
    
    /**
     * Generate a unique referral code
     * 
     * @return string
     */
    protected function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());
        
        return $code;
    }
}
