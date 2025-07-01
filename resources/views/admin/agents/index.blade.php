<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
        }

        /* Stats Card */
        .stats-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 20px;
            text-align: center;
            width: 220px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-card h3 {
            font-size: 1.1rem;
            font-weight: 500;
            color: #6b7280;
        }

        .stats-card .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 10px 0;
            color: var(--primary-color);
        }

        .stats-card .stat-change {
            font-size: 0.9rem;
            color: var(--success-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        /* Search Bar and Create Button */
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-bar {
            position: relative;
            width: 350px;
        }

        .search-bar input {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            font-size: 0.9rem;
            color: var(--text-color);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .search-bar i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1rem;
        }

        .create-agent-btn {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            color: blue;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .create-agent-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        /* Agents Table */
        .agents-table {
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-container {
            overflow-x: auto;
        }

        .agents-table table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .agents-table th,
        .agents-table td {
            padding: 14px;
            text-align: left;
            font-size: 0.9rem;
        }

        .agents-table th {
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            background: rgba(0, 0, 0, 0.05);
        }

        .agents-table tbody tr {
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .agents-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-buttons .btn {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(10px);
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }

        .action-buttons .btn-edit {
            background: var(--primary-color);
            color: white;
        }

        .action-buttons .btn-edit:hover {
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .action-buttons .btn-info {
            background: var(--info-color);
            color: white;
        }

        .action-buttons .btn-info:hover {
            box-shadow: 0 4px 16px rgba(20, 184, 166, 0.3);
        }

        .action-buttons .btn-delete {
            background: #dc3545 !important;
            color: #fff !important;
            border: none;
            cursor: pointer;
        }

        .action-buttons .btn-delete:hover {
            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3);
            background: #b52a37 !important;
            color: #fff !important;
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            margin: 0 4px;
            border-radius: var(--border-radius);
            background: var(--card-bg);
            color: var(--text-color);
            text-decoration: none;
            transition: background 0.3s, transform 0.2s;
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .pagination .current {
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
                color: var(--text-color);
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .stats-card {
                width: 100%;
            }

            .controls {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .search-bar {
                width: 100%;
            }

            .agents-table table {
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
                    <a href="{{ route('admin.subscription_plans.index') }}" class="nav-link {{ Route::is('admin..*') ? 'active' : '' }}">
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
            @if(session('success'))
                <div style="background: #22c55e; color: #fff; padding: 14px 24px; border-radius: 8px; margin-bottom: 18px; font-weight: 500; font-size: 1.1rem;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            <div class="hamburger">☰</div>
            <section class="container mx-auto">
                <div class="header">
                    <h2>Agents</h2>
                    <div class="stats-card">
                        <h3>Total Agents</h3>
                        <p class="stat-number">{{ $agents->total() }}</p>
                        <p class="stat-change"><i class="fas fa-arrow-up"></i> +12% from last week</p>
                    </div>
                </div>

                <div class="controls">
                    <div class="search-bar">
                        <input type="text" placeholder="Search for anything..." id="searchInput">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="{{ route('admin.agents.create') }}" class="create-agent-btn">
                        <i class="fas fa-plus"></i> Create Agent
                    </a>
                </div>

                <div class="agents-table">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Date of Joining</th>
                                    <th>Total Earning</th>
                                    <th>Total Withdrawal</th>
                                    <th>Balance</th>
                                    <th>Phone Number</th>
                                    <th>Referral Code</th>
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
                                        <td>{{ $agent->referral_code }}</td>
                                        <td class="action-buttons">
                                            <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this agent?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pagination">
                    {{ $agents->links() }}
                </div>
            </section>
        </div>
    </div>

    <script>
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('.agents-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>
</body>
</html>