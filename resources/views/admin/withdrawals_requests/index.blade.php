@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
    <h1>Withdrawal Requests</h1>
    <a href="{{ route('admin.withdrawal-requests.create') }}" class="btn btn-primary">Create Request</a>
    <table class="table">
        <thead>
            <tr>
                <th>Amount</th>
                <th>UPI ID</th>
                <th>Service Type</th>
                <th>Status</th>
                <th>User</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($withdrawalRequests as $request)
                <tr>
                    <td>{{ $request->amount }}</td>
                    <td>{{ $request->upi_id }}</td>
                    <td>{{ $request->service_type }}</td>
                    <td>
                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td>{{ $request->user->name ?? 'N/A' }}</td>
                    <td>
                        <!-- Actions: Approve/Decline buttons can be added here if needed -->
                        <span class="text-muted">Handled via API</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $withdrawalRequests->links() }}
    </div>
@endsection