@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
    <h1>Withdrawal Requests</h1>
    <div class="mb-2">
        <a href="{{ route('admin.all-withdrawal-requests') }}" class="btn btn-info">View All Withdrawal Requests (User + Agent)</a>
    </div>
    <a href="{{ route('admin.withdrawal-requests.create') }}" class="btn btn-primary">Create Request</a>

    @if(isset($userWithdrawalRequests) && isset($agentWithdrawalRequests))
        <h2>User Withdrawal Requests</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>UPI ID</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userWithdrawalRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ optional($request->user)->name ?? 'N/A' }}</td>
                        <td>{{ $request->amount }}</td>
                        <td>{{ $request->upi_id }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->created_at }}</td>
                        <td>
                            @if($request->status === 'pending')
                                <form action="{{ route('admin.withdrawal-requests.update', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('admin.withdrawal-requests.update', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="decline">
                                    <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                </form>
                            @else
                                <span class="text-muted">Handled</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            <span>Total User Requests: {{ $userWithdrawalRequests->count() }}</span>
        </div>

        <h2>Agent Withdrawal Requests</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Agent</th>
                    <th>Amount</th>
                    <th>UPI ID</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agentWithdrawalRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ optional($request->agent)->name ?? 'N/A' }}</td>
                        <td>{{ $request->amount }}</td>
                        <td>{{ $request->upi_id }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->created_at }}</td>
                        <td>
                            @if($request->status === 'pending')
                                <form action="{{ route('admin.agent-withdrawal-requests.approve', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('admin.agent-withdrawal-requests.decline', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                </form>
                            @else
                                <span class="text-muted">Handled</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            <span>Total Agent Requests: {{ $agentWithdrawalRequests->count() }}</span>
        </div>
    @elseif(isset($withdrawalRequests))
        <h2>User Withdrawal Requests</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>UPI ID</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($withdrawalRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ optional($request->user)->name ?? 'N/A' }}</td>
                        <td>{{ $request->amount }}</td>
                        <td>{{ $request->upi_id }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->created_at }}</td>
                        <td>
                            @if($request->status === 'pending')
                                <form action="{{ route('admin.withdrawal-requests.update', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('admin.withdrawal-requests.update', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="decline">
                                    <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                </form>
                            @else
                                <span class="text-muted">Handled</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $withdrawalRequests->links() }}
        </div>
    @else
        <div class="alert alert-warning">No withdrawal requests found.</div>
    @endif
@endsection