<!-- Standalone HTML file with custom styles, no Blade layout or section directives -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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
            margin-right: 30px;
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

        /* Alerts */
        .alert {
            max-width: 1200px;
            margin: 0 auto 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 15px;
            color: white;
            position: relative;
            animation: slideIn 0.5s ease-in;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.95);
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.95);
            backdrop-filter: blur(10px);
        }

        .alert .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .alert .close:hover {
            transform: scale(1.2);
        }

        /* Table Card */
        .table-card {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in;
        }

        .table-card:hover {
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

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
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

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1000px;
        }

        .users-table th,
        .users-table td {
            padding: 14px;
            text-align: left;
            font-size: 0.9rem;
        }

        .users-table th {
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            background: rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .users-table th.sortable::after {
            content: '↕';
            position: absolute;
            right: 8px;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .users-table tbody tr {
            transition: background 0.3s ease, transform 0.2s ease;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
        }

        .users-table tbody tr:nth-child(even) {
            background: linear-gradient(to right, rgba(245, 245, 245, 0.98), rgba(245, 245, 245, 0.92));
        }

        .users-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

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

        .badge-info {
            background: var(--info-color);
            color: white;
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

        .badge:hover {
            transform: scale(1.05);
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
            animation: pulse 2s infinite;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
            animation: none;
        }

        .btn-primary-sm, .btn-info-sm, .btn-danger-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .btn-info {
            background: linear-gradient(90deg, var(--info-color), #2dd4bf);
            color: white;
            border-radius: var(--border-radius);
            padding: 8px 16px;
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

        .btn-danger {
            background: linear-gradient(90deg, var(--error-color), #f87171);
            color: white;
            border-radius: var(--border-radius);
            padding: 8px 16px;
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

        /* DataTable Search */
        .dataTables_filter {
            margin-bottom: 15px;
        }

        .dataTables_filter input {
            padding: 10px 40px 10px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            color: var(--text-color);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .dataTables_filter label {
            position: relative;
        }

        .dataTables_filter label::after {
            content: '\f002';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1rem;
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

            .table-card, .alert {
                max-width: 100%;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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
                        <i class="fas fa-users mr-2"></i> Manage Users
                    </h1>
                    <a href="{{ route('admin.users.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i> Add New User
                    </a>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <span class="close">×</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                        <span class="close">×</span>
                    </div>
                @endif

                <!-- Table Card -->
                <div class="table-card">
                    <div class="card-header">
                        <h2>Users List</h2>
                    </div>
                    <div class="table-container">
                        <table class="users-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="sortable">ID</th>
                                    <th class="sortable">Name</th>
                                    <th class="sortable">Email</th>
                                    <th class="sortable">Phone</th>
                                    <th class="sortable">Agent Referral</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->referringAgent)
                                                <span class="badge badge-info" title="Referred by {{ $user->referringAgent->name }}">
                                                    {{ $user->agent_referral_code }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $user->referringAgent->name }}</small>
                                            @else
                                                <span class="text-muted">No referral</span>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($user->is_verified)
                                                <span class="badge badge-success">Verified</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn-info btn-info-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary btn-primary-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger btn-danger-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-gray-500 italic">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $users->links('vendor.pagination.custom') }}
                </div>
            </section>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            if ($.fn.DataTable.isDataTable('#usersTable')) {
                $('#usersTable').DataTable().destroy();
            }

            $('#usersTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25,
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search users...",
                }
            });

            // Close alerts
            document.querySelectorAll('.alert .close').forEach(button => {
                button.addEventListener('click', () => {
                    button.parentElement.style.display = 'none';
                });
            });

            // Hamburger menu toggle
            document.querySelector('.hamburger').addEventListener('click', () => {
                document.querySelector('.sidebar').classList.toggle('active');
            });
        });
    </script>
</body>
</html>