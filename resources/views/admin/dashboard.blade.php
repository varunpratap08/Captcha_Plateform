<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --background-color: #f8f9fc;
            --card-bg: rgba(255, 255, 255, 0.1);
            --text-color: #333;
            --sidebar-bg: rgba(255, 255, 255, 0.95);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
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
            transition: transform 0.3s ease-in-out;
            position:-

System: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: var(--text-color);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 10px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
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
            padding: 0 30px 30px 30px; /* Remove top padding */
            /* margin-left: 250px; */ /* Remove this to eliminate the gap */
            transition: margin-left 0.3s ease-in-out;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-text.display-4 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .card a {
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .card a:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Tables */
        .table {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .table th, .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .badge {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        /* Buttons */
        .btn-outline-primary {
            border-radius: var(--border-radius);
            padding: 8px 20px;
            transition: background 0.3s, color 0.3s;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agents.index') }}" class="nav-link">
                        <i class="fas fa-users"></i> Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="fas fa-user"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscription-plans.index') }}" class="nav-link">
                        <i class="fas fa-file-alt"></i> Subscription Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agent-plans.index') }}" class="nav-link">
                        <i class="fas fa-briefcase"></i> Agent Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.withdrawal-requests.index') }}" class="nav-link">
                        <i class="fas fa-money-check-alt"></i> Withdrawal Requests
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="hamburger">&#9776;</div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom" style="margin-top:0;">
                <h1 class="h2" style="margin-top:0;">Dashboard</h1>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Agents</h5>
                            <p class="card-text display-4">{{ $totalAgents ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-4">{{ $totalUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Total User Subscriptions</h5>
                            <p class="card-text display-4">{{ $totalUserSubscriptions ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Total Agent Subscriptions</h5>
                            <p class="card-text display-4">{{ $totalAgentSubscriptions ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h5 class="card-title">Total Users Subscribed</h5>
                            <p class="card-text display-4">{{ $totalUserSubscribed ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body">
                            <h5 class="card-title">Total Referrals</h5>
                            <p class="card-text display-4">{{ $totalReferrals ?? 0 }}</p>
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
                                <a href="{{ route('admin.withdrawal-requests.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All</a>
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
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All Users</a>
                            @else
                                <p class="text-muted">No users found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Agents</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($recentAgents) && $recentAgents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Referral Code</th>
                                                <th>Status</th>
                                                <th>Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentAgents as $agent)
                                                <tr>
                                                    <td>{{ $agent->id }}</td>
                                                    <td>{{ $agent->name }}</td>
                                                    <td>{{ $agent->phone_number }}</td>
                                                    <td><code>{{ $agent->referral_code }}</code></td>
                                                    <td>
                                                        <span class="badge bg-{{ $agent->status === 'active' ? 'success' : ($agent->status === 'inactive' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($agent->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $agent->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('admin.agents.index') }}" class="btn btn-sm btn-outline-primary mt-2">View All Agents</a>
                            @else
                                <p class="text-muted">No agents found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>