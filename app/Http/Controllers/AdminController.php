<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentPlan;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        try {
            $totalAgents = Agent::count();
            $totalRevenue = User::sum('total_amount_paid');
            $totalSubscriptions = SubscriptionPlan::count();
            $totalAgentPlans = AgentPlan::count();
            
            // Get recent withdrawal requests (last 5)
            $recentWithdrawals = WithdrawalRequest::with('user')
                ->latest()
                ->take(5)
                ->get();
                
            // Get recent users (last 5)
            $recentUsers = User::latest()
                ->take(5)
                ->get();
            
            // Get recent agents (last 5)
            $recentAgents = Agent::latest()
                ->take(5)
                ->get();
            
            return view('admin.dashboard', [
                'totalAgents' => $totalAgents,
                'totalRevenue' => $totalRevenue,
                'totalSubscriptions' => $totalSubscriptions,
                'totalAgentPlans' => $totalAgentPlans,
                'recentWithdrawals' => $recentWithdrawals,
                'recentUsers' => $recentUsers,
                'recentAgents' => $recentAgents
            ]);
        } catch (\Exception $e) {
            // Log the error and redirect back with error message
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'An error occurred while loading the dashboard. Please try again.');
        }
    }

    /**
     * Create a new agent
     */
    public function createAgent(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|regex:/^[0-9]{10}$/|unique:agents,phone_number',
                'email' => 'nullable|email|unique:agents,email',
                'password' => 'required|string|min:6',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'pincode' => 'nullable|string|regex:/^[0-9]{6}$/',
                'aadhar_number' => 'nullable|string|regex:/^[0-9]{12}$/',
                'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'gst_number' => 'nullable|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                'bio' => 'nullable|string|max:1000'
            ]);

            // Generate unique referral code
            $referralCode = Agent::generateReferralCode();

            // Create the agent
            $agent = Agent::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'referral_code' => $referralCode,
                'is_verified' => true,
                'profile_completed' => true,
                'status' => 'active',
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'aadhar_number' => $request->aadhar_number,
                'pan_number' => $request->pan_number,
                'gst_number' => $request->gst_number,
                'bio' => $request->bio
            ]);

            // Assign agent role
            $agent->assignRole('agent');

            Log::info('Agent created by admin', [
                'admin_id' => Auth::id(),
                'agent_id' => $agent->id,
                'phone_number' => $agent->phone_number,
                'referral_code' => $agent->referral_code
            ]);

            return redirect()->route('admin.agents.index')
                ->with('success', 'Agent created successfully with referral code: ' . $agent->referral_code);

        } catch (\Exception $e) {
            Log::error('Agent creation error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create agent: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update agent
     */
    public function updateAgent(Request $request, $id)
    {
        try {
            $agent = Agent::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|regex:/^[0-9]{10}$/|unique:agents,phone_number,' . $id,
                'email' => 'nullable|email|unique:agents,email,' . $id,
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'pincode' => 'nullable|string|regex:/^[0-9]{6}$/',
                'aadhar_number' => 'nullable|string|regex:/^[0-9]{12}$/',
                'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'gst_number' => 'nullable|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                'bio' => 'nullable|string|max:1000',
                'status' => 'required|in:active,inactive,suspended'
            ]);

            $agent->update($request->only([
                'name', 'phone_number', 'email', 'address', 'city', 'state', 'pincode',
                'aadhar_number', 'pan_number', 'gst_number', 'bio', 'status'
            ]));

            Log::info('Agent updated by admin', [
                'admin_id' => Auth::id(),
                'agent_id' => $agent->id,
                'phone_number' => $agent->phone_number
            ]);

            return redirect()->route('admin.agents.index')
                ->with('success', 'Agent updated successfully');

        } catch (\Exception $e) {
            Log::error('Agent update error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update agent: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete agent
     */
    public function deleteAgent($id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agentName = $agent->name;
            $agent->delete();

            Log::info('Agent deleted by admin', [
                'admin_id' => Auth::id(),
                'agent_id' => $id,
                'agent_name' => $agentName
            ]);

            return redirect()->route('admin.agents.index')
                ->with('success', 'Agent deleted successfully');

        } catch (\Exception $e) {
            Log::error('Agent deletion error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete agent: ' . $e->getMessage());
        }
    }

    public function allWithdrawalRequests()
    {
        $userWithdrawalRequests = \App\Models\WithdrawalRequest::with('user')
            ->select('id', 'user_id', 'amount', 'upi_id', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        $agentWithdrawalRequests = \App\Models\AgentWithdrawalRequest::with('agent')
            ->select('id', 'agent_id', 'amount', 'upi_id', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.withdrawals_requests.index', [
            'userWithdrawalRequests' => $userWithdrawalRequests,
            'agentWithdrawalRequests' => $agentWithdrawalRequests
        ]);
    }
}