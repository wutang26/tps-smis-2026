<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SessionProgramme;
use Illuminate\Support\Facades\Session;


class SessionProgrammeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if ($request->has('session_id')) {
        //     $sessionProgramme = SessionProgramme::find($request->input('session_id'));
        //     session(['selected_session' => $sessionProgramme]);
        // }else{
        //     return response()->json(['error' => 'Session ID is required'], 400);
        // }

        // Check if the session key exists or set it
        if (!Session::has('session_id')) {
            Session::put('session_id', 'default_session');
        } 

        return $next($request);
    }
}
