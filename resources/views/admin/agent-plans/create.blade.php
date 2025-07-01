<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            padding: 20px;
            transition: transform 0.3s ease-in-out;
            position: fixed;
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
        .form-label {
            font-weight: 500;
            color: #333;
        }
        .form-control, .form-select {
            border-radius: var(--border-radius);
            box-shadow: none;
        }
        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: #fff;
            border-radius: var(--border-radius);
            padding: 10px 24px;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #2e59d9;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            color: #fff;
            border-radius: var(--border-radius);
            padding: 10px 24px;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn-secondary:hover {
            background: #495057;
        }
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
            <img src="{{ asset('images/logo c2c 2.png') }}" alt="Logo" style="max-width: 160px; margin: 0 auto 10px; display: block;">
            <div class="sidebar-header">Admin Panel</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
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
                    <a href="{{ route('admin.subscription_plans.index') }}" class="nav-link">
                        <i class="fas fa-file-alt"></i> Subscription Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.agent-plans.index') }}" class="nav-link active">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Create New Agent Plan</h2>
                <a href="{{ route('admin.agent-plans.index') }}" class="btn btn-secondary">Back to Plans</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.agent-plans.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Plan Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price (₹) *</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="referral_reward" class="form-label">Referral Reward (₹) *</label>
                                <input type="number" name="referral_reward" id="referral_reward" value="{{ old('referral_reward', 0) }}" step="0.01" min="0" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Duration *</label>
                                <select name="duration" id="duration" required class="form-select">
                                    <option value="">Select Duration</option>
                                    <option value="lifetime" {{ old('duration') == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                    <option value="monthly" {{ old('duration') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('duration') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="icon" class="form-label">Icon (URL or FontAwesome class)</label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="e.g., fas fa-star or https://example.com/icon.png" class="form-control">
                                <small class="text-muted">Enter FontAwesome class (e.g., fas fa-star) or image URL</small>
                            </div>
                            <div class="col-md-12 mt-4">
                                <h5>Earning Rates (₹ per login)</h5>
                            </div>
                            <div class="col-md-4">
                                <label for="rate_1_50" class="form-label">Rate for 1-50 logins *</label>
                                <input type="number" name="rate_1_50" id="rate_1_50" value="{{ old('rate_1_50') }}" step="0.01" min="0" required class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="rate_51_100" class="form-label">Rate for 51-100 logins *</label>
                                <input type="number" name="rate_51_100" id="rate_51_100" value="{{ old('rate_51_100') }}" step="0.01" min="0" required class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="rate_after_100" class="form-label">Rate after 100 logins *</label>
                                <input type="number" name="rate_after_100" id="rate_after_100" value="{{ old('rate_after_100') }}" step="0.01" min="0" required class="form-control">
                            </div>
                            <div class="col-md-12 mt-4">
                                <h5>Bonuses</h5>
                            </div>
                            <div class="col-md-4">
                                <label for="bonus_10_logins" class="form-label">Bonus at 10 logins</label>
                                <input type="text" name="bonus_10_logins" id="bonus_10_logins" value="{{ old('bonus_10_logins') }}" placeholder="e.g., Cap" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="bonus_50_logins" class="form-label">Bonus at 50 logins</label>
                                <input type="text" name="bonus_50_logins" id="bonus_50_logins" value="{{ old('bonus_50_logins') }}" placeholder="e.g., T-shirt" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="bonus_100_logins" class="form-label">Bonus at 100 logins</label>
                                <input type="text" name="bonus_100_logins" id="bonus_100_logins" value="{{ old('bonus_100_logins') }}" placeholder="e.g., Bag" class="form-control">
                            </div>
                            <div class="col-md-12 mt-4">
                                <h5>Withdrawal Settings</h5>
                            </div>
                            <div class="col-md-6">
                                <label for="min_withdrawal" class="form-label">Minimum Withdrawal (₹) *</label>
                                <input type="number" name="min_withdrawal" id="min_withdrawal" value="{{ old('min_withdrawal', 250.00) }}" step="0.01" min="0" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="max_withdrawal" class="form-label">Maximum Withdrawal (₹)</label>
                                <input type="number" name="max_withdrawal" id="max_withdrawal" value="{{ old('max_withdrawal') }}" step="0.01" min="0" class="form-control">
                                <small class="text-muted">Leave empty for unlimited</small>
                            </div>
                            <div class="col-md-12">
                                <label for="withdrawal_time" class="form-label">Withdrawal Time *</label>
                                <input type="text" name="withdrawal_time" id="withdrawal_time" value="{{ old('withdrawal_time', 'Monday to Saturday 9:00AM to 18:00PM') }}" required class="form-control">
                            </div>
                            <div class="col-md-12 mt-4">
                                <h5>Features</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="unlimited_earning" id="unlimited_earning" value="1" {{ old('unlimited_earning', true) ? 'checked' : '' }} class="form-check-input">
                                    <label for="unlimited_earning" class="form-check-label">Unlimited Earning</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="unlimited_logins" id="unlimited_logins" value="1" {{ old('unlimited_logins') ? 'checked' : '' }} class="form-check-input">
                                    <label for="unlimited_logins" class="form-check-label">Unlimited Logins</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="max_logins_per_day" class="form-label">Maximum Logins Per Day</label>
                                <input type="number" name="max_logins_per_day" id="max_logins_per_day" value="{{ old('max_logins_per_day') }}" min="1" class="form-control">
                                <small class="text-muted">Leave empty if unlimited logins is enabled</small>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                    <label for="is_active" class="form-check-label">Active Plan</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.agent-plans.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.hamburger')?.addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html> 