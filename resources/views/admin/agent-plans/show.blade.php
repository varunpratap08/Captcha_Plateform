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
            --background-color: #f8f9fc;
            --text-color: #1e293b;
            --sidebar-bg: rgba(255, 255, 255, 0.95);
            --card-bg: rgba(255, 255, 255, 0.95);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --accent-color: #60a5fa;
            --form-bg-start: #e0e7ff;
            --form-bg-end: #f1f5f9;
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
            margin-left: 300px;
            background: #e6f7ff;
            border-radius: var(--border-radius);
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Plan Details Section */
        .plan-details-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background: linear-gradient(135deg, var(--form-bg-start), var(--form-bg-end));
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .plan-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .plan-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .plan-header p {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .plan-actions {
            display: flex;
            gap: 10px;
            margin-left: auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 10px;
            border-radius: var(--border-radius);
        }

        .plan-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px 0;
        }

        .detail-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 15px;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .detail-card dt {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .detail-card dd {
            font-size: 1rem;
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

        .badge-info {
            background: var(--info-color);
            color: white;
        }

        .badge-error {
            background: var(--error-color);
            color: white;
        }

        .badge-warning {
            background: var(--warning-color);
            color: white;
        }

        /* Subscriptions Table */
        .subscriptions-section {
            max-width: 1200px;
            margin: 20px auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            margin-top: 32px;
        }

        .subscriptions-header {
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .subscriptions-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .subscriptions-header p {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            font-size: 0.9rem;
        }

        .table th {
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .table tbody tr {
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            padding: 10px 20px;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .btn-danger {
            background: var(--error-color);
            color: white;
            border-radius: var(--border-radius);
            padding: 10px 20px;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .btn-danger:hover {
            background: #dc2626;
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

            .plan-details-grid {
                grid-template-columns: 1fr;
            }

            .plan-details-section,
            .subscriptions-section {
                max-width: 100%;
            }

            .table {
                min-width: 800px;
            }

            .plan-actions {
                flex-direction: column;
                align-items: flex-end;
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
                <!-- Plan Details -->
                <div class="plan-details-section">
                    <div class="plan-header">
                        <div class="flex items-center gap-4">
                            @if($agentPlan->icon)
                                @if(str_starts_with($agentPlan->icon, 'http'))
                                    <img src="{{ $agentPlan->icon }}" alt="Plan Icon" class="h-12 w-12 rounded-lg">
                                @else
                                    <i class="{{ $agentPlan->icon }} text-4xl text-primary-color"></i>
                                @endif
                            @else
                                <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <img src="/images/Vector.png" alt="No Icon" class="h-8 w-8">
                                </div>
                            @endif
                            <div>
                                <h1>{{ $agentPlan->name }}</h1>
                                <p>Plan details and statistics</p>
                            </div>
                        </div>
                        <div class="plan-actions">
                            <a href="{{ route('admin.agent-plans.edit', $agentPlan) }}" class="btn btn-primary">
                                <i class="fas fa-edit mr-2"></i> Edit Plan
                            </a>
                            <form action="{{ route('admin.agent-plans.destroy', $agentPlan) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this plan?')">
                                    <i class="fas fa-trash mr-2"></i> Delete Plan
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="plan-details-grid">
                        <div class="detail-card">
                            <dt>Plan Name</dt>
                            <dd>{{ $agentPlan->name }}</dd>
                        </div>
                        <div class="detail-card">
                            <dt>Description</dt>
                            <dd>{{ $agentPlan->description ?: 'No description provided' }}</dd>
                        </div>
                        <div class="detail-card">
                            <dt>Icon</dt>
                            <dd>
                                @if($agentPlan->icon)
                                    @if(str_starts_with($agentPlan->icon, 'http'))
                                        <img src="{{ $agentPlan->icon }}" alt="Plan Icon" class="h-8 w-8 rounded inline-block">
                                        <span class="ml-2 text-gray-500">{{ $agentPlan->icon }}</span>
                                    @else
                                        <i class="{{ $agentPlan->icon }} text-2xl text-gray-600 inline-block"></i>
                                        <span class="ml-2 text-gray-500">{{ $agentPlan->icon }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">No icon set</span>
                                @endif
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Price</dt>
                            <dd>₹{{ number_format($agentPlan->price, 2) }}</dd>
                        </div>
                        <div class="detail-card">
                            <dt>Duration</dt>
                            <dd>
                                <span class="badge {{ $agentPlan->duration === 'lifetime' ? 'badge-success' : 'badge-info' }}">
                                    {{ ucfirst($agentPlan->duration) }}
                                </span>
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Status</dt>
                            <dd>
                                <span class="badge {{ $agentPlan->is_active ? 'badge-success' : 'badge-error' }}">
                                    {{ $agentPlan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Earning Rates</dt>
                            <dd>
                                <div class="space-y-2">
                                    <div>1-50 logins: ₹{{ $agentPlan->rate_1_50 }}</div>
                                    <div>51-100 logins: ₹{{ $agentPlan->rate_51_100 }}</div>
                                    <div>After 100 logins: ₹{{ $agentPlan->rate_after_100 }}</div>
                                </div>
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Bonuses</dt>
                            <dd>
                                <div class="space-y-2">
                                    <div>10 logins: {{ $agentPlan->bonus_10_logins ?: 'None' }}</div>
                                    <div>50 logins: {{ $agentPlan->bonus_50_logins ?: 'None' }}</div>
                                    <div>100 logins: {{ $agentPlan->bonus_100_logins ?: 'None' }}</div>
                                </div>
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Withdrawal Settings</dt>
                            <dd>
                                <div class="space-y-2">
                                    <div>Minimum: ₹{{ $agentPlan->min_withdrawal }}</div>
                                    <div>Maximum: {{ $agentPlan->max_withdrawal ? '₹' . $agentPlan->max_withdrawal : 'Unlimited' }}</div>
                                    <div>Time: {{ $agentPlan->withdrawal_time }}</div>
                                </div>
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Features</dt>
                            <dd>
                                <div class="space-y-2">
                                    <div>Unlimited Earning: {{ $agentPlan->unlimited_earning ? 'Yes' : 'No' }}</div>
                                    <div>Unlimited Logins: {{ $agentPlan->unlimited_logins ? 'Yes' : 'No' }}</div>
                                    @if($agentPlan->max_logins_per_day)
                                        <div>Max Logins/Day: {{ $agentPlan->max_logins_per_day }}</div>
                                    @endif
                                </div>
                            </dd>
                        </div>
                        <div class="detail-card">
                            <dt>Sort Order</dt>
                            <dd>{{ $agentPlan->sort_order }}</dd>
                        </div>
                        <div class="detail-card">
                            <dt>Created</dt>
                            <dd>{{ $agentPlan->created_at->format('F j, Y \a\t g:i A') }}</dd>
                        </div>
                        <div class="detail-card">
                            <dt>Last Updated</dt>
                            <dd>{{ $agentPlan->updated_at->format('F j, Y \a\t g:i A') }}</dd>
                        </div>
                        <div class="detail-card">
                            <dt>Referral Reward</dt>
                            <dd>₹{{ number_format($agentPlan->referral_reward, 2) }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Subscriptions -->
                <div class="subscriptions-section">
                    <div class="subscriptions-header">
                        <h2>Plan Subscriptions</h2>
                        <p>Agents who have purchased this plan</p>
                    </div>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Agent</th>
                                    <th>Amount Paid</th>
                                    <th>Status</th>
                                    <th>Started</th>
                                    <th>Expires</th>
                                    <th>Total Logins</th>
                                    <th>Total Earnings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <div class="font-medium">{{ $subscription->agent->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $subscription->agent->phone_number }}</div>
                                        </td>
                                        <td>₹{{ number_format($subscription->amount_paid, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $subscription->status === 'active' ? 'badge-success' : ($subscription->status === 'expired' ? 'badge-error' : 'badge-warning') }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $subscription->started_at->format('M j, Y') }}</td>
                                        <td>{{ $subscription->expires_at ? $subscription->expires_at->format('M j, Y') : 'Lifetime' }}</td>
                                        <td>{{ $subscription->total_logins }}</td>
                                        <td>₹{{ number_format($subscription->total_earnings, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-500">
                                            No subscriptions found for this plan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($subscriptions->hasPages())
                        <div class="mt-4">
                            {{ $subscriptions->links() }}
                        </div>
                    @endif
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