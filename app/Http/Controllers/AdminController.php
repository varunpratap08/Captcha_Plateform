<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            
            // Get recent withdrawal requests (last 5)
            $recentWithdrawals = WithdrawalRequest::with('user')
                ->latest()
                ->take(5)
                ->get();
                
            // Get recent users (last 5)
            $recentUsers = User::latest()
                ->take(5)
                ->get();
            
            return view('admin.dashboard', [
                'totalAgents' => $totalAgents,
                'totalRevenue' => $totalRevenue,
                'totalSubscriptions' => $totalSubscriptions,
                'recentWithdrawals' => $recentWithdrawals,
                'recentUsers' => $recentUsers
            ]);
        } catch (\Exception $e) {
            // Log the error and redirect back with error message
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'An error occurred while loading the dashboard. Please try again.');
        }
    }
}