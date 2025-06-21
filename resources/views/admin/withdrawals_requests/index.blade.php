@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
    <h1>Withdrawal Requests</h1>
    <a href="{{ route('withdrawal-requests.create') }}" class="btn btn-primary">Create Request</a>
    <table class="table">
        <thead>
            <tr>
                <th>Subscription</th>
                <th>Status</th>
                <th>User</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->subscription_name }}</td>
                    <td>{{ $request->status ? 'Approved' : 'Pending' }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>
                        <form action="{{ route('withdrawal-requests.update', $request) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status">
                                    <option value="0" {{ !$request->status ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ $request->status ? 'selected' : '' }}>Approved</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $requests->links() }}
    </div>
@endsection