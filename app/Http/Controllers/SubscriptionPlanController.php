<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin');
    }

    public function index()
    {
        $plans = SubscriptionPlan::paginate(10);
        return view('admin.subscription_plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new subscription plan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.subscription_plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'captcha_per_day' => 'required|string',
            'min_withdrawal_limit' => 'nullable|integer',
            'cost' => 'required|numeric',
            'earning_type' => 'nullable|string',
            'plan_type' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption_limit' => 'nullable|integer',
            'earnings' => 'nullable|array',
            'earnings.*.range' => 'nullable|string',
            'earnings.*.amount' => 'nullable|numeric',
            'min_daily_earning' => 'nullable|integer',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plan-images', 'public');
        }

        // Prepare earnings as JSON
        $earnings = $request->input('earnings', []);
        $validated['earnings'] = json_encode($earnings);

        $plan = new SubscriptionPlan();
        $plan->name = $validated['name'];
        $plan->captcha_per_day = $validated['captcha_per_day'];
        $plan->min_withdrawal_limit = $validated['min_withdrawal_limit'] ?? null;
        $plan->cost = $validated['cost'];
        $plan->earning_type = $validated['earning_type'] ?? null;
        $plan->plan_type = $validated['plan_type'] ?? null;
        $plan->icon = $validated['icon'] ?? null;
        $plan->image = $imagePath;
        $plan->caption_limit = $validated['caption_limit'] ?? null;
        $plan->earnings = $validated['earnings'];
        $plan->min_daily_earning = $validated['min_daily_earning'] ?? null;
        $plan->save();

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan created.');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'caption_limit' => 'required|integer',
        ]);

        $plan->update($request->only(['name', 'icon', 'caption_limit']));
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan deleted.');
    }
}