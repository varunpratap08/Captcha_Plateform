<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Edit Agent</title>
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
        <h2>Edit Agent</h2>
        <form action="{{ route('admin.agents.update', $agent) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $agent->name) }}" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $agent->phone_number) }}" required>
                @error('phone_number')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $agent->date_of_birth) }}">
                @error('date_of_birth')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $agent->email) }}">
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="upi_id">UPI ID</label>
                <input type="text" name="upi_id" id="upi_id" value="{{ old('upi_id', $agent->upi_id) }}">
                @error('upi_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" value="{{ old('address', $agent->address) }}">
                @error('address')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" value="{{ old('city', $agent->city) }}">
                @error('city')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input type="text" name="state" id="state" value="{{ old('state', $agent->state) }}">
                @error('state')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="pincode">Pincode</label>
                <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $agent->pincode) }}">
                @error('pincode')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="aadhar_number">Aadhar Number</label>
                <input type="text" name="aadhar_number" id="aadhar_number" value="{{ old('aadhar_number', $agent->aadhar_number) }}">
                @error('aadhar_number')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="pan_number">PAN Number</label>
                <input type="text" name="pan_number" id="pan_number" value="{{ old('pan_number', $agent->pan_number) }}">
                @error('pan_number')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="gst_number">GST Number</label>
                <input type="text" name="gst_number" id="gst_number" value="{{ old('gst_number', $agent->gst_number) }}">
                @error('gst_number')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio">{{ old('bio', $agent->bio) }}</textarea>
                @error('bio')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*">
                @error('profile_image')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="bank_account_number">Bank Account Number</label>
                <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $agent->bank_account_number) }}">
                @error('bank_account_number')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="ifsc_code">IFSC Code</label>
                <input type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code', $agent->ifsc_code) }}">
                @error('ifsc_code')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="account_holder_name">Account Holder Name</label>
                <input type="text" name="account_holder_name" id="account_holder_name" value="{{ old('account_holder_name', $agent->account_holder_name) }}">
                @error('account_holder_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="active" {{ old('status', $agent->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $agent->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn-primary">Update Agent</button>
        </form>
    </div>
</body>
</html> 