<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'student') {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'reg_number' => 'required|string',
            'password'   => 'required|string',
        ]);

        // Logout any staff session first
        Auth::guard('web')->logout();

        $credentials = [
            'reg_number' => $request->reg_number,
            'password'   => $request->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            if ($user->role !== 'student') {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'reg_number' => 'This portal is for students only.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'reg_number' => 'Invalid registration number or password.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'reg_number' => 'required|string',
            'email'      => 'required|email',
        ]);

        return back()->with('status', 'If your account exists, a reset link has been sent to your email.');
    }
}