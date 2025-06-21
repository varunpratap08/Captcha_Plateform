@extends('layouts.admin')

@section('title', 'Agents')

@section('content')
    <h1>Agents</h1>
    <a href="{{ route('agents.create') }}" class="btn btn-primary">Create Agent</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Referral Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
                <tr>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->phone_number }}</td>
                    <td>{{ $agent->referral_code }}</td>
                    <td>
                        <a href="{{ route('agents.edit', $agent) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('agents.destroy', $agent) }}" method="POST" style="display:inline;">
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
        {{ $agents->links() }}
    </div>
@endsection