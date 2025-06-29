@extends('layouts.admin')

@section('title', 'Agents')

@push('styles')
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
}

.sidebar {
    width: 250px;
    background: #f8f9fa;
    min-height: 100vh;
    padding: 20px;
    position: fixed;
}

.logo img {
    width: 100px;
    margin-bottom: 40px;
}

.nav-item {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    margin-bottom: 10px;
}

.nav-item.active {
    background: #007bff;
    color: white;
    border-radius: 5px;
}

.nav-item i {
    margin-right: 10px;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.stats-card {
    background: #f1f1f1;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 200px;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    margin: 10px 0;
}

.stat-change {
    color: #28a745;
    font-size: 14px;
}

.search-bar input {
    width: 300px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.create-agent-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}

.agents-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.agents-table th,
.agents-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.agents-table th {
    background: #f8f9fa;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.action-buttons .btn {
    padding: 5px 10px;
    border-radius: 3px;
    text-decoration: none;
    font-size: 14px;
}

.action-buttons .btn-edit {
    background: #007bff;
    color: white;
}

.action-buttons .btn-delete {
    background: #dc3545;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="header">
        <h2>Agents</h2>
        <div class="stats-card">
            <h3>Total Agents</h3>
            <p class="stat-number">{{ $agents->total() }}</p>
            <p class="stat-change">+12% from last week</p>
        </div>
    </div>

    <div class="content">
        <div class="search-bar">
            <input type="text" placeholder="Search for anything..." id="searchInput">
        </div>
        <a href="{{ route('admin.agents.create') }}" class="create-agent-btn">Create Agent</a>

        <table class="agents-table">
            <thead>
                <tr>
                    <th>Agent Name</th>
                    <th>Date of Joining</th>
                    <th>Total Earning</th>
                    <th>Total Withdrawal</th>
                    <th>Balance</th>
                    <th>Phone number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agents as $agent)
                <tr>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->created_at->format('d/m/Y') }}</td>
                    <td>₹{{ number_format($agent->total_earnings ?? 0, 2) }}</td>
                    <td>₹{{ number_format($agent->total_withdrawals ?? 0, 2) }}</td>
                    <td>₹{{ number_format($agent->wallet_balance ?? 0, 2) }}</td>
                    <td>{{ $agent->phone_number }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-edit">Edit</a>
                        <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-info" style="background: #17a2b8; color: white;">View</a>
                        <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this agent?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination" style="margin-top: 20px;">
            {{ $agents->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.agents-table tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection