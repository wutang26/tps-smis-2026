<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseInstructor;

class CheckCourseInstructor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $courseId = $request->route('course');

        // Check if the user is assigned to the course
        $isInstructor = CourseInstructor::where('user_id', $user->id)
            ->whereHas('programmeCourseSemester', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->exists();

        if (!$isInstructor) {
            return redirect()->back()->with('error', 'You do not have access to this course or coursework.');
        }

        return $next($request);
    }
}