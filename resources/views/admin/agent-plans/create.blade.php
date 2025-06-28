@extends('layouts.admin')

@section('title', 'Create Agent Plan')

@section('content')
<div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Create New Agent Plan</h3>
        
        <form action="{{ route('admin.agent-plans.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Basic Information -->
                <div class="sm:col-span-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Basic Information</h4>
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Plan Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (₹) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="referral_reward" class="block text-sm font-medium text-gray-700">Referral Reward (₹) *</label>
                    <input type="number" name="referral_reward" id="referral_reward" value="{{ old('referral_reward', 0) }}" step="0.01" min="0" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">Duration *</label>
                    <select name="duration" id="duration" required
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Duration</option>
                        <option value="lifetime" {{ old('duration') == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                        <option value="monthly" {{ old('duration') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('duration') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                </div>
                
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700">Icon (URL or FontAwesome class)</label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g., fas fa-star or https://example.com/icon.png"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <p class="mt-1 text-sm text-gray-500">Enter FontAwesome class (e.g., fas fa-star) or image URL</p>
                </div>
                
                <!-- Earning Rates -->
                <div class="sm:col-span-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Earning Rates (₹ per login)</h4>
                </div>
                
                <div>
                    <label for="rate_1_50" class="block text-sm font-medium text-gray-700">Rate for 1-50 logins *</label>
                    <input type="number" name="rate_1_50" id="rate_1_50" value="{{ old('rate_1_50') }}" step="0.01" min="0" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="rate_51_100" class="block text-sm font-medium text-gray-700">Rate for 51-100 logins *</label>
                    <input type="number" name="rate_51_100" id="rate_51_100" value="{{ old('rate_51_100') }}" step="0.01" min="0" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="rate_after_100" class="block text-sm font-medium text-gray-700">Rate after 100 logins *</label>
                    <input type="number" name="rate_after_100" id="rate_after_100" value="{{ old('rate_after_100') }}" step="0.01" min="0" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <!-- Bonuses -->
                <div class="sm:col-span-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Bonuses</h4>
                </div>
                
                <div>
                    <label for="bonus_10_logins" class="block text-sm font-medium text-gray-700">Bonus at 10 logins</label>
                    <input type="text" name="bonus_10_logins" id="bonus_10_logins" value="{{ old('bonus_10_logins') }}" placeholder="e.g., Cap"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="bonus_50_logins" class="block text-sm font-medium text-gray-700">Bonus at 50 logins</label>
                    <input type="text" name="bonus_50_logins" id="bonus_50_logins" value="{{ old('bonus_50_logins') }}" placeholder="e.g., T-shirt"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="bonus_100_logins" class="block text-sm font-medium text-gray-700">Bonus at 100 logins</label>
                    <input type="text" name="bonus_100_logins" id="bonus_100_logins" value="{{ old('bonus_100_logins') }}" placeholder="e.g., Bag"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <!-- Withdrawal Settings -->
                <div class="sm:col-span-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Withdrawal Settings</h4>
                </div>
                
                <div>
                    <label for="min_withdrawal" class="block text-sm font-medium text-gray-700">Minimum Withdrawal (₹) *</label>
                    <input type="number" name="min_withdrawal" id="min_withdrawal" value="{{ old('min_withdrawal', 250.00) }}" step="0.01" min="0" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <div>
                    <label for="max_withdrawal" class="block text-sm font-medium text-gray-700">Maximum Withdrawal (₹)</label>
                    <input type="number" name="max_withdrawal" id="max_withdrawal" value="{{ old('max_withdrawal') }}" step="0.01" min="0"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <p class="mt-1 text-sm text-gray-500">Leave empty for unlimited</p>
                </div>
                
                <div class="sm:col-span-2">
                    <label for="withdrawal_time" class="block text-sm font-medium text-gray-700">Withdrawal Time *</label>
                    <input type="text" name="withdrawal_time" id="withdrawal_time" value="{{ old('withdrawal_time', 'Monday to Saturday 9:00AM to 18:00PM') }}" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                
                <!-- Features -->
                <div class="sm:col-span-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Features</h4>
                </div>
                
                <div class="sm:col-span-2">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="unlimited_earning" id="unlimited_earning" value="1" {{ old('unlimited_earning', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="unlimited_earning" class="ml-2 block text-sm text-gray-900">Unlimited Earning</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="unlimited_logins" id="unlimited_logins" value="1" {{ old('unlimited_logins') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="unlimited_logins" class="ml-2 block text-sm text-gray-900">Unlimited Logins</label>
                        </div>
                        
                        <div>
                            <label for="max_logins_per_day" class="block text-sm font-medium text-gray-700">Maximum Logins Per Day</label>
                            <input type="number" name="max_logins_per_day" id="max_logins_per_day" value="{{ old('max_logins_per_day') }}" min="1"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-sm text-gray-500">Leave empty if unlimited logins is enabled</p>
                        </div>
                    </div>
                </div>
                
                <!-- Status -->
                <div class="sm:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active Plan</label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.agent-plans.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection 