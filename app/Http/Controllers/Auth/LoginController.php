<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->intended('/admin/dashboard');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->hasRole('admin')) {
                $token = JWTAuth::fromUser($user);
                
                // Store the token in the session for web requests
                $request->session()->put('jwt_token', $token);
                
                // Set the token in a cookie for API requests
                return redirect()->intended('/admin/dashboard')
                    ->withCookie(cookie('jwt_token', $token, 60 * 24, null, null, false, true));
            }
            
            // If user doesn't have admin role, log them out
            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have admin access.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('jwt_token');
        return redirect('/login')
            ->withCookie(cookie()->forget('jwt_token'));
    }
}
