<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Agent login
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
                'otp' => 'required|string|size:6',
                'role' => 'required|string|in:agent'
            ]);

            // Find agent by phone number
            $agent = Agent::where('phone_number', $request->phone_number)->first();

            if (!$agent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Agent not found with this phone number'
                ], 404);
            }

            // Check if OTP exists
            if (empty($agent->otp)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No OTP found. Please request an OTP first.'
                ], 422);
            }

            // Check if OTP is expired
            if ($agent->otp_expires_at && $agent->otp_expires_at->isPast()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired'
                ], 422);
            }

            // Verify OTP using Hash::check since OTP is stored as hash
            if (!Hash::check($request->otp, $agent->otp)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP'
                ], 422);
            }

            // Clear OTP after successful verification
            $agent->otp = null;
            $agent->otp_expires_at = null;
            $agent->last_login_at = now();
            $agent->save();

            // Generate JWT token
            $token = auth('agent')->login($agent);

            Log::info('Agent logged in successfully with OTP', [
                'agent_id' => $agent->id,
                'phone_number' => $agent->phone_number
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('agent')->factory()->getTTL() * 60,
                    'agent' => [
                        'id' => $agent->id,
                        'name' => $agent->name,
                        'phone_number' => $agent->phone_number,
                        'email' => $agent->email,
                        'referral_code' => $agent->referral_code,
                        'is_verified' => $agent->is_verified,
                        'profile_completed' => $agent->profile_completed,
                        'wallet_balance' => $agent->wallet_balance,
                        'total_earnings' => $agent->total_earnings,
                        'total_withdrawals' => $agent->total_withdrawals,
                        'status' => $agent->status
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Agent login error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Agent logout
     */
    public function logout()
    {
        try {
            auth('agent')->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error('Agent logout error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Refresh agent token
     */
    public function refresh()
    {
        try {
            $token = auth('agent')->refresh();
            $agent = auth('agent')->user();

            return response()->json([
                'status' => 'success',
                'message' => 'Token refreshed successfully',
                'data' => [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('agent')->factory()->getTTL() * 60,
                    'agent' => [
                        'id' => $agent->id,
                        'name' => $agent->name,
                        'phone_number' => $agent->phone_number,
                        'email' => $agent->email,
                        'referral_code' => $agent->referral_code,
                        'is_verified' => $agent->is_verified,
                        'profile_completed' => $agent->profile_completed,
                        'wallet_balance' => $agent->wallet_balance,
                        'total_earnings' => $agent->total_earnings,
                        'total_withdrawals' => $agent->total_withdrawals,
                        'status' => $agent->status
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Agent token refresh error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Token refresh failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get current agent profile
     */
    public function me()
    {
        try {
            $agent = auth('agent')->user();

            return response()->json([
                'status' => 'success',
                'message' => 'Agent profile retrieved successfully',
                'data' => [
                    'agent' => [
                        'id' => $agent->id,
                        'name' => $agent->name,
                        'phone_number' => $agent->phone_number,
                        'email' => $agent->email,
                        'referral_code' => $agent->referral_code,
                        'is_verified' => $agent->is_verified,
                        'profile_completed' => $agent->profile_completed,
                        'wallet_balance' => $agent->wallet_balance,
                        'total_earnings' => $agent->total_earnings,
                        'total_withdrawals' => $agent->total_withdrawals,
                        'upi_id' => $agent->upi_id,
                        'bank_account_number' => $agent->bank_account_number,
                        'ifsc_code' => $agent->ifsc_code,
                        'account_holder_name' => $agent->account_holder_name,
                        'address' => $agent->address,
                        'city' => $agent->city,
                        'state' => $agent->state,
                        'pincode' => $agent->pincode,
                        'profile_image' => $agent->profile_image,
                        'aadhar_number' => $agent->aadhar_number,
                        'pan_number' => $agent->pan_number,
                        'gst_number' => $agent->gst_number,
                        'bio' => $agent->bio,
                        'status' => $agent->status,
                        'last_login_at' => $agent->last_login_at,
                        'created_at' => $agent->created_at,
                        'updated_at' => $agent->updated_at
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Agent profile retrieval error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve agent profile',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
} 