<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteProfileRequest;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Complete user profile
     * 
     * @param CompleteProfileRequest $request
     * @return JsonResponse
     */
    public function completeProfile(CompleteProfileRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = Auth::user();
                
                if (!$user) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'User not authenticated.'
                    ], 401);
                }
                
                $data = $request->validated();
                
                // Handle profile photo upload if present
                if ($request->hasFile('profile_photo')) {
                    try {
                        // Delete old profile photo if exists
                        if ($user->profile_photo_path) {
                            Storage::disk('public')->delete($user->profile_photo_path);
                        }
                        $path = $request->file('profile_photo')->store('profile-photos', 'public');
                        $data['profile_photo_path'] = $path;
                    } catch (\Exception $e) {
                        \Log::error('Profile photo upload failed', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                        throw new \RuntimeException('Failed to upload profile photo. Please try again.');
                    }
                }
                
                // Handle referral code if provided
                if (isset($data['referral_code'])) {
                    try {
                        $referrer = User::where('referral_code', $data['referral_code'])->first();
                        
                        // Prevent self-referral and ensure referrer exists and is not the same as the user
                        if ($referrer && $referrer->id !== $user->id) {
                            // Check if this user has already used a referral code
                            $existingReferral = UserReferral::where('referred_id', $user->id)->exists();
                            
                            if (!$existingReferral) {
                                // Record the referral
                                UserReferral::create([
                                    'referrer_id' => $referrer->id,
                                    'referred_id' => $user->id,
                                    'referral_code' => $data['referral_code'],
                                    'used_at' => now(),
                                ]);
                                
                                // You can add referral bonus logic here if needed
                            }
                        }
                        
                        // Remove referral_code from data as it's not a direct user field
                        unset($data['referral_code']);
                    } catch (\Exception $e) {
                        \Log::error('Referral processing failed', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                        // Continue without failing the entire request if referral processing fails
                    }
                }
                
                // Mark profile as completed
                $data['profile_completed'] = true;
                
                // Update user profile
                if (!$user->update($data)) {
                    throw new \RuntimeException('Failed to update user profile.');
                }
                
                // Generate new token with updated user data
                if (!$token = auth('api')->login($user)) {
                    throw new \RuntimeException('Failed to generate authentication token.');
                }
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile completed successfully',
                    'user' => $user->makeHidden(['otp', 'otp_expires_at']),
                    'profile_completed' => true,
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth('api')->factory()->getTTL() * 60 // in seconds
                    ],
                    'redirect_to' => '/dashboard'
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Profile completion failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to complete profile. ' . $e->getMessage()
            ], 500);
        }
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
    
    /**
     * Get user profile
     * 
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'requires_profile_completion' => !$user->isProfileComplete()
        ]);
    }
}
