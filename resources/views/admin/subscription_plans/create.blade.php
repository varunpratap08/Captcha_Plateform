@extends('layouts.admin')

@section('title', 'Create Subscription Plan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Create New Subscription Plan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subscription-plans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Plan Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="captcha_per_day">Captcha per day</label>
                        <input type="text" name="captcha_per_day" id="captcha_per_day" class="form-control @error('captcha_per_day') is-invalid @enderror" value="{{ old('captcha_per_day') }}" required>
                        @error('captcha_per_day')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="min_withdrawal_limit">Minimum Withdrawal Limit</label>
                        <input type="number" name="min_withdrawal_limit" id="min_withdrawal_limit" class="form-control @error('min_withdrawal_limit') is-invalid @enderror" value="{{ old('min_withdrawal_limit') }}">
                        @error('min_withdrawal_limit')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cost">Price</label>
                        <input type="number" step="0.01" name="cost" id="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost') }}" required>
                        @error('cost')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="plan_type">Plan Type</label>
                        <select name="plan_type" id="plan_type" class="form-control @error('plan_type') is-invalid @enderror">
                            <option value="">Select Type</option>
                            <option value="Monthly" {{ old('plan_type') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="Yearly" {{ old('plan_type') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="Unlimited" {{ old('plan_type') == 'Unlimited' ? 'selected' : '' }}>Unlimited</option>
                        </select>
                        @error('plan_type')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="icon">Icon (Optional)</label>
                        <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="e.g., fas fa-star">
                        <small class="form-text text-muted">Use Font Awesome icon classes (e.g., fas fa-star)</small>
                        @error('icon')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror">
                        @error('image')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="caption_limit">Captcha Limit</label>
                        <input type="text" name="caption_limit" id="caption_limit" class="form-control @error('caption_limit') is-invalid @enderror" value="{{ old('caption_limit') }}">
                        @error('caption_limit')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="min_daily_earning">Minimum Daily Earning</label>
                        <input type="number" name="min_daily_earning" id="min_daily_earning" class="form-control @error('min_daily_earning') is-invalid @enderror" value="{{ old('min_daily_earning') }}">
                        @error('min_daily_earning')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="earning_type">Earning Type</label>
                        <input type="text" name="earning_type" id="earning_type" class="form-control @error('earning_type') is-invalid @enderror" value="{{ old('earning_type') }}">
                        @error('earning_type')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label>Earnings (Add multiple ranges and amounts)</label>
                    <div id="earnings-list">
                        <div class="form-row mb-2 earning-row">
                            <div class="col">
                                <input type="text" name="earnings[0][range]" class="form-control" placeholder="Range (e.g. 1-50)" />
                            </div>
                            <div class="col">
                                <input type="number" name="earnings[0][amount]" class="form-control" placeholder="Amount (e.g. 5)" />
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-danger btn-sm remove-earning">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" id="add-earning">Add More</button>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Create Plan</button>
                    <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        let earningIndex = 1;
        document.getElementById('add-earning').addEventListener('click', function() {
            const earningsList = document.getElementById('earnings-list');
            const row = document.createElement('div');
            row.className = 'form-row mb-2 earning-row';
            row.innerHTML = `
                <div class="col">
                    <input type="text" name="earnings[${earningIndex}][range]" class="form-control" placeholder="Range (e.g. 1-50)" />
                </div>
                <div class="col">
                    <input type="number" name="earnings[${earningIndex}][amount]" class="form-control" placeholder="Amount (e.g. 5)" />
                </div>
                <div class="col">
                    <button type="button" class="btn btn-danger btn-sm remove-earning">Remove</button>
                </div>
            `;
            earningsList.appendChild(row);
            earningIndex++;
        });
        document.getElementById('earnings-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-earning')) {
                e.target.closest('.earning-row').remove();
            }
        });
    </script>
@endsection
