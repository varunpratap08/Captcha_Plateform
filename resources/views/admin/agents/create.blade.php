
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Agent</title>
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
            margin-left: 250px;
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
        .alert-success, .alert-danger {
            max-width: 600px;
            margin: 0 auto 20px;
            padding: 15px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            position: relative;
            animation: slideIn 0.5s ease-in;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.95);
            backdrop-filter: blur(10px);
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.95);
            backdrop-filter: blur(10px);
            color: white;
        }

        .alert-success .close, .alert-danger .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .alert-success .close:hover, .alert-danger .close:hover {
            transform: scale(1.2);
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Form Card */
        .form-card {
            max-width: 600px;
            margin: 0 auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 24px;
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
            margin: -24px -24px 20px;
        }

        .card-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-color);
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 8px;
            display: block;
        }

        .form-group input,
        .form-group select {
            width: 80%;
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

        .form-group .error {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 5px;
        }

        /* File Input */
        .form-group input[type="file"] {
            padding: 10px 16px;
        }

        .form-group input[type="file"]::-webkit-file-upload-button {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 8px 16px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            backdrop-filter: blur(10px);
        }

        .form-group input[type="file"]::-webkit-file-upload-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        /* Button */
        .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            width: 100%;
            justify-content: center;
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
            display: flex;
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

            .form-card,
            .alert-success,
            .alert-danger {
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
                        <i class="fas fa-user-plus mr-2"></i> Create Agent
                    </h1>
                    <a href="{{ route('admin.agents.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Agents
                    </a>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <span class="close">×</span>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <span class="close">×</span>
                    </div>
                @endif

                <!-- Form Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h2>Create Agent</h2>
                    </div>
                    <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="@error('name') error @enderror">
                           
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required class="@error('phone_number') error @enderror">
                            
                            @error('phone_number')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="@error('date_of_birth') error @enderror">
                            
                            @error('date_of_birth')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="@error('email') error @enderror">
                           
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="upi_id">UPI ID</label>
                            <input type="text" id="upi_id" name="upi_id" value="{{ old('upi_id') }}" class="@error('upi_id') error @enderror">
                           
                            @error('upi_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="profile_image">Profile Image</label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="@error('profile_image') error @enderror">
                            @error('profile_image')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="@error('status') error @enderror">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i> Create Agent
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Hamburger menu toggle
        document.querySelector('.hamburger').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Close alerts
        document.querySelectorAll('.alert-success .close, .alert-danger .close').forEach(button => {
            button.addEventListener('click', () => {
                button.parentElement.style.display = 'none';
            });
        });
    </script>
</body>
</html>
