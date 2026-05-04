<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Not logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Students not allowed in staff routes
        if ($user->role === 'student') {
            return redirect('/dashboard');
        }

        // If roles specified, check if user has one of them
        if (count($roles) > 0) {
            $userRole = trim($user->role);
            $allowed  = array_map('trim', $roles);

            if (!in_array($userRole, $allowed)) {
                abort(403, 'Unauthorized — required: ' . implode(', ', $allowed) . ' | your role: ' . $userRole);
            }
        }

        return $next($request);
    }
}