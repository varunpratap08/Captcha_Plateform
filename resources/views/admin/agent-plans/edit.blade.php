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
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --background-color: #f8f9fc;
            --text-color: #333;
            --sidebar-bg: rgba(255, 255, 255, 0.95);
            --input-bg: rgba(255, 255, 255, 0.1);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --accent-color: #60a5fa;
            --error-color: #f87171;
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
            padding: 0 30px 30px 40px;
            margin-left: 250px;
            background: #e6f7ff;
            border-radius: var(--border-radius);
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Form Styles */
        .form-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .form-header {
            margin-bottom: 20px;
        }

        .form-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .form-header p {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-color);
            transition: transform 0.2s ease;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            background: var(--input-bg);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            padding: 12px;
            color: var(--text-color);
            font-size: 0.9rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.3);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group.checkbox {
            flex-direction: row;
            align-items: center;
            gap: 10px;
        }

        .form-group.checkbox input {
            accent-color: var(--accent-color);
            width: 20px;
            height: 20px;
        }

        .form-group .error-text {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            transition: background 0.3s, transform 0.2s;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            transition: background 0.3s, transform 0.2s;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background: #9ca3af;
            transform: translateY(-2px);
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

            .form-container {
                max-width: 100%;
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
                <div class="form-container">
                    <div class="form-header">
                        <h1>Edit Agent Plan</h1>
                        <p class="mt-2">Update the details of the agent subscription plan</p>
                    </div>
                    <form action="{{ route('admin.agent-plans.update', $agentPlan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Plan Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $agentPlan->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="icon">Icon (URL or Font Awesome Class)</label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon', $agentPlan->icon) }}" class="form-control @error('icon') is-invalid @enderror">
                                @error('icon')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Price (₹)</label>
                                <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $agentPlan->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                                @error('price')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <select name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" required>
                                    <option value="monthly" {{ old('duration', $agentPlan->duration) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('duration', $agentPlan->duration) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="lifetime" {{ old('duration', $agentPlan->duration) == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                </select>
                                @error('duration')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rate_1_50">Earning Rate (1-50) (₹)</label>
                                <input type="number" name="rate_1_50" id="rate_1_50" step="0.01" value="{{ old('rate_1_50', $agentPlan->rate_1_50) }}" class="form-control @error('rate_1_50') is-invalid @enderror" required>
                                @error('rate_1_50')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rate_51_100">Earning Rate (51-100) (₹)</label>
                                <input type="number" name="rate_51_100" id="rate_51_100" step="0.01" value="{{ old('rate_51_100', $agentPlan->rate_51_100) }}" class="form-control @error('rate_51_100') is-invalid @enderror" required>
                                @error('rate_51_100')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="rate_after_100">Earning Rate (100+) (₹)</label>
                                <input type="number" name="rate_after_100" id="rate_after_100" step="0.01" value="{{ old('rate_after_100', $agentPlan->rate_after_100) }}" class="form-control @error('rate_after_100') is-invalid @enderror" required>
                                @error('rate_after_100')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="referral_reward">Referral Reward (₹)</label>
                                <input type="number" name="referral_reward" id="referral_reward" step="0.01" value="{{ old('referral_reward', $agentPlan->referral_reward) }}" class="form-control @error('referral_reward') is-invalid @enderror" required>
                                @error('referral_reward')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $agentPlan->description) }}</textarea>
                                @error('description')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group checkbox">
                                <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $agentPlan->is_active) ? 'checked' : '' }}>
                                <label for="is_active">Active</label>
                                @error('is_active')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end gap-4 mt-6">
                            <a href="{{ route('admin.agent-plans.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Plan</button>
                        </div>
                    </form>
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

