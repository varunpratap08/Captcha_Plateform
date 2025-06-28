<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentPlan;
use App\Models\AgentPlanSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * Get all active plans
     */
    public function index()
    {
        try {
            $plans = AgentPlan::active()
                ->orderBy('sort_order')
                ->get()
                ->map(function ($plan) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'description' => $plan->description,
                        'price' => $plan->price,
                        'duration' => $plan->duration,
                        'icon' => $plan->icon ?? null
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Plans retrieved successfully',
                'data' => [
                    'plans' => $plans
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve plans',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get a specific plan by ID
     */
    public function show($id)
    {
        try {
            $plan = AgentPlan::active()->findOrFail($id);

            $planData = [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description,
                'price' => $plan->price,
                'duration' => $plan->duration,
                'icon' => $plan->icon ?? null
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Plan retrieved successfully',
                'data' => [
                    'plan' => $planData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan not found or not available',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 404);
        }
    }

    /**
     * Purchase a plan
     */
    public function purchase(Request $request)
    {
        try {
            $request->validate([
                'plan_id' => 'required|exists:agent_plans,id',
                'agent_id' => 'required|integer|exists:agents,id'
            ]);

            $agent = auth('agent')->user();
            if ($request->agent_id != $agent->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Agent ID does not match the authenticated agent.'
                ], 403);
            }

            $plan = AgentPlan::findOrFail($request->plan_id);

            // Check if plan is active
            if (!$plan->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This plan is not available for purchase'
                ], 400);
            }

            // Check if agent already has an active plan
            if ($agent->hasActivePlan()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You already have an active plan'
                ], 400);
            }

            // Check if agent has sufficient wallet balance
            if ($agent->wallet_balance < $plan->price) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance. Required: ₹' . $plan->price . ', Available: ₹' . $agent->wallet_balance
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Deduct amount from wallet
                $agent->wallet_balance -= $plan->price;
                $agent->save();
                // Log the transaction
                \App\Models\AgentWalletTransaction::create([
                    'agent_id' => $agent->id,
                    'amount' => $plan->price,
                    'type' => 'debit',
                    'description' => 'Plan purchase: ' . $plan->name,
                ]);

                // Create subscription
                $subscription = AgentPlanSubscription::create([
                    'agent_id' => $agent->id,
                    'plan_id' => $plan->id,
                    'amount_paid' => $plan->price,
                    'payment_method' => 'wallet',
                    'transaction_id' => 'WALLET_' . time() . '_' . $agent->id,
                    'status' => 'active',
                    'started_at' => now(),
                    'expires_at' => $plan->duration === 'lifetime' ? null : now()->addMonths($plan->duration === 'monthly' ? 1 : 12),
                    'total_logins' => 0,
                    'total_earnings' => 0.00
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Plan purchased successfully',
                    'data' => [
                        'subscription' => [
                            'id' => $subscription->id,
                            'plan_name' => $plan->name,
                            'amount_paid' => $subscription->amount_paid,
                            'payment_method' => $subscription->payment_method,
                            'transaction_id' => $subscription->transaction_id,
                            'started_at' => $subscription->started_at,
                            'expires_at' => $subscription->expires_at,
                            'status' => $subscription->status
                        ],
                        'plan_details' => [
                            'id' => $plan->id,
                            'name' => $plan->name,
                            'description' => $plan->description,
                            'price' => $plan->price,
                            'duration' => $plan->duration,
                            'icon' => $plan->icon ?? null,
                            'earning_rates' => [
                                '1-50_logins' => $plan->rate_1_50,
                                '51-100_logins' => $plan->rate_51_100,
                                'after_100_logins' => $plan->rate_after_100
                            ],
                            'bonuses' => [
                                '10_logins' => $plan->bonus_10_logins,
                                '50_logins' => $plan->bonus_50_logins,
                                '100_logins' => $plan->bonus_100_logins
                            ],
                            'withdrawal_settings' => [
                                'min_withdrawal' => $plan->min_withdrawal,
                                'max_withdrawal' => $plan->max_withdrawal,
                                'withdrawal_time' => $plan->withdrawal_time
                            ]
                        ],
                        'wallet_info' => [
                            'previous_balance' => $agent->wallet_balance + $plan->price,
                            'current_balance' => $agent->wallet_balance,
                            'amount_deducted' => $plan->price
                        ]
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to purchase plan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get agent's current plan
     */
    public function myPlan()
    {
        try {
            $agent = auth('agent')->user();
            $subscription = $agent->activePlanSubscription;

            if (!$subscription) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No active plan found'
                ], 404);
            }

            $plan = $subscription->plan;

            return response()->json([
                'status' => 'success',
                'message' => 'Current plan retrieved successfully',
                'data' => [
                    'subscription' => [
                        'id' => $subscription->id,
                        'started_at' => $subscription->started_at,
                        'expires_at' => $subscription->expires_at,
                        'total_logins' => $subscription->total_logins,
                        'total_earnings' => $subscription->total_earnings,
                        'current_earning_rate' => $subscription->getCurrentEarningRate(),
                        'status' => $subscription->status
                    ],
                    'plan' => [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'description' => $plan->description,
                        'price' => $plan->price,
                        'duration' => $plan->duration,
                        'earning_rates' => [
                            '1-50_logins' => $plan->rate_1_50,
                            '51-100_logins' => $plan->rate_51_100,
                            'after_100_logins' => $plan->rate_after_100
                        ],
                        'bonuses' => [
                            '10_logins' => $plan->bonus_10_logins,
                            '50_logins' => $plan->bonus_50_logins,
                            '100_logins' => $plan->bonus_100_logins
                        ],
                        'withdrawal_settings' => [
                            'min_withdrawal' => $plan->min_withdrawal,
                            'max_withdrawal' => $plan->max_withdrawal,
                            'withdrawal_time' => $plan->withdrawal_time
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve current plan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get agent's detailed information including current plan
     */
    public function agentDetails()
    {
        try {
            $agent = auth('agent')->user();
            $subscription = $agent->activePlanSubscription;
            $plan = $subscription ? $subscription->plan : null;

            $response = [
                'agent' => [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'phone_number' => $agent->phone_number,
                    'email' => $agent->email,
                    'referral_code' => $agent->referral_code,
                    'wallet_balance' => $agent->wallet_balance,
                    'total_earnings' => $agent->total_earnings,
                    'total_withdrawals' => $agent->total_withdrawals,
                    'profile_completed' => $agent->profile_completed,
                    'status' => $agent->status,
                    'last_login_at' => $agent->last_login_at
                ],
                'current_plan' => null
            ];

            if ($subscription && $plan) {
                $response['current_plan'] = [
                    'subscription_id' => $subscription->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'plan_description' => $plan->description,
                    'plan_icon' => $plan->icon,
                    'amount_paid' => $subscription->amount_paid,
                    'started_at' => $subscription->started_at,
                    'expires_at' => $subscription->expires_at,
                    'total_logins' => $subscription->total_logins,
                    'total_earnings' => $subscription->total_earnings,
                    'current_earning_rate' => $subscription->getCurrentEarningRate(),
                    'status' => $subscription->status,
                    'earning_rates' => [
                        '1-50_logins' => $plan->rate_1_50,
                        '51-100_logins' => $plan->rate_51_100,
                        'after_100_logins' => $plan->rate_after_100
                    ],
                    'bonuses' => [
                        '10_logins' => $plan->bonus_10_logins,
                        '50_logins' => $plan->bonus_50_logins,
                        '100_logins' => $plan->bonus_100_logins
                    ],
                    'withdrawal_settings' => [
                        'min_withdrawal' => $plan->min_withdrawal,
                        'max_withdrawal' => $plan->max_withdrawal,
                        'withdrawal_time' => $plan->withdrawal_time
                    ]
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Agent details retrieved successfully',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve agent details',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
