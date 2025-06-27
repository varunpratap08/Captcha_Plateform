@extends('layouts.admin')

@section('title', 'View User: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <a href="{{ route('admin.users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">ID:</div>
                        <div class="col-sm-9">{{ $user->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Name:</div>
                        <div class="col-sm-9">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Email:</div>
                        <div class="col-sm-9">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Phone:</div>
                        <div class="col-sm-9">{{ $user->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Status:</div>
                        <div class="col-sm-9">
                            @if($user->is_verified)
                                <span class="badge badge-success">Verified</span>
                            @else
                                <span class="badge badge-warning">Pending Verification</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Roles:</div>
                        <div class="col-sm-9">
                            @forelse($user->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Created At:</div>
                        <div class="col-sm-9">{{ $user->created_at->format('M d, Y H:i:s') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 font-weight-bold">Last Updated:</div>
                        <div class="col-sm-9">{{ $user->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Additional user statistics or information can go here -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-profile rounded-circle mb-3" 
                             src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('img/undraw_profile.svg') }}" 
                             alt="Profile" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-4">{{ $user->email }}</p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small font-weight-bold text-uppercase mb-1">Email Verified</div>
                        <div>
                            @if($user->email_verified_at)
                                <span class="text-success"><i class="fas fa-check-circle"></i> {{ $user->email_verified_at->format('M d, Y') }}</span>
                            @else
                                <span class="text-danger"><i class="fas fa-times-circle"></i> Not verified</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small font-weight-bold text-uppercase mb-1">Last Login</div>
                        <div>
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in' }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="small font-weight-bold text-uppercase mb-1">Account Status</div>
                        <div>
                            @if($user->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
