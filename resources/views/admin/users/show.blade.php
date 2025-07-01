<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details: {{ $user->name }}</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Cards */
        .info-card, .stats-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in;
            margin-bottom: 20px;
        }

        .info-card:hover, .stats-card:hover {
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

        .card-body {
            padding: 20px;
        }

        .card-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* User Information */
        .info-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }

        .info-label {
            width: 120px;
            font-weight: 500;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .info-value {
            flex: 1;
            font-size: 0.9rem;
            color: var(--text-color);
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

        .badge-primary {
            background: var(--primary-color);
            color: white;
        }

        .badge-success {
            background: var(--success-color);
            color: white;
        }

        .badge-warning {
            background: var(--warning-color);
            color: white;
        }

        .badge-secondary {
            background: #6b7280;
            color: white;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        /* Stats Card */
        .stats-card .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .stats-card .profile-photo:hover {
            transform: scale(1.05);
        }

        .stats-card h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stats-card .email {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .stats-row {
            margin-bottom: 15px;
        }

        .stats-label {
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .stats-value {
            font-size: 0.9rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stats-value .fas {
            font-size: 1rem;
        }

        /* Buttons */
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

        .btn-danger {
            background: linear-gradient(90deg, var(--error-color), #f87171);
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
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

            .info-card, .stats-card {
                max-width: 100%;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card-footer {
                flex-direction: column;
                align-items: flex-end;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .info-label {
                width: auto;
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
                        <i class="fas fa-user mr-2"></i> User Details: {{ $user->name }}
                    </h1>
                    <a href="{{ route('admin.users.index') }}" class="btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Users
                    </a>
                </div>

                <!-- Cards Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- User Information Card -->
                    <div class="info-card lg:col-span-2">
                        <div class="card-header">
                            <h2>User Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">ID:</div>
                                <div class="info-value">{{ $user->id }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Name:</div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email:</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Phone:</div>
                                <div class="info-value">{{ $user->phone ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Status:</div>
                                <div class="info-value">
                                    @if($user->is_verified)
                                        <span class="badge badge-success">Verified</span>
                                    @else
                                        <span class="badge badge-warning">Pending Verification</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Roles:</div>
                                <div class="info-value">
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">No roles assigned</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Created At:</div>
                                <div class="info-value">{{ $user->created_at->format('M d, Y H:i:s') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Last Updated:</div>
                                <div class="info-value">{{ $user->updated_at->format('M d, Y H:i:s') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Level:</div>
                                <div class="info-value">{{ $user->level ?? (\App\Models\CaptchaSolve::where('user_id', $user->id)->count()) }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Captcha Solved:</div>
                                <div class="info-value">{{ \App\Models\CaptchaSolve::where('user_id', $user->id)->count() }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Wallet Amount:</div>
                                <div class="info-value">₹{{ number_format($user->wallet_balance ?? 0, 2) }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Purchased Plan:</div>
                                <div class="info-value">
                                    @if($user->subscription_name)
                                        {{ $user->subscription_name }}
                                        @if($user->purchased_date)
                                            <br><span class="text-xs text-gray-500">Purchased on: {{ $user->purchased_date->format('M d, Y') }}</span>
                                        @endif
                                        @if($user->total_amount_paid)
                                            <br><span class="text-xs text-gray-500">Amount: ₹{{ number_format($user->total_amount_paid, 2) }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No plan purchased</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary">
                                <i class="fas fa-edit mr-2"></i> Edit User
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-trash mr-2"></i> Delete User
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Account Statistics Card -->
                    <div class="stats-card lg:col-span-1">
                        <div class="card-header">
                            <h2>Account Statistics</h2>
                        </div>
                        <div class="card-body text-center">
                            <img class="profile-photo" 
                                 src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('img/undraw_profile.svg') }}" 
                                 alt="Profile">
                            <h4>{{ $user->name }}</h4>
                            <p class="email">{{ $user->email }}</p>
                            <hr class="border-t border-gray-200 my-4">
                            <div class="stats-row">
                                <div class="stats-label">Email Verified</div>
                                <div class="stats-value">
                                    @if($user->email_verified_at)
                                        <span class="text-success"><i class="fas fa-check-circle mr-1"></i> {{ $user->email_verified_at->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-times-circle mr-1"></i> Not verified</span>
                                    @endif
                                </div>
                            </div>
                            <div class="stats-row">
                                <div class="stats-label">Last Login</div>
                                <div class="stats-value">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in' }}
                                </div>
                            </div>
                            <div class="stats-row">
                                <div class="stats-label">Account Status</div>
                                <div class="stats-value">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Hamburger menu toggle
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
