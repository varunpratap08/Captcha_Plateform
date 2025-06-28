@extends('layouts.admin')

@section('title', 'Agent Profile')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Agent Profile</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p><strong>Name:</strong> {{ $agent->name }}</p>
                <p><strong>Phone Number:</strong> {{ $agent->phone_number }}</p>
                <p><strong>Email:</strong> {{ $agent->email ?? 'N/A' }}</p>
                <p><strong>Referral Code:</strong> {{ $agent->referral_code }}</p>
                <p><strong>Status:</strong> {{ ucfirst($agent->status ?? 'inactive') }}</p>
                <p><strong>Profile Completed:</strong> {{ $agent->profile_completed ? 'Yes' : 'No' }}</p>
                <p><strong>Total Earnings:</strong> ₹{{ number_format($agent->total_earnings ?? 0, 2) }}</p>
                <p><strong>Total Withdrawals:</strong> ₹{{ number_format($agent->total_withdrawals ?? 0, 2) }}</p>
                <p><strong>Last Login At:</strong> {{ optional($agent->last_login_at)->format('d M Y, H:i') ?? 'Never' }}</p>
            </div>
            <div>
                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <h3 class="text-lg font-semibold mb-2">Wallet Details</h3>
                    <p><strong>Wallet Balance:</strong> <span class="text-green-600 font-bold">₹{{ number_format($agent->wallet_balance ?? 0, 2) }}</span></p>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">Referral Statistics</h3>
                    <p><strong>Total Referred Users:</strong> <span class="text-blue-600 font-bold">{{ $agent->referredUsers->count() }}</span></p>
                </div>
            </div>
        </div>
        
        <!-- Referred Users Section -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Referred Users</h3>
            @if($agent->referredUsers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border-b text-left">ID</th>
                                <th class="px-4 py-2 border-b text-left">Name</th>
                                <th class="px-4 py-2 border-b text-left">Email</th>
                                <th class="px-4 py-2 border-b text-left">Phone</th>
                                <th class="px-4 py-2 border-b text-left">Profile Completed</th>
                                <th class="px-4 py-2 border-b text-left">Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agent->referredUsers as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b">{{ $user->id }}</td>
                                    <td class="px-4 py-2 border-b">{{ $user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border-b">{{ $user->email ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border-b">{{ $user->phone ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border-b">
                                        <span class="px-2 py-1 text-xs rounded {{ $user->profile_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $user->profile_completed ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border-b">{{ $user->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">No users have been referred by this agent yet.</p>
            @endif
        </div>
        
        <div class="mt-6">
            <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Back to Agents List</a>
        </div>
    </div>
</div>
@endsection 