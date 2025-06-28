<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AgentWalletTransaction;

class WalletController extends Controller
{
    /**
     * Get agent wallet balance and transaction history
     */
    public function transactions(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        if (!$agent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Agent not authenticated.'
            ], 401);
        }

        $transactions = $agent->walletTransactions()->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'status' => 'success',
            'wallet_balance' => $agent->wallet_balance,
            'transactions' => $transactions
        ]);
    }
} 