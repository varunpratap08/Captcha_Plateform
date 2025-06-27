@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
    <h1>Subscription Plans</h1>
    <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-primary">Create Plan</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Icon</th>
                <th>Caption Limit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>{{ $plan->icon ?? 'N/A' }}</td>
                    <td>{{ $plan->caption_limit }}</td>
                    <td>
                        <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $plans->links() }}
    </div>
@endsection