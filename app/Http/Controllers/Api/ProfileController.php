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
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $data = $request->validated();
            
            // Handle profile photo upload if present
            if ($request->hasFile('profile_photo')) {
                // Delete old profile photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $data['profile_photo_path'] = $path;
            }
            
            // Handle referral code if provided
            if (isset($data['referral_code'])) {
                $referrer = User::where('referral_code', $data['referral_code'])->first();
                
                if ($referrer) {
                    // Record the referral
                    UserReferral::create([
                        'referrer_id' => $referrer->id,
                        'referred_id' => $user->id,
                        'referral_code' => $data['referral_code'],
                        'used_at' => now(),
                    ]);
                    
                    // You can add referral bonus logic here if needed
                }
                
                // Remove referral_code from data as it's not a direct user field
                unset($data['referral_code']);
            }
            
            // Generate a referral code for the user if they don't have one
            if (empty($user->referral_code)) {
                $data['referral_code'] = $this->generateUniqueReferralCode();
            }
            
            // Update user profile
            $user->update($data);
            
            // Generate new token with updated user data
            $token = auth('api')->login($user);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Profile completed successfully',
                'user' => $user->makeHidden(['otp', 'otp_expires_at']),
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60 // in seconds
                ],
                'redirect_to' => '/dashboard'
            ]);
        });
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
