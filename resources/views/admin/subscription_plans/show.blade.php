<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plan Details</title>
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

        /* Card */
        .details-card {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 24px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in;
        }

        .details-card:hover {
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
            margin: -24px -24px 20px;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
            text-align: center;
        }

        /* Details Table */
        .details-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1000px;
        }

        .details-table th,
        .details-table td {
            padding: 14px;
            text-align: left;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .details-table th {
            font-weight: 700;
            color: #6b7280;
            width: 25%;
        }

        .details-table td {
            font-weight: 600;
            color: var(--text-color);
        }

        .details-table tr:last-child th,
        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-table tbody tr {
            transition: background 0.3s ease;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
        }

        .details-table tbody tr:nth-child(even) {
            background: linear-gradient(to right, rgba(245, 245, 245, 0.98), rgba(245, 245, 245, 0.92));
        }

        .details-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        /* Earnings Table */
        .earnings-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-top: 10px;
        }

        .earnings-table th,
        .earnings-table td {
            padding: 10px;
            text-align: left;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .earnings-table th {
            font-weight: 500;
            color: #6b7280;
            background: rgba(0, 0, 0, 0.05);
        }

        .earnings-table tbody tr {
            transition: background 0.3s ease, transform 0.2s ease;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
        }

        .earnings-table tbody tr:nth-child(even) {
            background: linear-gradient(to right, rgba(245, 245, 245, 0.98), rgba(245, 245, 245, 0.92));
        }

        .earnings-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Icon and Image */
        .icon-display {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            transition: transform 0.2s ease;
        }

        .icon-display:hover {
            transform: scale(1.1);
        }

        .image-display {
            max-width: 120px;
            border-radius: var(--border-radius);
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            transition: transform 0.2s ease;
        }

        .image-display:hover {
            transform: scale(1.05);
        }

        .text-muted {
            color: #6b7280;
            font-style: italic;
        }

        /* Table Container */
        .table-container {
            overflow-x: auto;
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

            .details-card {
                max-width: 100%;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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
                        <i class="fas fa-list-alt mr-2"></i> Subscription Plan Details
                    </h1>
                    <a href="{{ route('admin.subscription_plans.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Plans
                    </a>
                </div>

                <!-- Details Card -->
                <div class="details-card">
                    <div class="card-header">
                        <h2>Plan Details</h2>
                    </div>
                    <div class="table-container">
                        <table class="details-table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $subscription_plan->name }}</td>
                                </tr>
                                <tr>
                                    <th>Captcha Per Day</th>
                                    <td>{{ $subscription_plan->captcha_per_day }}</td>
                                </tr>
                                <tr>
                                    <th>Min Withdrawal Limit</th>
                                    <td>{{ $subscription_plan->min_withdrawal_limit }}</td>
                                </tr>
                                <tr>
                                    <th>Cost</th>
                                    <td>{{ $subscription_plan->cost }}</td>
                                </tr>
                                <tr>
                                    <th>Earning Type</th>
                                    <td>{{ $subscription_plan->earning_type }}</td>
                                </tr>
                                <tr>
                                    <th>Plan Type</th>
                                    <td>{{ $subscription_plan->plan_type }}</td>
                                </tr>
                                <tr>
                                    <th>Icon</th>
                                    <td>
                                        @if($subscription_plan->icon)
                                            <span class="icon-display"><i class="{{ $subscription_plan->icon }}"></i></span>
                                            {{ $subscription_plan->icon }}
                                        @else
                                            <span class="text-muted">No icon</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Image</th>
                                    <td>
                                        @if($subscription_plan->image)
                                            <img src="{{ asset('storage/' . $subscription_plan->image) }}" alt="Plan Image" class="image-display">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Captcha Limit</th>
                                    <td>{{ $subscription_plan->captcha_limit }}</td>
                                </tr>
                                <tr>
                                    <th>Earnings</th>
                                    <td>
                                        @php $earnings = is_array($subscription_plan->earnings) ? $subscription_plan->earnings : json_decode($subscription_plan->earnings, true); @endphp
                                        @if($earnings && is_array($earnings))
                                            <table class="earnings-table">
                                                <thead>
                                                    <tr>
                                                        <th>Range</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($earnings as $earning)
                                                        <tr>
                                                            <td>{{ $earning['range'] ?? '-' }}</td>
                                                            <td>{{ $earning['amount'] ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Min Daily Earning</th>
                                    <td>{{ $subscription_plan->min_daily_earning }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $subscription_plan->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $subscription_plan->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
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
