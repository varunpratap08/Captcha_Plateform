<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin')->only(['store']);
        $this->middleware('auth:api')->only(['purchase']);
    }

    public function index()
    {
        $plans = \App\Models\SubscriptionPlan::all();
        $userPlans = $plans->map(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'cost' => (float) $plan->cost,
                'currency' => $plan->currency ?? 'INR',
                'earning_type' => $plan->earning_type,
                'captcha_per_day' => $plan->captcha_per_day,
                'earnings' => $plan->earnings ?? [],
                'min_withdrawal_limit' => $plan->min_withdrawal_limit,
                'plan_type' => $plan->plan_type,
                'icon' => $plan->icon,
                'image' => $plan->image,
                'caption_limit' => $plan->caption_limit,
                'min_daily_earning' => (int) $plan->min_daily_earning,
            ];
        });
        return response()->json(['user_plans' => $userPlans]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'caption_limit' => 'required|integer',
        ]);

        $plan = SubscriptionPlan::create($request->only(['name', 'icon', 'caption_limit']));
        return response()->json($plan, 201);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer|exists:subscription_plans,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        $user = \App\Models\User::find($request->user_id);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
        }
        if ($user->hasRole('admin')) {
            return response()->json(['status' => 'error', 'message' => 'Admins cannot purchase plans.'], 403);
        }
        $plan = \App\Models\SubscriptionPlan::find($request->plan_id);
        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found.'], 404);
        }
        $user->subscription_name = $plan->name;
        $user->purchased_date = now();
        $user->total_amount_paid = $plan->cost;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Plan purchased successfully.',
            'user' => $user,
            'plan' => $plan
        ]);
    }
}