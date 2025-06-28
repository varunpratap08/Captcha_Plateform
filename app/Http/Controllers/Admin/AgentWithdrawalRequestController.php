<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AgentWithdrawalRequest;
use App\Models\AgentWalletTransaction;
use App\Models\Agent;

class AgentWithdrawalRequestController extends Controller
{
    // Approve agent withdrawal request
    public function approve($id, Request $request)
    {
        $admin = Auth::user();
        $withdrawal = AgentWithdrawalRequest::with('agent')->findOrFail($id);
        if ($withdrawal->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Request already processed.'], 400);
        }
        $agent = $withdrawal->agent;
        if ($agent->wallet_balance < $withdrawal->amount) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient wallet balance.'], 400);
        }
        DB::transaction(function () use ($withdrawal, $agent, $admin) {
            $agent->wallet_balance -= $withdrawal->amount;
            $agent->save();
            $withdrawal->status = 'approved';
            $withdrawal->approved_at = now();
            $withdrawal->admin_id = $admin->id;
            $withdrawal->save();
            AgentWalletTransaction::create([
                'agent_id' => $agent->id,
                'amount' => $withdrawal->amount,
                'type' => 'debit',
                'description' => 'Withdrawal approved',
            ]);
        });
        return response()->json(['status' => 'success', 'message' => 'Withdrawal approved.', 'wallet_balance' => $agent->wallet_balance]);
    }

    // Decline agent withdrawal request
    public function decline($id, Request $request)
    {
        $admin = Auth::user();
        $withdrawal = AgentWithdrawalRequest::findOrFail($id);
        if ($withdrawal->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Request already processed.'], 400);
        }
        $withdrawal->status = 'declined';
        $withdrawal->approved_at = now();
        $withdrawal->admin_id = $admin->id;
        $withdrawal->remarks = $request->remarks;
        $withdrawal->save();
        return response()->json(['status' => 'success', 'message' => 'Withdrawal declined.']);
    }
} 