<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        // Removed jwt.admin middleware to allow access for all authenticated users/agents
    }

    public function index()
    {
        return User::with('roles')->paginate(10);
    }

    /**
     * Get a list of all users (for contact matching)
     * GET /api/v1/users/list
     */
    public function list(Request $request)
    {
        $users = \App\Models\User::select('id', 'name', 'phone', 'profile_photo_path')->get();
        $users = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'profile_photo_url' => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null,
            ];
        });
        return response()->json(['status' => 'success', 'users' => $users]);
    }
}