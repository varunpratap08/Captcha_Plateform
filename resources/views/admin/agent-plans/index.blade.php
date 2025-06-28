@extends('layouts.admin')

@section('title', 'Agent Plans')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Agent Plans</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage agent subscription plans</p>
        </div>
        <a href="{{ route('admin.agent-plans.create') }}" class="btn btn-primary">Create Plan</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earning Rates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Reward</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($plans as $plan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($plan->icon)
                            @if(str_starts_with($plan->icon, 'http'))
                                <img src="{{ $plan->icon }}" alt="Plan Icon" class="h-8 w-8 rounded">
                            @else
                                <i class="{{ $plan->icon }} text-2xl text-gray-600"></i>
                            @endif
                        @else
                            <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                        @if($plan->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($plan->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ₹{{ number_format($plan->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $plan->duration === 'lifetime' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($plan->duration) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="space-y-1">
                            <div>1-50: ₹{{ $plan->rate_1_50 }}</div>
                            <div>51-100: ₹{{ $plan->rate_51_100 }}</div>
                            <div>100+: ₹{{ $plan->rate_after_100 }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ₹{{ number_format($plan->referral_reward, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.agent-plans.show', $plan) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('admin.agent-plans.edit', $plan) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form action="{{ route('admin.agent-plans.destroy', $plan) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Are you sure you want to delete this plan?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No plans found. <a href="{{ route('admin.agent-plans.create') }}" class="text-indigo-600 hover:text-indigo-900">Create your first plan</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 