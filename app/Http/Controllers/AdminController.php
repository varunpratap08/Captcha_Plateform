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
        return view('admin.dashboard', compact('totalAgents', 'totalRevenue', 'totalSubscriptions'));
    }
}