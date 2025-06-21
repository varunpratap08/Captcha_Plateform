@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Agents</h5>
                    <p class="card-text display-4">{{ $totalAgents ?? 0 }}</p>
                    <a href="{{ route('agents.index') }}" class="text-white">View details →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text display-4">₹{{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Subscriptions</h5>
                    <p class="card-text display-4">{{ $totalSubscriptions ?? 0 }}</p>
                    <a href="{{ route('subscription-plans.index') }}" class="text-white">View plans →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Withdrawal Requests</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentWithdrawals) && $recentWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentWithdrawals as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->id }}</td>
                                            <td>{{ $withdrawal->user->name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($withdrawal->amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('withdrawal-requests.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All</a>
                    @else
                        <p class="text-muted">No recent withdrawal requests.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Users</h5>
                </div>
                <div class="card-body">
                    @if(isset($recentUsers) && $recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All Users</a>
                    @else
                        <p class="text-muted">No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection