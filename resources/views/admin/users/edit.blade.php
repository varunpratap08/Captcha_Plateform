@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        <a href="{{ route('admin.users.show', $user->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to User
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
            <h6 class="m-0 font-weight-bold text-primary">Edit User Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
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
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
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
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="referral_code">Referral Code</label>
                            <input type="text" class="form-control" 
                                   id="referral_code" value="{{ $user->referral_code }}" disabled>
                            <small class="form-text text-muted">Referral code cannot be changed</small>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            <small class="form-text text-muted">Leave blank to keep current password</small>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label>Profile Photo</label>
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
                    @if($user->profile_photo_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                 alt="Profile Photo" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @endif
                </div>

                <div class="form-group mb-4">
                    <label>Account Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                               value="1" {{ $user->is_active ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </label>
                    </div>
                    <small class="form-text text-muted">Deactivating an account will prevent the user from logging in.</small>
                </div>

                <div class="form-group mb-4">
                    <label>Email Verification Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="email_verified" 
                               name="email_verified" value="1" {{ $user->hasVerifiedEmail() ? 'checked' : '' }}>
                        <label class="custom-control-label" for="email_verified">
                            {{ $user->hasVerifiedEmail() ? 'Verified' : 'Not Verified' }}
                        </label>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label>User Roles</label>
                    @foreach($roles as $id => $name)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="role_{{ $id }}" 
                                   name="roles[]" value="{{ $id }}" 
                                   {{ in_array($id, $userRoles) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="role_{{ $id }}">
                                {{ $name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">
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
        label.textContent = this.checked ? 'Verified' : 'Not Verified';
    });
</script>
@endpush
@endsection
