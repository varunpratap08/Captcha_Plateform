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
        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'caption_limit' => 'required|integer',
        ]);

        SubscriptionPlan::create($request->only(['name', 'icon', 'caption_limit']));
        return redirect()->route('subscription-plans.index')->with('success', 'Plan created.');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'caption_limit' => 'required|integer',
        ]);

        $plan->update($request->only(['name', 'icon', 'caption_limit']));
        return redirect()->route('subscription-plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return redirect()->route('subscription-plans.index')->with('success', 'Plan deleted.');
    }
}