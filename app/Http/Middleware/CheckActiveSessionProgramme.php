<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckActiveSessionProgramme
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    
    public function handle(Request $request, Closure $next)
    {
        $sessionProgrammeId = session('selected_session');
        $sessionProgramme = DB::table('session_programmes')->find($sessionProgrammeId);

        if (!$sessionProgramme) {
            // Prevent redirect loop by checking the current URL
            if ($request->path() !== '/') {
                return redirect('/')->with('error', 'The selected session programme is not valid.');
            }
        }

        return $next($request);
    }

}
