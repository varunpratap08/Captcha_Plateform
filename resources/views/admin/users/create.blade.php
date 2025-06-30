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

        /* Error Alert */
        .alert-danger {
            max-width: 1200px;
            margin: 0 auto 20px;
            background: rgba(239, 68, 68, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 15px;
            color: white;
            position: relative;
            animation: slideIn 0.5s ease-in;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-danger .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .alert-danger .close:hover {
            transform: scale(1.2);
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
        .form-group .custom-file-input {
            padding: 10px 40px 10px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            color: var(--text-color);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group .custom-file-input:focus {
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

        .form-group .custom-file {
            position: relative;
        }

        .form-group .custom-file-label {
            padding: 10px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .form-group .custom-file-input:focus + .custom-file-label {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Toggle Switches */
        .custom-switch {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-switch input {
            display: none;
        }

        .custom-switch .toggle {
            width: 44px;
            height: 24px;
            background: #d1d5db;
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .custom-switch .toggle::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .custom-switch input:checked + .toggle {
            background: var(--success-color);
        }

        .custom-switch input:checked + .toggle::before {
            transform: translateX(20px);
        }

        .custom-switch label {
            font-size: 0.9rem;
            color: var(--text-color);
        }

        /* Checkboxes */
        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .custom-checkbox input {
            width: 16px;
            height: 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .custom-checkbox input:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-checkbox label {
            font-size: 0.9rem;
            color: var(--text-color);
            cursor: pointer;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
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

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-card,
            .alert-danger {
                max-width: 100%;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .button-group {
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
                <!-- Header -->
                <div class="header">
                    <h1>
                        <i class="fas fa-user-plus mr-2"></i> Create New User
                    </h1>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Users
                    </a>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <span class="close">&times;</span>
                    </div>
                @endif

                <!-- Form Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-info-circle mr-2"></i> User Information
                        </h2>
                    </div>
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <!-- Full Name -->
                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="@error('name') error @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                <i class="fas fa-user icon"></i>
                                @error('name')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="@error('email') error @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                <i class="fas fa-envelope icon"></i>
                                @error('email')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="@error('phone') error @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                <i class="fas fa-phone icon"></i>
                                @error('phone')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Referral Code -->
                            <div class="form-group">
                                <label for="referral_code">Referral Code (Optional)</label>
                                <input type="text" class="@error('referral_code') error @enderror" id="referral_code" name="referral_code" value="{{ old('referral_code') }}">
                                <i class="fas fa-code icon"></i>
                                <small>If left blank, a unique code will be generated</small>
                                @error('referral_code')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="@error('password') error @enderror" id="password" name="password" required>
                                <i class="fas fa-lock icon"></i>
                                @error('password')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required>
                                <i class="fas fa-lock icon"></i>
                            </div>

                            <!-- Profile Photo -->
                            <div class="form-group">
                                <label for="profile_photo">Profile Photo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile_photo') error @enderror" id="profile_photo" name="profile_photo">
                                    <label class="custom-file-label" for="profile_photo">Choose file</label>
                                    @error('profile_photo')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Account Status -->
                            <div class="form-group">
                                <label>Account Status</label>
                                <div class="custom-switch">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                                    <span class="toggle"></span>
                                    <label for="is_active" id="is_active_label">Active</label>
                                </div>
                                <small>Deactivating will prevent the user from logging in.</small>
                            </div>

                            <!-- Email Verification Status -->
                            <div class="form-group">
                                <label>Email Verification Status</label>
                                <div class="custom-switch">
                                    <input type="checkbox" id="email_verified" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : 'checked' }}>
                                    <span class="toggle"></span>
                                    <label for="email_verified" id="email_verified_label">Email Verified</label>
                                </div>
                            </div>

                            <!-- User Roles -->
                            <div class="form-group col-span-2">
                                <label>User Roles <span class="text-danger">*</span></label>
                                @foreach($roles as $id => $name)
                                    <div class="custom-checkbox">
                                        <input type="checkbox" id="role_{{ $id }}" name="roles[]" value="{{ $id }}" {{ in_array($id, old('roles', [])) || $loop->first ? 'checked' : '' }}>
                                        <label for="role_{{ $id }}">{{ $name }}</label>
                                    </div>
                                @endforeach
                                @error('roles')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="button-group">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <script>
        // Update the file input label with the selected filename
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        // Toggle switch labels
        document.getElementById('is_active').addEventListener('change', function() {
            const label = document.getElementById('is_active_label');
            label.textContent = this.checked ? 'Active' : 'Inactive';
        });

        document.getElementById('email_verified').addEventListener('change', function() {
            const label = document.getElementById('email_verified_label');
            label.textContent = this.checked ? 'Email Verified' : 'Email Not Verified';
        });

        // Close error alert
        document.querySelectorAll('.alert-danger .close').forEach(button => {
            button.addEventListener('click', () => {
                button.parentElement.style.display = 'none';
            });
        });
    </script>
</body>
</html>
