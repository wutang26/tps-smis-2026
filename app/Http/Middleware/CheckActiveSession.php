<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('session_id')) {
            Session::put('session_id', 'default_session');
        }

        return $next($request);
    }
}
