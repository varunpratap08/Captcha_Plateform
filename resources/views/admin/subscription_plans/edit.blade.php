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
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Form Card */
        .form-card {
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

        .form-card:hover {
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
            margin: -20px -20px 20px;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            position: relative;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            padding: 10px 40px 10px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            color: var(--text-color);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .form-group .icon {
            position: absolute;
            right: 12px;
            top: 38px;
            color: #6b7280;
            font-size: 1rem;
        }

        .form-group input[type="file"] {
            padding: 10px;
        }

        .form-group .error {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .form-group small {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 5px;
        }

        /* Earnings Section */
        .earnings-section {
            background: rgba(245, 245, 245, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 15px;
            margin: 20px 0;
        }

        .earnings-section label {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 15px;
            display: block;
        }

        .earning-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            align-items: end;
            margin-bottom: 15px;
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

        .btn-secondary {
            background: linear-gradient(90deg, #6b7280, #9ca3af);
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

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(107, 114, 128, 0.3);
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

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: flex-start;
            margin-top: 30px;
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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .earning-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .button-group {
                flex-direction: column;
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
                    <a href="{{ route('admin.subscription-plans.index') }}" class="nav-link {{ Route::is('admin.subscription-plans.*') ? 'active' : '' }}">
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
                <!-- Form Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-edit mr-2"></i> Edit Subscription Plan
                        </h2>
                    </div>
                    <form action="{{ route('admin.subscription-plans.update', $subscription_plan) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <!-- Plan Name -->
                            <div class="form-group">
                                <label for="name">Plan Name</label>
                                <input type="text" name="name" id="name" class="@error('name') error @enderror" value="{{ old('name', $subscription_plan->name) }}" required>
                                <i class="fas fa-signature icon"></i>
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Captcha per Day -->
                            <div class="form-group">
                                <label for="captcha_per_day">Captcha per Day</label>
                                <input type="text" name="captcha_per_day" id="captcha_per_day" class="@error('captcha_per_day') error @enderror" value="{{ old('captcha_per_day', $subscription_plan->captcha_per_day) }}" required>
                                <i class="fas fa-shield-alt icon"></i>
                                @error('captcha_per_day')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Minimum Withdrawal Limit -->
                            <div class="form-group">
                                <label for="min_withdrawal_limit">Minimum Withdrawal Limit</label>
                                <input type="number" name="min_withdrawal_limit" id="min_withdrawal_limit" class="@error('min_withdrawal_limit') error @enderror" value="{{ old('min_withdrawal_limit', $subscription_plan->min_withdrawal_limit) }}">
                                <i class="fas fa-money-bill-wave icon"></i>
                                @error('min_withdrawal_limit')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="form-group">
                                <label for="cost">Price (₹)</label>
                                <input type="number" step="0.01" name="cost" id="cost" class="@error('cost') error @enderror" value="{{ old('cost', $subscription_plan->cost) }}" required>
                                <i class="fas fa-rupee-sign icon"></i>
                                @error('cost')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Plan Type -->
                            <div class="form-group">
                                <label for="plan_type">Plan Type</label>
                                <select name="plan_type" id="plan_type" class="@error('plan_type') error @enderror">
                                    <option value="">Select Type</option>
                                    <option value="Monthly" {{ old('plan_type', $subscription_plan->plan_type) == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="Yearly" {{ old('plan_type', $subscription_plan->plan_type) == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="Unlimited" {{ old('plan_type', $subscription_plan->plan_type) == 'Unlimited' ? 'selected' : '' }}>Unlimited</option>
                                </select>
                                <i class="fas fa-clock icon"></i>
                                @error('plan_type')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Icon -->
                            <div class="form-group">
                                <label for="icon">Icon (Optional)</label>
                                <input type="text" name="icon" id="icon" class="@error('icon') error @enderror" value="{{ old('icon', $subscription_plan->icon) }}" placeholder="e.g., fas fa-star">
                                <i class="fas fa-icons icon"></i>
                                <small>Use Font Awesome icon classes (e.g., fas fa-star)</small>
                                @error('icon')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" name="image" id="image" class="@error('image') error @enderror">
                                @if($subscription_plan->image)
                                    <small>Current image: {{ basename($subscription_plan->image) }}</small>
                                @endif
                                @error('image')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Captcha Limit -->
                            <div class="form-group">
                                <label for="caption_limit">Captcha Limit</label>
                                <input type="text" name="caption_limit" id="caption_limit" class="@error('caption_limit') error @enderror" value="{{ old('caption_limit', $subscription_plan->caption_limit) }}">
                                <i class="fas fa-lock icon"></i>
                                @error('caption_limit')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Minimum Daily Earning -->
                            <div class="form-group">
                                <label for="min_daily_earning">Minimum Daily Earning</label>
                                <input type="number" name="min_daily_earning" id="min_daily_earning" class="@error('min_daily_earning') error @enderror" value="{{ old('min_daily_earning', $subscription_plan->min_daily_earning) }}">
                                <i class="fas fa-coins icon"></i>
                                @error('min_daily_earning')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Earning Type -->
                            <div class="form-group">
                                <label for="earning_type">Earning Type</label>
                                <input type="text" name="earning_type" id="earning_type" class="@error('earning_type') error @enderror" value="{{ old('earning_type', $subscription_plan->earning_type) }}">
                                <i class="fas fa-money-check-alt icon"></i>
                                @error('earning_type')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Earnings Section -->
                        <div class="earnings-section">
                            <label>Earnings (Add multiple ranges and amounts)</label>
                            <div id="earnings-list">
                                @php
                                    $earnings = json_decode($subscription_plan->earnings, true) ?: [];
                                @endphp
                                @forelse($earnings as $index => $earning)
                                    <div class="earning-row">
                                        <div class="form-group">
                                            <input type="text" name="earnings[{{ $index }}][range]" class="@error('earnings.'.$index.'.range') error @enderror" placeholder="Range (e.g. 1-50)" value="{{ $earning['range'] ?? '' }}">
                                            @error('earnings.'.$index.'.range')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="number" name="earnings[{{ $index }}][amount]" class="@error('earnings.'.$index.'.amount') error @enderror" placeholder="Amount (e.g. 5)" value="{{ $earning['amount'] ?? '' }}">
                                            @error('earnings.'.$index.'.amount')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn-danger btn-sm remove-earning">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="earning-row">
                                        <div class="form-group">
                                            <input type="text" name="earnings[0][range]" class="@error('earnings.0.range') error @enderror" placeholder="Range (e.g. 1-50)">
                                            @error('earnings.0.range')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="number" name="earnings[0][amount]" class="@error('earnings.0.amount') error @enderror" placeholder="Amount (e.g. 5)">
                                            @error('earnings.0.amount')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn-danger btn-sm remove-earning">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn-secondary btn-sm" id="add-earning">
                                <i class="fas fa-plus"></i> Add More
                            </button>
                        </div>

                        <!-- Buttons -->
                        <div class="button-group">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Update Plan
                            </button>
                            <a href="{{ route('admin.subscription-plans.index') }}" class="btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        let earningIndex = {{ count($earnings) }};
        document.getElementById('add-earning').addEventListener('click', function() {
            const earningsList = document.getElementById('earnings-list');
            const row = document.createElement('div');
            row.className = 'earning-row';
            row.innerHTML = `
                <div class="form-group">
                    <input type="text" name="earnings[${earningIndex}][range]" class="form-control" placeholder="Range (e.g. 1-50)" />
                </div>
                <div class="form-group">
                    <input type="number" name="earnings[${earningIndex}][amount]" class="form-control" placeholder="Amount (e.g. 5)" />
                </div>
                <div class="form-group">
                    <button type="button" class="btn-danger btn-sm remove-earning">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            earningsList.appendChild(row);
            earningIndex++;
        });

        document.getElementById('earnings-list').addEventListener('click', function(e) {
            if (e.target.closest('.remove-earning')) {
                e.target.closest('.earning-row').remove();
            }
        });
    </script>
</body>
</html> 