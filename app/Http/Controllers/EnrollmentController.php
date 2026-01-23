<?php
namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validatedData = $request->validate([
            'course_id'            => 'required|exists:courses,id',
            'semester_id'          => 'required|exists:semesters,id',
            'session_programme_id' => 'required|exists:session_programmes,id',
        ]);

        $exists = Enrollment::where('course_id', $validatedData['course_id'])
            ->where('semester_id', $validatedData['semester_id'])
            ->where('session_programme_id', $validatedData['session_programme_id'])
            ->exists();

        if ($exists) {
            return back()->with([
                'info' => 'You are already enrolled in this course for the selected session and semester.',
            ])->withInput();
        }

        Enrollment::create([
            'course_id'            => $request->course_id,
            'semester_id'          => $request->semester_id,
            'session_programme_id' => $request->session_programme_id,
            'enrollment_date'      => now(),
        ]);

        return redirect()->back()->with('success', 'Session enrolled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id, AuditLoggerService $auditLogger)
    {
        $enrollment = Enrollment::find($id);
        $enrollmentSnapshot = clone $enrollment;
        $enrollment->delete();

        $auditLogger->logAction([
            'action' => 'delete_enrollment',
            'target_type' => 'Enrollment',
            'target_id' => $enrollmentSnapshot->id,
            'metadata' => [
                'enrollment' => $enrollmentSnapshot,

            ],
            'old_values' => [
                'department' => $enrollmentSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->back()->with('success', 'Session unenrolled successfully.');
    }
}
