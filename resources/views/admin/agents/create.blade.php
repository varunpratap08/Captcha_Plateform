<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Create Agent</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6fb;
            color: #1e293b;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            padding: 32px 24px;
        }
        h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 24px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }
        input, textarea {
            width: 100%;
            padding: 8px 12px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            background: #f8fafc;
            margin-right: 10px;
        }
        .btn-primary {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .error {
            color: #ef4444;
            font-size: 0.95em;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))
            <div style="background: #22c55e; color: #fff; padding: 14px 24px; border-radius: 8px; margin-bottom: 18px; font-weight: 500; font-size: 1.1rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        <h2>Create Agent</h2>
        <form action="{{ route('admin.agents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required>
                @error('phone_number')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}">
                @error('date_of_birth')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
           
            <div class="form-group">
                <label for="upi_id">UPI ID</label>
                <input type="text" name="upi_id" id="upi_id" value="{{ old('upi_id') }}">
                @error('upi_id')<div class="error">{{ $message }}</div>@enderror
            </div>
           


            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*">
                @error('profile_image')<div class="error">{{ $message }}</div>@enderror
            </div>
           
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn-primary">Create Agent</button>
        </form>
    </div>
</body>
</html> 