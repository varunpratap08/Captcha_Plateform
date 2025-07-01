<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            display: flex;
            align-items: center;
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
            padding: 30px;
            margin-left: 260px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Cards */
        .profile-card, .account-card {
            max-width: 1200px;
            margin: 0 auto 20px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in;
        }

        .profile-card:hover, .account-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            padding: 15px;
            background: linear-gradient(90deg, var(--header-bg-start), var(--header-bg-end));
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            margin: -20px -20px 20px;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .card-content p {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 0.9rem;
        }

        .card-content p strong {
            font-weight: 500;
            color: #6b7280;
        }

        .card-content p span {
            font-weight: 600;
            color: var(--text-color);
        }

        .badge {
            padding: 6px 12px;
            border-radius: var(--border-radius);
            font-size: 0.8rem;
            font-weight: 500;
            transition: transform 0.2s ease;
            display: inline-block;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .badge-success {
            background: var(--success-color);
            color: white;
        }

        .badge-warning {
            background: var(--warning-color);
            color: white;
        }

        .badge-error {
            background: var(--error-color);
            color: white;
        }

        .badge-info {
            background: var(--info-color);
            color: white;
        }

        /* Referred Users Table */
        .referred-users-section {
            max-width: 1200px;
            margin: 20px auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
        }

        .referred-users-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .referred-users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .referred-users-table th,
        .referred-users-table td {
            padding: 14px;
            text-align: left;
            font-size: 0.9rem;
        }

        .referred-users-table th {
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            background: rgba(0, 0, 0, 0.05);
        }

        .referred-users-table tbody tr {
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .referred-users-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Back Button */
        .btn-secondary {
            background: linear-gradient(90deg, #6b7280, #9ca3af);
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(107, 114, 128, 0.3);
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

            .card-content {
                grid-template-columns: 1fr;
            }

            .profile-card,
            .account-card,
            .referred-users-section {
                max-width: 100%;
            }

            .referred-users-table {
                min-width: 800px;
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
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agents.index') }}" class="nav-link {{ Route::is('admin.agents.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscription_plans.index') }}" class="nav-link {{ Route::is('admin.subscription_plans.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Subscription Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agent-plans.index') }}" class="nav-link {{ Route::is('admin.agent-plans.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i> Agent Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.withdrawal-requests.index') }}" class="nav-link {{ Route::is('admin.withdrawal-requests.*') ? 'active' : '' }}">
                        <i class="fas fa-money-check-alt"></i> Withdrawal Requests
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="hamburger">☰</div>
            <section class="container mx-auto">
                <!-- Profile Info Card -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-user-circle mr-2"></i> Agent Profile
                        </h2>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                        @if($agent->profile_image)
                            <img src="{{ asset('storage/' . $agent->profile_image) }}" alt="Profile Image" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 16px rgba(59,130,246,0.15); border: 4px solid #fff; background: #f3f4f6;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($agent->name) }}&size=120&background=3b82f6&color=fff" alt="Default Avatar" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 16px rgba(59,130,246,0.15); border: 4px solid #fff; background: #f3f4f6;">
                        @endif
                    </div>
                    <div class="card-content">
                        <p>
                            <strong>Name:</strong>
                            <span>{{ $agent->name }}</span>
                        </p>
                        <p>
                            <strong>Phone Number:</strong>
                            <span>{{ $agent->phone_number }}</span>
                        </p>
                        <p>
                            <strong>Email:</strong>
                            <span>{{ $agent->email ?? 'N/A' }}</span>
                        </p>
                        <p>
                            <strong>Referral Code:</strong>
                            <span>{{ $agent->referral_code }}</span>
                        </p>
                        <p>
                            <strong>Status:</strong>
                            <span class="badge {{ $agent->status === 'active' ? 'badge-success' : 'badge-error' }}">
                                {{ ucfirst($agent->status ?? 'inactive') }}
                            </span>
                        </p>
                        <p>
                            <strong>Profile Completed:</strong>
                            <span class="badge {{ $agent->profile_completed ? 'badge-success' : 'badge-warning' }}">
                                {{ $agent->profile_completed ? 'Yes' : 'No' }}
                            </span>
                        </p>
                        <p>
                            <strong>Active Plan:</strong>
                            @if($agent->currentPlan())
                                {{ $agent->currentPlan()->name }}
                                @if($agent->activePlanSubscription && $agent->activePlanSubscription->started_at)
                                    <br><span class="text-xs text-gray-500">Started: {{ $agent->activePlanSubscription->started_at->format('M d, Y') }}</span>
                                @endif
                                @if($agent->activePlanSubscription && $agent->activePlanSubscription->amount_paid)
                                    <br><span class="text-xs text-gray-500">Amount: ₹{{ number_format($agent->activePlanSubscription->amount_paid, 2) }}</span>
                                @endif
                            @else
                                <span class="text-muted">No active plan</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Account Info Card -->
                <div class="account-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-wallet mr-2"></i> Account Information
                        </h2>
                    </div>
                    <div class="card-content">
                        <p>
                            <strong>Wallet Balance:</strong>
                            <span class="badge badge-success">₹{{ number_format($agent->wallet_balance ?? 0, 2) }}</span>
                        </p>
                        <p>
                            <strong>Total Referred Users:</strong>
                            <span class="badge badge-info">{{ $agent->referredUsers->count() }}</span>
                        </p>
                        <p>
                            <strong>Total Earnings:</strong>
                            <span>₹{{ number_format($agent->total_earnings ?? 0, 2) }}</span>
                        </p>
                        <p>
                            <strong>Total Withdrawals:</strong>
                            <span>₹{{ number_format($agent->total_withdrawals ?? 0, 2) }}</span>
                        </p>
                        <p>
                            <strong>Last Login At:</strong>
                            <span>{{ optional($agent->last_login_at)->format('d M Y, H:i') ?? 'Never' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Referred Users Section -->
                <div class="referred-users-section">
                    <h3>Referred Users</h3>
                    @if($agent->referredUsers->count() > 0)
                        <div class="table-container">
                            <table class="referred-users-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Profile Completed</th>
                                        <th>Joined Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agent->referredUsers as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name ?? 'N/A' }}</td>
                                            <td>{{ $user->email ?? 'N/A' }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $user->profile_completed ? 'badge-success' : 'badge-warning' }}">
                                                    {{ $user->profile_completed ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No users have been referred by this agent yet.</p>
                    @endif
                </div>

                <!-- Back Button -->
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.agents.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Agents List
                    </a>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
