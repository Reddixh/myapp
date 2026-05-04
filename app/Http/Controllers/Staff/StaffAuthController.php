<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role !== 'student') {
            return redirect('/staff/dashboard');
        }
        return view('staff.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Logout any student session first
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
         $user = Auth::user();
         if ($user->role === 'student') {
         Auth::logout();
         return back()->withErrors([
            'email' => 'This portal is for staff only.',
        ]);
        }
        $request->session()->regenerate();

        return match($user->role) {
        'librarian' => redirect('/staff/dashboard'),
        'dean'      => redirect('/dean/dashboard'),
        default     => redirect('/staff/dashboard'),
       };
       }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/staff/login');
    }
}