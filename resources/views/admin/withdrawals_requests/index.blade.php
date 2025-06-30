<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Requests</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --info-color: #14b8a6;
            --background-color: #f8f9fc;
            --text-color: #1e293b;
            --sidebar-bg: rgba(255, 255, 255, 0.95);
            --card-bg: rgba(255, 255, 255, 0.95);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --accent-color: #60a5fa;
            --header-bg-start: #e0e7ff;
            --header-bg-end: #f1f5f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar .nav-link {
            color: var(--text-color);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 10px;
            transition: background 0.3s, color 0.3s;
            display: block;
            font-size: 1rem;
            font-weight: 500;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .sidebar .sidebar-header {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 270px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-in;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .btn-info {
            background: linear-gradient(90deg, var(--info-color), #2dd4bf);
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(20, 184, 166, 0.3);
        }

        .btn-success-sm, .btn-danger-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .btn-success {
            background: linear-gradient(90deg, var(--success-color), #4ade80);
            color: white;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(34, 197, 94, 0.3);
        }

        .btn-danger {
            background: linear-gradient(90deg, var(--error-color), #f87171);
            color: white;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
        }

        /* Alert */
        .alert-warning {
            max-width: 1200px;
            margin: 0 auto 20px;
            background: rgba(245, 158, 11, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 15px;
            color: white;
            position: relative;
            animation: slideIn 0.5s ease-in;
        }

        .alert-warning .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .alert-warning .close:hover {
            transform: scale(1.2);
        }

        /* Cards */
        .request-card {
            max-width: 1200px;
            margin: 0 auto 20px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in;
        }

        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .card-header {
            padding: 15px;
            background: linear-gradient(90deg, var(--header-bg-start), var(--header-bg-end));
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            margin: -1px -1px 20px;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .requests-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1000px;
        }

        .requests-table th,
        .requests-table td {
            padding: 14px;
            text-align: left;
            font-size: 0.9rem;
        }

        .requests-table th {
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            background: rgba(0, 0, 0, 0.05);
        }

        .requests-table tbody tr {
            transition: background 0.3s ease, transform 0.2s ease;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
        }

        .requests-table tbody tr:nth-child(even) {
            background: linear-gradient(to right, rgba(245, 245, 245, 0.98), rgba(245, 245, 245, 0.92));
        }

        .requests-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: var(--border-radius);
            font-size: 0.8rem;
            font-weight: 500;
            transition: transform 0.2s ease;
        }

        .badge-success {
            background: var(--success-color);
            color: white;
        }

        .badge-warning {
            background: var(--warning-color);
            color: white;
        }

        .badge-danger {
            background: var(--error-color);
            color: white;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        /* Action Group */
        .action-group {
            display: flex;
            gap: 10px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: transform 0.2s, box-shadow 0.3s, background 0.3s;
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .pagination .current {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .total-count {
            font-size: 0.9rem;
            color: #6b7280;
            text-align: center;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .hamburger {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1100;
                cursor: pointer;
                font-size: 1.5rem;
                color: var(--text-color);
            }

            .request-card, .alert-warning {
                max-width: 100%;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-group {
                flex-direction: column;
                gap: 8px;
            }
        }

        @media (min-width: 769px) {
            .hamburger {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">Admin Panel</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agents.index') }}" class="nav-link {{ Route::is('admin.agents.*') ? 'active' : '' }}">
                        Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscription_plans.index') }}" class="nav-link {{ Route::is('admin.subscription_plans.*') ? 'active' : '' }}">
                        Subscription Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agent-plans.index') }}" class="nav-link {{ Route::is('admin.agent-plans.*') ? 'active' : '' }}">
                        Agent Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.withdrawal-requests.index') }}" class="nav-link {{ Route::is('admin.withdrawal-requests.*') ? 'active' : '' }}">
                        Withdrawal Requests
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="hamburger">☰</div>
            <section class="container mx-auto">
                <!-- Header -->
                <div class="header">
                    <h1>
                        <i class="fas fa-money-check-alt mr-2"></i> Withdrawal Requests
                    </h1>
                    <div class="action-buttons">
                        <a href="{{ route('admin.all-withdrawal-requests') }}" class="btn-info">
                            <i class="fas fa-eye mr-2"></i> View All Withdrawal Requests
                        </a>
                    </div>
                </div>

                <!-- No Requests Alert -->
                @if(!isset($withdrawalRequests) && !isset($userWithdrawalRequests) && !isset($agentWithdrawalRequests))
                    <div class="alert-warning">
                        No withdrawal requests found.
                        <span class="close">×</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- User Withdrawal Requests -->
                @if(isset($showAll) && $showAll && isset($userWithdrawalRequests) && isset($agentWithdrawalRequests))
                    <div class="request-card">
                        <div class="card-header">
                            <h2>User Withdrawal Requests</h2>
                        </div>
                        <div class="table-container">
                            <table class="requests-table">
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
                                                <span class="badge badge-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('M d, Y H:i:s') }}</td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <div class="action-group">
                                                        <form action="{{ route('admin.withdrawal-requests.update', $request->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="btn-success btn-success-sm">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.withdrawal-requests.update', $request->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="action" value="decline">
                                                            <button type="submit" class="btn-danger btn-danger-sm">
                                                                <i class="fas fa-times"></i> Decline
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Handled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="total-count">
                            Total User Requests: {{ $userWithdrawalRequests->count() }}
                        </div>
                    </div>

                    <!-- Agent Withdrawal Requests -->
                    <div class="request-card">
                        <div class="card-header">
                            <h2>Agent Withdrawal Requests</h2>
                        </div>
                        <div class="table-container">
                            <table class="requests-table">
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
                                                <span class="badge badge-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('M d, Y H:i:s') }}</td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <div class="action-group">
                                                        <form action="{{ route('admin.agent-withdrawal-requests.approve', $request->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-success btn-success-sm">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.agent-withdrawal-requests.decline', $request->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-danger btn-danger-sm">
                                                                <i class="fas fa-times"></i> Decline
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Handled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="total-count">
                            Total Agent Requests: {{ $agentWithdrawalRequests->count() }}
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <script>
        // Hamburger menu toggle
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Close alert
        document.querySelectorAll('.alert-warning .close').forEach(button => {
            button.addEventListener('click', () => {
                button.parentElement.style.display = 'none';
            });
        });
    </script>
</body>
</html>
