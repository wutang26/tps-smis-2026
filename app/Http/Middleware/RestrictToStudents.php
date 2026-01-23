<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictToStudents
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->role === 'Student') {
            return $next($request); // Allow access
        }

        return response()->view('errors.access-denied', [], 403);
    }
}
