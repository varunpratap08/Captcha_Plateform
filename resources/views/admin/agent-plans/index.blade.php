<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
            margin-right: 30px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Cards and Tables */
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

        .card-header {
            padding: 20px;
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .table {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            width: 100%;
        }

        .table th, .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .table thead {
            background: #f1f3f5;
        }

        .table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--text-color);
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .badge {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            padding: 8px 20px;
            transition: background 0.3s, color 0.3s;
        }

        .btn-primary:hover {
            background-color: #3b5bdb;
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border-radius: var(--border-radius);
            padding: 8px 20px;
            transition: background 0.3s, color 0.3s;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
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

            .table-responsive {
                overflow-x: auto;
            }

            .table {
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
            <img src="{{ asset('images/logo c2c 2.png') }}" alt="Logo" style="max-width: 160px; margin: 0 auto 10px; display: block;">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agents.index') }}" class="nav-link">
                        Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subscription_plans.index') }}" class="nav-link">
                        Subscription Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agent-plans.index') }}" class="nav-link">
                        Agent Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.withdrawal-requests.index') }}" class="nav-link">
                        Withdrawal Requests
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="hamburger">☰</div>
            <section class="container mx-auto">
                <div class="card">
                    <div class="card-header flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Agent Plans</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage agent subscription plans</p>
                        </div>
                        <a href="{{ route('admin.agent-plans.create') }}" class="btn btn-primary">Create Plan</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                        <th>Earning Rates</th>
                                        <th>Referral Reward</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($plans as $plan)
                                        <tr>
                                            <td>
                                                @if($plan->icon)
                                                    @if(str_starts_with($plan->icon, 'http'))
                                                        <img src="{{ $plan->icon }}" alt="Plan Icon" class="h-8 w-8 rounded">
                                                    @else
                                                        <i class="{{ $plan->icon }} text-2xl text-gray-600"></i>
                                                    @endif
                                                @else
                                                    <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                                @if($plan->description)
                                                    <div class="text-sm text-gray-500">{{ Str::limit($plan->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-sm text-gray-900">
                                                ₹{{ number_format($plan->price, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $plan->duration === 'lifetime' ? 'success' : 'info' }}">
                                                    {{ ucfirst($plan->duration) }}
                                                </span>
                                            </td>
                                            <td class="text-sm text-gray-900">
                                                <div class="space-y-1">
                                                    <div>1-50: ₹{{ $plan->rate_1_50 }}</div>
                                                    <div>51-100: ₹{{ $plan->rate_51_100 }}</div>
                                                    <div>100+: ₹{{ $plan->rate_after_100 }}</div>
                                                </div>
                                            </td>
                                            <td class="text-sm text-gray-900">
                                                ₹{{ number_format($plan->referral_reward, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.agent-plans.show', $plan) }}" class="btn btn-info btn-sm" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.agent-plans.edit', $plan) }}" class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.agent-plans.destroy', $plan) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?')" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-sm text-gray-500 text-center">
                                                No plans found. <a href="{{ route('admin.agent-plans.create') }}" class="text-indigo-600 hover:text-indigo-900">Create your first plan</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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