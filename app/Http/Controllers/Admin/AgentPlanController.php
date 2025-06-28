<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = AgentPlan::orderBy('sort_order')->get();
        
        return view('admin.agent-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.agent-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:lifetime,monthly,yearly',
            'rate_1_50' => 'required|numeric|min:0',
            'rate_51_100' => 'required|numeric|min:0',
            'rate_after_100' => 'required|numeric|min:0',
            'bonus_10_logins' => 'nullable|string|max:255',
            'bonus_50_logins' => 'nullable|string|max:255',
            'bonus_100_logins' => 'nullable|string|max:255',
            'min_withdrawal' => 'required|numeric|min:0',
            'max_withdrawal' => 'nullable|numeric|min:0',
            'withdrawal_time' => 'required|string|max:255',
            'unlimited_earning' => 'boolean',
            'unlimited_logins' => 'boolean',
            'max_logins_per_day' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'referral_reward' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $plan = AgentPlan::create($request->all());

        return redirect()->route('admin.agent-plans.index')
            ->with('success', 'Plan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AgentPlan $agentPlan)
    {
        $subscriptions = $agentPlan->subscriptions()->with('agent')->paginate(10);
        
        return view('admin.agent-plans.show', compact('agentPlan', 'subscriptions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AgentPlan $agentPlan)
    {
        return view('admin.agent-plans.edit', compact('agentPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AgentPlan $agentPlan)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:lifetime,monthly,yearly',
            'rate_1_50' => 'required|numeric|min:0',
            'rate_51_100' => 'required|numeric|min:0',
            'rate_after_100' => 'required|numeric|min:0',
            'bonus_10_logins' => 'nullable|string|max:255',
            'bonus_50_logins' => 'nullable|string|max:255',
            'bonus_100_logins' => 'nullable|string|max:255',
            'min_withdrawal' => 'required|numeric|min:0',
            'max_withdrawal' => 'nullable|numeric|min:0',
            'withdrawal_time' => 'required|string|max:255',
            'unlimited_earning' => 'boolean',
            'unlimited_logins' => 'boolean',
            'max_logins_per_day' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'referral_reward' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $agentPlan->update($request->all());

        return redirect()->route('admin.agent-plans.index')
            ->with('success', 'Plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgentPlan $agentPlan)
    {
        // Check if plan has active subscriptions
        if ($agentPlan->activeSubscriptions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete plan with active subscriptions!');
        }

        $agentPlan->delete();

        return redirect()->route('admin.agent-plans.index')
            ->with('success', 'Plan deleted successfully!');
    }

    /**
     * Toggle plan status
     */
    public function toggleStatus(AgentPlan $agentPlan)
    {
        $agentPlan->update([
            'is_active' => !$agentPlan->is_active
        ]);

        $status = $agentPlan->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Plan {$status} successfully!");
    }
}
