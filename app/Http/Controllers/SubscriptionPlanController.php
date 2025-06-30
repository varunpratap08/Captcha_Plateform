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

    public function edit(SubscriptionPlan $subscription_plan)
    {
        return view('admin.subscription_plans.edit', compact('subscription_plan'));
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

        $subscription_plan = new SubscriptionPlan();
        $subscription_plan->name = $validated['name'];
        $subscription_plan->captcha_per_day = $validated['captcha_per_day'];
        $subscription_plan->min_withdrawal_limit = $validated['min_withdrawal_limit'] ?? null;
        $subscription_plan->cost = $validated['cost'];
        $subscription_plan->earning_type = $validated['earning_type'] ?? null;
        $subscription_plan->plan_type = $validated['plan_type'] ?? null;
        $subscription_plan->icon = $validated['icon'] ?? null;
        $subscription_plan->image = $imagePath;
        $subscription_plan->caption_limit = $validated['caption_limit'] ?? null;
        $subscription_plan->earnings = $validated['earnings'];
        $subscription_plan->min_daily_earning = $validated['min_daily_earning'] ?? null;
        $subscription_plan->save();

        return redirect()->route('admin.subscription_plans.index')->with('success', 'Plan created.');
    }

    public function update(Request $request, SubscriptionPlan $subscription_plan)
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
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plan-images', 'public');
            $validated['image'] = $imagePath;
        }

        // Prepare earnings as JSON
        $earnings = $request->input('earnings', []);
        $validated['earnings'] = json_encode($earnings);

        $subscription_plan->update($validated);
        return redirect()->route('admin.subscription_plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscription_plan)
    {
        $subscription_plan->delete();
        return redirect()->route('admin.subscription_plans.index')->with('success', 'Plan deleted.');
    }

    /**
     * Display the specified subscription plan.
     */
    public function show($id)
    {
        $subscription_plan = SubscriptionPlan::findOrFail($id);
        return view('admin.subscription_plans.show', compact('subscription_plan'));
    }
}