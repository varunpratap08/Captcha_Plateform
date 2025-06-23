<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $user = Auth::user();
        
        $data = $request->validated();
        
        // Handle profile photo upload if present
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }
        
        // Update user profile
        $user->update($data);
        
        return response()->json([
            'message' => 'Profile completed successfully',
            'user' => $user->fresh(),
            'redirect_to' => '/dashboard' // Or any other route after profile completion
        ]);
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
