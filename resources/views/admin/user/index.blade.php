@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <h1>Users</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Subscription</th>
                <th>Purchased Date</th>
                <th>Total Paid</th>
                <th>Level</th>
                <th>Wallet Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->subscription_name ?? 'N/A' }}</td>
                    <td>{{ $user->purchased_date ?? 'N/A' }}</td>
                    <td>{{ $user->total_amount_paid ?? '0.00' }}</td>
                    <td>{{ \App\Models\CaptchaSolve::where('user_id', $user->id)->count() }}</td>
                    <td>{{ $user->wallet_balance ?? '0.00' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $users->links() }}
    </div>
@endsection