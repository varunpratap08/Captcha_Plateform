<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $withdrawalRequests = WithdrawalRequest::with('user')->latest()->paginate(10);
        return view('admin.withdrawals_requests.index', compact('withdrawalRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.withdrawals_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        
        WithdrawalRequest::create($validated);

        return redirect()->route('withdrawal-requests.index')
            ->with('success', 'Withdrawal request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WithdrawalRequest $withdrawalRequest)
    {
        return view('admin.withdrawals_requests.show', compact('withdrawalRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WithdrawalRequest $withdrawalRequest)
    {
        return view('admin.withdrawals_requests.edit', compact('withdrawalRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        $validated = $request->validate([
            'subscription_name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $withdrawalRequest->update($validated);

        return redirect()->route('withdrawal-requests.index')
            ->with('success', 'Withdrawal request updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WithdrawalRequest $withdrawalRequest)
    {
        $withdrawalRequest->delete();

        return redirect()->route('withdrawal-requests.index')
            ->with('success', 'Withdrawal request deleted successfully');
    }
}
