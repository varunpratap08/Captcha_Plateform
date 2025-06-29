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
                
                // Handle agent referral code if provided
                if (isset($data['agent_referral_code'])) {
                    try {
                        $agent = \App\Models\Agent::where('referral_code', $data['agent_referral_code'])->first();
                        
                        // Ensure agent exists and is active
                        if ($agent && $agent->status === 'active') {
                            // Check if this user has already used an agent referral code
                            if (!$user->agent_id) {
                                // Update user with agent referral
                                $data['agent_id'] = $agent->id;
                                
                                // Credit agent wallet with referral reward from their current plan
                                $plan = $agent->currentPlan();
                                if ($plan && $plan->referral_reward > 0) {
                                    $agent->wallet_balance += $plan->referral_reward;
                                    $agent->total_earnings += $plan->referral_reward;
                                    $agent->save();
                                    // Log the transaction
                                    \App\Models\AgentWalletTransaction::create([
                                        'agent_id' => $agent->id,
                                        'amount' => $plan->referral_reward,
                                        'type' => 'credit',
                                        'description' => 'Referral reward for user #' . $user->id,
                                    ]);
                                    \Log::info('Agent wallet credited for referral', [
                                        'agent_id' => $agent->id,
                                        'user_id' => $user->id,
                                        'reward' => $plan->referral_reward
                                    ]);
                                }
                                
                                \Log::info('Agent referral recorded', [
                                    'user_id' => $user->id,
                                    'agent_id' => $agent->id,
                                    'agent_referral_code' => $data['agent_referral_code']
                                ]);
                            }
                        } else {
                            \Log::warning('Invalid or inactive agent referral code used', [
                                'user_id' => $user->id,
                                'agent_referral_code' => $data['agent_referral_code']
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Agent referral processing failed', [
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
                $user->refresh();
                
                // Generate new token with updated user data
                if (!$token = auth('api')->login($user)) {
                    throw new \RuntimeException('Failed to generate authentication token.');
                }
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile completed successfully',
                    'user' => $user->makeHidden(['otp', 'otp_expires_at'])->makeVisible(['agent_id', 'agent_referral_code']),
                    'profile_completed' => true,
                    'profile_photo_url' => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null,
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
            'user' => $user->makeVisible(['agent_id', 'agent_referral_code']),
            'requires_profile_completion' => !$user->isProfileComplete()
        ]);
    }

    /**
     * Update user profile (edit profile)
     * 
     * @param CompleteProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(CompleteProfileRequest $request): JsonResponse
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
                // Handle agent referral code if provided
                if (isset($data['agent_referral_code'])) {
                    try {
                        $agent = \App\Models\Agent::where('referral_code', $data['agent_referral_code'])->first();
                        if ($agent && $agent->status === 'active') {
                            if (!$user->agent_id) {
                                $data['agent_id'] = $agent->id;
                            }
                        } else {
                            \Log::warning('Invalid or inactive agent referral code used in update', [
                                'user_id' => $user->id,
                                'agent_referral_code' => $data['agent_referral_code']
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Agent referral processing failed in update', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                // Do not change profile_completed flag here
                if (!$user->update($data)) {
                    throw new \RuntimeException('Failed to update user profile.');
                }
                $user->refresh();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile updated successfully',
                    'user' => $user->makeHidden(['otp', 'otp_expires_at'])->makeVisible(['agent_id', 'agent_referral_code'])
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile. ' . $e->getMessage()
            ], 500);
        }
    }
}
