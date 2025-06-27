@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New User</h1>
        <a href="{{ route('admin.users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="referral_code">Referral Code (Optional)</label>
                            <input type="text" class="form-control @error('referral_code') is-invalid @enderror" 
                                   id="referral_code" name="referral_code" value="{{ old('referral_code') }}">
                            <small class="form-text text-muted">If left blank, a unique code will be generated</small>
                            @error('referral_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="profile_photo">Profile Photo</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('profile_photo') is-invalid @enderror" 
                               id="profile_photo" name="profile_photo">
                        <label class="custom-file-label" for="profile_photo">Choose file</label>
                        @error('profile_photo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label>Account Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" 
                               name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                    <small class="form-text text-muted">Deactivating will prevent the user from logging in.</small>
                </div>

                <div class="form-group mb-4">
                    <label>Email Verification Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="email_verified" 
                               name="email_verified" value="1" {{ old('email_verified') ? 'checked' : 'checked' }}>
                        <label class="custom-control-label" for="email_verified">Email Verified</label>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label>User Roles <span class="text-danger">*</span></label>
                    @foreach($roles as $id => $name)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="role_{{ $id }}" 
                                   name="roles[]" value="{{ $id }}" 
                                   {{ in_array($id, old('roles', [])) || $loop->first ? 'checked' : '' }}>
                            <label class="custom-control-label" for="role_{{ $id }}">
                                {{ $name }}
                            </label>
                        </div>
                    @endforeach
                    @error('roles')
                        <span class="text-danger" style="font-size: 0.875em;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update the file input label with the selected filename
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Toggle switch labels
    document.getElementById('is_active').addEventListener('change', function() {
        const label = this.nextElementSibling;
        label.textContent = this.checked ? 'Active' : 'Inactive';
    });

    document.getElementById('email_verified').addEventListener('change', function() {
        const label = this.nextElementSibling;
        label.textContent = this.checked ? 'Email Verified' : 'Email Not Verified';
    });
</script>
@endpush
@endsection
