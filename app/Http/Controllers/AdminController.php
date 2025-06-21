<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin');
    }

    public function dashboard()
    {
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
    }
}