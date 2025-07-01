<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AgentReferralController extends Controller
{
    /**
     * Get agent referral code and list of referred users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function referrals(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        if (!$agent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Agent not authenticated.'
            ], 401);
        }

        $referralCode = $agent->referral_code;
        $referredUsers = User::where('agent_id', $agent->id)->get();

        $users = $referredUsers->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $this->maskPhone($user->phone),
                'profile_completed' => (bool) $user->profile_completed,
                'registered_at' => $user->created_at->toDateTimeString(),
            ];
        });
        // If no real users, add a dummy user
        if ($users->isEmpty()) {
            $users = collect([
                [
                    'id' => 0,
                    'name' => 'Dummy User',
                    'phone' => '9999*****00',
                    'profile_completed' => false,
                    'registered_at' => now()->toDateTimeString(),
                ]
            ]);
        }

        // Optionally, calculate referral earnings if you have such logic
        // $totalReferralEarnings = ...;

        return response()->json([
            'status' => 'success',
            'referral_code' => $referralCode,
            'users' => $users,
            // 'total_referral_earnings' => $totalReferralEarnings,
        ]);
    }

    /**
     * Mask phone number for privacy (e.g., +91 992*****12)
     */
    private function maskPhone($phone)
    {
        if (!$phone) return null;
        // Show first 4 and last 2 digits, mask the rest
        return substr($phone, 0, 4) . str_repeat('*', max(0, strlen($phone) - 6)) . substr($phone, -2);
    }
} 