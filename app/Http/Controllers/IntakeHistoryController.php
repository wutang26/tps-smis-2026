<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Company;
use App\Models\SessionProgramme;
use App\Models\TerminationReason;

class IntakeHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        // Get selected session ID from session or default to 1
        $selectedSessionId = session('selected_session') ?? 1;
        
        // Aggregate summary counts
        $stats = [
            'totalEnrolled'   => Student::where('session_programme_id', $selectedSessionId)->get(),
            'currentStudents' => Student::where('session_programme_id', $selectedSessionId)->where('enrollment_status', 1)->get(),
            'dismissed'       => Student::where('session_programme_id', $selectedSessionId)->where('enrollment_status', 0)->get(),
            'verified'        => Student::where('session_programme_id', $selectedSessionId)->where('status', 'approved')->get(),
        ];

        $students = Student::where('session_programme_id', $selectedSessionId)->paginate(10);

        // Get unique, non-null entry regions for students in the selected session
        $regions = Student::where('session_programme_id', $selectedSessionId)
                            ->whereNotNull('entry_region')
                            ->selectRaw('DISTINCT UPPER(TRIM(entry_region)) as entry_region')
                            ->orderBy('entry_region')
                            ->pluck('entry_region')
                            ->values();
        $companies = Company::where('campus_id', 1)
                            ->orderBy('name')
                            ->get();

        $terminationReasons = TerminationReason::all()->groupBy('category');
        $active_session = SessionProgramme::where('id', $selectedSessionId)->pluck('session_programme_name')->first();


        return view('students.intake_history.index', compact('stats', 'students', 'regions', 'companies', 'terminationReasons', 'active_session'));
    }

    
    public function filterStudents(Request $request)
    {
        $selectedSessionId = session('selected_session') ?? 1;
        $type = $request->get('type');

        if (! in_array($type, ['totalEnrolled', 'currentStudents', 'dismissed', 'verified'])) {
            $type = 'totalEnrolled';
        }

        $query = Student::where('session_programme_id', $selectedSessionId);

        // 🎯 Card-specific base filters
        switch ($type) {
            case 'currentStudents':
                $query->where('enrollment_status', 1);
                break;
            case 'dismissed':
                $query->where('enrollment_status', 0);
                break;
            case 'verified':
                $query->where('status', 'approved');
                break;
            case 'totalEnrolled':
            default:
                // No extra filter
                break;
        }

        // 🔍 Apply shared filters
        if ($request->filled('entry_region')) {
            $regions = $request->input('entry_region');
            $regions = is_array($regions) ? array_filter($regions) : [$regions];
            if (count($regions)) {
                $query->whereIn('entry_region', $regions);
            }
        }

        if ($request->filled('education_level')) {
            $query->where('education_level', $request->education_level);
        }

        if ($request->filled('age_range')) {
            $range = $request->age_range;

            if (str_ends_with($range, '+')) {
                $minAge = (int)rtrim($range, '+');
                $query->where('age', '>=', $minAge);
            } elseif (str_contains($range, '-')) {
                [$minAge, $maxAge] = explode('-', $range);
                $query->whereBetween('age', [(int)$minAge, (int)$maxAge]);
            }
        }


        // 🏢 Company filter (for current, dismissed, verified)
        if (in_array($type, ['currentStudents', 'dismissed', 'verified']) && $request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 📄 Dismissal reason (only for dismissed)
        if ($type === 'dismissed' && $request->filled('dismissal_reason')) {
            $query->where('termination_reason_id', $request->dismissal_reason);
        }

        // 📊 Summary counts scoped to current type
        $baseQuery = clone $query;

        $summary = match ($type) {
            'currentStudents' => [
                'total'     => $baseQuery->count(),
                'active'    => $baseQuery->count(),
                'dismissed' => 0,
                'verified'  => (clone $baseQuery)->where('status', 'approved')->count(),
            ],
            'dismissed' => [
                'total'     => $baseQuery->count(),
                'active'    => 0,
                'dismissed' => $baseQuery->count(),
                'verified'  => (clone $baseQuery)->where('status', 'approved')->count(),
            ],
            'verified' => [
                'total'     => $baseQuery->count(),
                'active'    => (clone $baseQuery)->where('enrollment_status', 1)->count(),
                'dismissed' => (clone $baseQuery)->where('enrollment_status', 0)->count(),
                'verified'  => $baseQuery->count(),
            ],
            default => [
                'total'     => $baseQuery->count(),
                'active'    => (clone $baseQuery)->where('enrollment_status', 1)->count(),
                'dismissed' => (clone $baseQuery)->where('enrollment_status', 0)->count(),
                'verified'  => (clone $baseQuery)->where('status', 'approved')->count(),
            ],
        };

        $students = $query->paginate(10);

        return response()->json([
            'students' => $students,
            'summary'  => $summary,
        ]);
    }



    public function getRegionsBySession(Request $request)
    {
        $sessionId = $request->get('session_id') ?? 1;

        $regions = Student::where('session_programme_id', $sessionId)
            ->whereNotNull('entry_region')
            ->pluck('entry_region')
            ->unique()
            ->sort()
            ->values();

        return response()->json($regions);
    }

   /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
