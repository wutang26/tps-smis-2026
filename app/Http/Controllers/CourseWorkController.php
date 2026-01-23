<?php

namespace App\Http\Controllers;

use App\Models\CourseWork;
use App\Models\Course;
use App\Models\Semester;
use App\Models\AssessmentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLoggerService;
class CourseWorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coursework-create')->only(['create', 'store']);
        $this->middleware('permission:coursework-list')->only(['index', 'show']);
        $this->middleware('permission:coursework-edit')->only(['edit', 'update']);
        $this->middleware('permission:coursework-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function getCourse($courseId){
        $course = Course::findOrFail($courseId);
        $assessmentTypes = AssessmentType::all();
        return view('course_works.index', compact('course','assessmentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($courseId)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $coursePivot =  $course->semesters[0]->pivot;
        $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'coursework_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courseworks')->where(function ($query) use ($request, $courseId) {
                    return $query->where('course_id', $courseId) // Check within the same course
                                ->where('assessment_type_id', $request->assessment_type_id); // Check within the same assessment type
                }),
            ],
            'max_score' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
        ]);

        CourseWork::create([
            'course_id' => $course->id,
            'semester_id' => $coursePivot->semester_id,
            'assessment_type_id' => $request->assessment_type_id,
            'coursework_title' => $request->coursework_title,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date?? NULL,
            'session_programme_id' =>$coursePivot->session_programme_id,
            'created_by' => $request->user()->id
        ]);

        return redirect()->back()->with('success', 'Assessment type added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(CourseWork $courseWork)
    {
        return $courseWork;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseWork $courseWork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id, AuditLoggerService $auditLogger)
    {
        $courseWork = CourseWork::findOrFail($id);
        $course = Course::findOrFail($courseWork->course_id);
        $coursePivot = $course->semesters[0]->pivot;

        $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'coursework_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courseworks')->where(function ($query) use ($request, $courseWork) {
                    return $query->where('course_id', $courseWork->course_id)
                                ->where('assessment_type_id', $request->assessment_type_id);
                })->ignore($courseWork->id), // Ignore current record
            ],
            'max_score' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
        ]);
        $hasResults = $courseWork->courseworkResults()->exists();
        $courseworkSnapshot = $courseWork->toArray();
        $resultsSnapshot = $hasResults ? $courseWork->courseworkResults->toArray() : [];
        $courseWork->update([
            'assessment_type_id' => $request->assessment_type_id,
            'coursework_title' => $request->coursework_title,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date ?? NULL,
            'semester_id' => $coursePivot->semester_id, // Optional: if semester can change
            'session_programme_id' => $coursePivot->session_programme_id, // Optional
            'updated_by' => $request->user()->id, // Optional audit field
        ]);

        $auditLogger->logAction([
                'action' => 'update_coursework',
                'target_type' => 'CourseWork',
                'target_id' => $courseWork->id,
                'metadata' => [
                    'title' => $courseWork->coursework_title,
                    'max_score' => $courseWork->max_score,
                    'deleted_results_count' => count($resultsSnapshot),
                ],
                'old_values' => [
                    'coursework' => $courseworkSnapshot,
                    'results' => $resultsSnapshot,
                ],
                'new_values' => [
                    'coursework'=> $courseWork,
                ],
                'request' => $request,

            ]);
        return redirect()->back()->with('success', 'Assessment type updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request, $id, AuditLoggerService $auditLogger)
    {
        $courseWork = CourseWork::findOrFail($id);
        $user = $request->user();
        $hasResults = $courseWork->courseworkResults()->exists();

        // If results exist but cascade not confirmed, block deletion
        if ($hasResults && !$request->has('cascade')) {
            return redirect()->back()->withErrors([
                'This coursework has associated results. Confirm deletion to proceed.'
            ]);
        }

        // Capture snapshots before deletion
        $courseworkSnapshot = $courseWork->toArray();
        $resultsSnapshot = $hasResults ? $courseWork->courseworkResults->toArray() : [];

        // Cascade delete results if confirmed
        if ($hasResults && $request->cascade) {
            $courseWork->courseworkResults()->delete();
        }

        // Delete coursework
        $courseWork->delete();

        // Log audit entry
        $auditLogger->logAction([
                'action' => 'delete_coursework',
                'target_type' => 'CourseWork',
                'target_id' => $courseWork->id,
                'metadata' => [
                    'title' => $courseWork->coursework_title,
                    'max_score' => $courseWork->max_score,
                    'deleted_results_count' => count($resultsSnapshot),
                ],
                'old_values' => [
                    'coursework' => $courseworkSnapshot,
                    'results' => $resultsSnapshot,
                ],
                'new_values' => null,
                'request' => $request,
                'user' => $user,
            ]);


        return redirect()->back()->with('success', 'Coursework and related results deleted successfully.');
    }

    public function getCourseworks($semesterId, $courseId)
    {
        Log::info("Fetching courseworks for semester ID: {$semesterId} and course Id: {$courseId} ");
        
        if (!$courseId) {
            return response()->json(['error' => 'Course ID not found in session'], 400);
        }
    
        // Find the semester
        $semester = Semester::findOrFail($semesterId);
    
        // Retrieve courseworks filtered by both semester_id and course_id
        $courseworks = Coursework::where('semester_id', $semesterId)
            ->where('course_id', $courseId)
            ->get(['id', 'coursework_title']);
    
        return response()->json($courseworks);
    }

}