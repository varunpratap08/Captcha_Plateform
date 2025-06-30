<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Register a new agent
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => [
                    'required',
                    'string',
                    'min:6',
                    'max:20',
                    'regex:/^[+0-9\- ]+$/'
                ],
                'otp' => 'required|string|size:6',
                'role' => 'required|string|in:agent'
            ]);

            DB::beginTransaction();

            // Check if agent already exists with this phone number
            $existingAgent = Agent::where('phone_number', $request->phone_number)->first();
            
            if (!$existingAgent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please send OTP first before registration'
                ], 400);
            }

            // Check if agent is already verified
            if ($existingAgent->is_verified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This phone number is already registered. Please login instead.'
                ], 409);
            }

            // Verify OTP first
            if (!$existingAgent->otp || !Hash::check($request->otp, $existingAgent->otp)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP'
                ], 400);
            }

            // Check if OTP is expired
            if ($existingAgent->otp_expires_at && now()->gt($existingAgent->otp_expires_at)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired'
                ], 400);
            }

            // Update the existing agent with verification data
            $existingAgent->update([
                'is_verified' => true,
                'phone_verified_at' => now(),
                'status' => 'active',
                'otp' => null,
                'otp_expires_at' => null,
                'profile_completed' => false
            ]);

            // Assign agent role if not already assigned
            if (!$existingAgent->hasRole('agent')) {
                $existingAgent->assignRole('agent');
            }

            // Generate JWT token
            $token = auth('agent')->login($existingAgent);

            DB::commit();

            Log::info('Agent registered successfully', [
                'agent_id' => $existingAgent->id,
                'phone_number' => $existingAgent->phone_number,
                'referral_code' => $existingAgent->referral_code
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Agent registered successfully',
                'data' => [
                    'agent' => [
                        'id' => $existingAgent->id,
                        'phone_number' => $existingAgent->phone_number,
                        'is_verified' => $existingAgent->is_verified,
                        'profile_completed' => $existingAgent->profile_completed ?? false,
                    ],
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth('agent')->factory()->getTTL() * 60,
                    ],
                    'profile_completed' => $existingAgent->profile_completed ?? false,
                    'requires_profile_completion' => !($existingAgent->profile_completed ?? false)
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Agent registration error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register agent',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
} 