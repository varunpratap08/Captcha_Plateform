<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaptchaSolve;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CaptchaSolveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // POST /api/v1/captcha/solve
    public function solveCaptcha(Request $request)
    {
        $user = Auth::user();
        $plan = SubscriptionPlan::where('name', $user->subscription_name)->first();
        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'No active plan.'], 400);
        }
        $limit = (int) ($plan->captcha_per_day ?? $plan->caption_limit ?? 0);
        $todayCount = CaptchaSolve::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        if ($todayCount >= $limit) {
            return response()->json(['status' => 'error', 'message' => 'Daily captcha limit reached.'], 403);
        }
        // Record solve
        CaptchaSolve::create(['user_id' => $user->id]);
        // Calculate earning per captcha based on range
        $currentLevel = CaptchaSolve::where('user_id', $user->id)->count(); // after this solve
        $earning = 0;
        $earnings = is_string($plan->earnings) ? json_decode($plan->earnings, true) : $plan->earnings;
        if (is_array($earnings)) {
            foreach ($earnings as $range) {
                if (!empty($range['range']) && !empty($range['amount'])) {
                    // Range format: "start-end" (e.g., "1-50")
                    [$start, $end] = array_map('trim', explode('-', $range['range']));
                    if ($currentLevel >= (int)$start && $currentLevel <= (int)$end) {
                        $earning = (float)$range['amount'];
                        break;
                    }
                }
            }
        }
        if ($earning <= 0) {
            $earning = 0; // fallback, no earning if not in any range
        }
        // Add to wallet and log transaction
        $user->wallet_balance += $earning;
        $user->level = $currentLevel;
        $user->save();
        \App\Models\WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $earning,
            'type' => 'earning',
            'description' => 'Earning for solving captcha',
        ]);
        return response()->json(['status' => 'success', 'message' => 'Captcha solved.', 'level' => (int)$user->level, 'remaining' => $limit - $todayCount - 1, 'wallet_balance' => $user->wallet_balance, 'earned' => $earning]);
    }

    // GET /api/v1/captcha/level
    public function getLevel(Request $request)
    {
        $user = Auth::user();
        $plan = SubscriptionPlan::where('name', $user->subscription_name)->first();
        $limit = (int) ($plan->captcha_per_day ?? $plan->caption_limit ?? 0);
        $todayCount = CaptchaSolve::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $level = CaptchaSolve::where('user_id', $user->id)->count();
        return response()->json([
            'status' => 'success',
            'level' => $level,
            'remaining_today' => max(0, $limit - $todayCount),
            'plan_limit' => $limit,
            'wallet_balance' => $user->wallet_balance,
        ]);
    }

    // GET /api/v1/captcha/level/{user_id} (admin only)
    public function getLevelByUserId($user_id)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('admin')) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }
        $user = \App\Models\User::find($user_id);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $plan = SubscriptionPlan::where('name', $user->subscription_name)->first();
        $limit = (int) ($plan->captcha_per_day ?? $plan->caption_limit ?? 0);
        $todayCount = CaptchaSolve::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $level = CaptchaSolve::where('user_id', $user->id)->count();
        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'level' => $level,
            'remaining_today' => max(0, $limit - $todayCount),
            'plan_limit' => $limit,
            'wallet_balance' => $user->wallet_balance,
        ]);
    }

    // POST /api/v1/captcha/level-by-user (admin only, user_id in body)
    public function getLevelByUserIdFromBody(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('admin')) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        $user = \App\Models\User::find($request->user_id);
        $plan = SubscriptionPlan::where('name', $user->subscription_name)->first();
        $limit = (int) ($plan->captcha_per_day ?? $plan->caption_limit ?? 0);
        $todayCount = CaptchaSolve::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $level = CaptchaSolve::where('user_id', $user->id)->count();
        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'level' => $level,
            'remaining_today' => max(0, $limit - $todayCount),
            'plan_limit' => $limit,
            'wallet_balance' => $user->wallet_balance,
        ]);
    }
} 