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
                'earnings' => is_string($plan->earnings) ? json_decode($plan->earnings, true) : $plan->earnings,
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

    /**
     * Get plan details by user ID (admin only)
     * POST /api/v1/plans/by-user-id
     * Body: { user_id: int }
     */
    public function getPlanByUserId(Request $request)
    {
        // If called from the user route, always use the authenticated user's ID
        $route = $request->route()->getName() ?? $request->path();
        $isUserRoute = str_contains($route, 'by-user-id-user');
        $userId = $isUserRoute ? auth()->id() : $request->input('user_id');

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'User ID not found.'], 400);
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
        }
        if (!$user->subscription_name) {
            return response()->json(['status' => 'error', 'message' => 'User has not purchased any plan.'], 404);
        }
        $plan = \App\Models\SubscriptionPlan::where('name', $user->subscription_name)->first();
        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found.'], 404);
        }
        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'cost' => (float) $plan->cost,
                'currency' => $plan->currency ?? 'INR',
                'earning_type' => $plan->earning_type,
                'captcha_per_day' => $plan->captcha_per_day,
                'earnings' => is_string($plan->earnings) ? json_decode($plan->earnings, true) : $plan->earnings,
                'min_withdrawal_limit' => $plan->min_withdrawal_limit,
                'plan_type' => $plan->plan_type,
                'icon' => $plan->icon,
                'image' => $plan->image,
                'caption_limit' => $plan->caption_limit,
                'min_daily_earning' => (int) $plan->min_daily_earning,
                'purchased_date' => $user->purchased_date ?? null,
            ],
        ]);
    }
}