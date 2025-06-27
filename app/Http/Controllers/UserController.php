<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $users = User::with('roles')
                ->latest()
                ->paginate(10);
                
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in UserController@index: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Return a user-friendly error message
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading the users. Please try again.');
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            'referral_code' => 'nullable|string|max:255|unique:users,referral_code',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
                'is_verified' => true,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'email_verified_at' => $request->has('email_verified') ? now() : null,
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $userData['profile_photo_path'] = $path;
            }

            // Handle referral code
            if (!empty($validated['referral_code'])) {
                $userData['referral_code'] = $validated['referral_code'];
            } else {
                // Generate a unique referral code if not provided
                $userData['referral_code'] = $this->generateUniqueReferralCode();
            }

            $user = User::create($userData);

            // Assign roles
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $user->syncRoles($roles);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully');
                
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->withInput()
                ->with('error', 'Error creating user. Please try again.');
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'id');
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            'remove_photo' => 'nullable|boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_active' => $request->has('is_active') ? 1 : 0,
            ];
            
            // Update email verification status
            if ($request->has('email_verified')) {
                $updateData['email_verified_at'] = $user->email_verified_at ?? now();
            } else {
                $updateData['email_verified_at'] = null;
            }
            
            // Update password if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    \Storage::disk('public')->delete($user->profile_photo_path);
                }
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $updateData['profile_photo_path'] = $path;
            } elseif ($request->has('remove_photo') && $user->profile_photo_path) {
                // Remove profile photo if requested
                \Storage::disk('public')->delete($user->profile_photo_path);
                $updateData['profile_photo_path'] = null;
            }
            
            $user->update($updateData);
            
            // Sync roles
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $user->syncRoles($roles);

            return redirect()->route('admin.users.show', $user->id)
                ->with('success', 'User updated successfully');
                
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->withInput()
                ->with('error', 'Error updating user. Please try again.');
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting own account
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'You cannot delete your own account.');
            }
            
            // Delete profile photo if exists
            if ($user->profile_photo_path) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Detach all roles before deleting
            $user->roles()->detach();
            
            $user->delete();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->route('admin.users.index')
                ->with('error', 'Error deleting user. Please try again.');
        }
    }
    
    /**
     * Generate a unique referral code
     * 
     * @return string
     */
    protected function generateUniqueReferralCode()
    {
        $code = strtoupper(\Str::random(8));
        
        // Check if code already exists
        while (User::where('referral_code', $code)->exists()) {
            $code = strtoupper(\Str::random(8));
        }
        
        return $code;
    }
}