@extends('layouts.admin')

@section('title', 'Agent Plan Details')

@section('content')
<div class="space-y-6">
    <!-- Plan Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $agentPlan->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Plan details and statistics</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.agent-plans.edit', $agentPlan) }}" class="btn btn-primary">Edit Plan</a>
                <form action="{{ route('admin.agent-plans.destroy', $agentPlan) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this plan?')">
                        Delete Plan
                    </button>
                </form>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Plan Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $agentPlan->name }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $agentPlan->description ?: 'No description provided' }}</dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Icon</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($agentPlan->icon)
                            @if(str_starts_with($agentPlan->icon, 'http'))
                                <img src="{{ $agentPlan->icon }}" alt="Plan Icon" class="h-8 w-8">
                            @else
                                <i class="{{ $agentPlan->icon }} text-2xl text-gray-600"></i>
                            @endif
                            <span class="ml-2 text-gray-500">{{ $agentPlan->icon }}</span>
                        @else
                            <span class="text-gray-400">No icon set</span>
                        @endif
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">₹{{ number_format($agentPlan->price, 2) }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Duration</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $agentPlan->duration === 'lifetime' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($agentPlan->duration) }}
                        </span>
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $agentPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $agentPlan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Earning Rates</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div><strong>1-50 logins:</strong> ₹{{ $agentPlan->rate_1_50 }}</div>
                            <div><strong>51-100 logins:</strong> ₹{{ $agentPlan->rate_51_100 }}</div>
                            <div><strong>After 100 logins:</strong> ₹{{ $agentPlan->rate_after_100 }}</div>
                        </div>
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Bonuses</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div><strong>10 logins:</strong> {{ $agentPlan->bonus_10_logins ?: 'None' }}</div>
                            <div><strong>50 logins:</strong> {{ $agentPlan->bonus_50_logins ?: 'None' }}</div>
                            <div><strong>100 logins:</strong> {{ $agentPlan->bonus_100_logins ?: 'None' }}</div>
                        </div>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Withdrawal Settings</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div><strong>Minimum:</strong> ₹{{ $agentPlan->min_withdrawal }}</div>
                            <div><strong>Maximum:</strong> {{ $agentPlan->max_withdrawal ? '₹' . $agentPlan->max_withdrawal : 'Unlimited' }}</div>
                            <div><strong>Time:</strong> {{ $agentPlan->withdrawal_time }}</div>
                        </div>
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Features</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div><strong>Unlimited Earning:</strong> {{ $agentPlan->unlimited_earning ? 'Yes' : 'No' }}</div>
                            <div><strong>Unlimited Logins:</strong> {{ $agentPlan->unlimited_logins ? 'Yes' : 'No' }}</div>
                            @if($agentPlan->max_logins_per_day)
                                <div><strong>Max Logins/Day:</strong> {{ $agentPlan->max_logins_per_day }}</div>
                            @endif
                        </div>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Sort Order</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $agentPlan->sort_order }}</dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $agentPlan->created_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $agentPlan->updated_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Referral Reward</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">₹{{ number_format($agentPlan->referral_reward, 2) }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Subscriptions -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Plan Subscriptions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Agents who have purchased this plan</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Logins</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Earnings</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $subscription->agent->name }}</div>
                            <div class="text-sm text-gray-500">{{ $subscription->agent->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₹{{ number_format($subscription->amount_paid, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($subscription->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subscription->started_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subscription->expires_at ? $subscription->expires_at->format('M j, Y') : 'Lifetime' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $subscription->total_logins }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₹{{ number_format($subscription->total_earnings, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No subscriptions found for this plan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($subscriptions->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 