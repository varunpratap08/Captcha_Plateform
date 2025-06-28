<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentWithdrawalRequest;
use App\Models\AgentWalletTransaction;

class WithdrawalController extends Controller
{
    /**
     * Agent creates a withdrawal request
     */
    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'upi_id' => 'required|string',
        ]);

        $amount = $request->amount;
        $upiId = $request->upi_id;
        $plan = $agent->currentPlan();
        $fee = ($plan && isset($plan->withdrawal_fee)) ? (float)$plan->withdrawal_fee : 100.0;
        $finalAmount = $amount - $fee;

        if ($amount > $agent->wallet_balance) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient wallet balance.'], 400);
        }
        if ($finalAmount <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Withdrawal amount after fee must be positive.'], 400);
        }

        $withdrawal = AgentWithdrawalRequest::create([
            'agent_id' => $agent->id,
            'amount' => $amount,
            'fee' => $fee,
            'final_withdrawal_amount' => $finalAmount,
            'upi_id' => $upiId,
            'status' => 'pending',
            'request_date' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Withdrawal request submitted.',
            'withdrawal' => $withdrawal
        ]);
    }

    /**
     * Agent views their withdrawal requests
     */
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $requests = $agent->withdrawalRequests()->orderByDesc('created_at')->paginate(20);
        return response()->json([
            'status' => 'success',
            'withdrawal_requests' => $requests
        ]);
    }
} 