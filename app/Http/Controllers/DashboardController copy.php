<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionProgramme;
use App\Models\Student; 
use App\Models\Course;
use App\Models\Attendence;
use App\Models\Beat;
use App\Models\Patient;
use App\Models\Staff;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'check_active_session']);
    }

    public function index(Request $request)
    {
        $beatStudents = 0;
         // Check if a session ID has been submitted
        if (request()->has('session_id')) {
        // Store the selected session ID in the session
        session(['selected_session' => request()->session_id]);
        }
        // Get the selected session ID from the session
        $selectedSessionId = session('selected_session');
        $pending_message = session('pending_message');

        if (auth()->user()->hasRole('Student')) {
            return view('dashboard.student_dashboard', compact('pending_message', 'selectedSessionId'));
        } else {
            $denttotalCount = Student::where('session_programme_id', $selectedSessionId)->count();
            $dentpresentCount = Student::where('session_programme_id', $selectedSessionId)->where('beat_status', 1)->count();
            $beats = Beat::where('date', Carbon::today()->toDateString())->get();
            $filteredBeats = $beats->filter(function ($beat) use ($selectedSessionId) {
                $studentIds = json_decode($beat->student_ids, true);
                return Student::whereIn('id', $studentIds)->where('session_programme_id', $selectedSessionId)->exists();
            });
            $totalStudentsInBeats = $filteredBeats->sum(function ($beat) {
                return count(json_decode($beat->student_ids, true));
            });
            $beatStudents = $totalStudentsInBeats;
            $patientsCount = Patient::where('created_at', Carbon::today()->toDateString())->count('student_id');
            $staffsCount = Staff::count('forceNumber');
            $beatStudentPercentage = $denttotalCount > 0 ? ($totalStudentsInBeats / $denttotalCount) * 100 : 0;

            return view('dashboard.dashboard', compact('selectedSessionId', 'denttotalCount', 'dentpresentCount', 'beatStudents', 'patientsCount', 'staffsCount', 'beatStudentPercentage'));
        }
    }

    public function getContent(Request $request)
    {
        $sessionProgrammeId = session('selected_session'); // Use session variable to get the selected session programme ID
        $denttotalCount = Student::where('session_programme_id', $sessionProgrammeId)->count();
        $dentpresentCount = Student::where('session_programme_id', $sessionProgrammeId)->where('beat_status', 1)->count();
        $beats = Beat::where('date', Carbon::today()->toDateString())->get();
        $filteredBeats = $beats->filter(function ($beat) use ($sessionProgrammeId) {
            $studentIds = json_decode($beat->student_ids, true);
            return Student::whereIn('id', $studentIds)->where('session_programme_id', $sessionProgrammeId)->exists();
        });
        $totalStudentsInBeats = $filteredBeats->sum(function ($beat) {
            return count(json_decode($beat->student_ids, true));
        });
        $beatsCount = $totalStudentsInBeats;
        $patientsCount = Patient::where('created_at', Carbon::today()->toDateString())->count('student_id');
        $staffsCount = Staff::count('forceNumber');
        $beatStudentPercentage = $denttotalCount > 0 ? ($totalStudentsInBeats / $denttotalCount) * 100 : 0;

        return view('dashboard.partials.dashboard_content', compact('denttotalCount', 'dentpresentCount', 'beatsCount', 'patientsCount', 'staffsCount', 'beatStudentPercentage'));
    }

    public function indexnew()
    {
        // Get the weekly attendance data
        $weeklyAttendance = Attendence::selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(present) as total_present, SUM(absent) as total_absent')
        ->groupBy('year', 'week')
        ->orderBy('year', 'asc')
        ->orderBy('year', 'asc')
        ->get();

        // Calculate the comparison with the previous week 
        $weeklyComparison = []; 
        foreach ($weeklyAttendance as $index => $week) {
             if ($index > 0) {
                 $previousWeek = $weeklyAttendance[$index - 1]; 
                 $weeklyComparison[] = [ 
                    'year' => $week->year, 
                    'week' => $week->week, 
                    'present_difference' => $week->total_present - $previousWeek->total_present, 
                    'absent_difference' => $week->total_absent - $previousWeek->total_absent, 
                ]; 
            } 
        }

        // Get the count of current programs 
        $currentProgramsCount = SessionProgramme::where('is_current', 1)->count(); 
        // Get the count of inactive programs 
        $inactiveProgramsCount = SessionProgramme::where('is_current', 0)->count(); 
        // Get program details 
        $programmes = SessionProgramme::where('is_current', 1)->get(); 
        // Additional data for graphs here

        
        return view('dashboard.default_dashboard', compact('currentProgramsCount', 'inactiveProgramsCount', 'programmes', 'weeklyAttendance', 'weeklyComparison')); 
    }
}
