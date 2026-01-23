<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ensure the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redirect non-admin users
        return redirect('/')->with('error', 'Access denied! Only admins can access this page.');
    }

}
