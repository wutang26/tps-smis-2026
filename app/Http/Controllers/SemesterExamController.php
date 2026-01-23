<?php

namespace App\Http\Controllers;

use App\Models\SemesterExam;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Semester;
use App\Services\AuditLoggerService;
use App\Imports\CourseExamResultImport;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SemesterExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexold(Request $request)
    {
        $programme = Programme::findOrFail(1);
        $userId    = $request->user()->id;
        $user      = $request->user();
        if (
            $user->hasRole('Super Administrator') ||
            $user->hasRole('Academic Coordinator') ||
            $user->hasRole('Chief Instructor') ||
            $user->hasRole('Head of Department')) {
            $semesters = Semester::with('courses')->get();

        } else if ($request->user()->hasRole('Instructor')) {
            $semesters = Semester::with(['courses' => function ($query) use ($userId) {
                $query->whereHas('courseInstructors', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId);
                });
            }])->get();
        } else {
            $semesters = [];
        }
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester   = $selectedSemesterId ? Semester::with('courses')->find($selectedSemesterId) : null;
        $selectedCourseId = $request->get('course_id');
        $selectedCourse = $selectedCourseId ? Course::find($selectedCourseId) : null;

        return view('semester_exams.index', compact('programme', 'semesters', 'selectedSemester', 'selectedCourse'));
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
    public function store(Request $request, $courseId)
    {
        //return $request->all();
        $course      = Course::findOrFail($courseId);
        $coursePivot = $course->semesters[0]->pivot;
        $request->validate([
            'max_score' => 'required|integer|min:1',
            'exam_date' => 'required|date',
            Rule::unique('courses')->where(function ($query) use ($request, $courseId) {
                return $query->where('id', $courseId)                                // Check within the same course
                    ->where('session_programme_id', $coursePivot->session_programme_id); // Check within the same assessment type
            }),
        ]);

        SemesterExam::create([
            'course_id'            => $course->id,
            'semester_id'          => $coursePivot->semester_id,
            //'exam_title'                => $request->exam_title,
            'max_score'            => $request->max_score,
            'exam_date'            => $request->exam_date,
            'session_programme_id' => $coursePivot->session_programme_id,
            'created_by'           => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Course Semester Exam configured successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SemesterExam $semesterExam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SemesterExam $semesterExam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $exam = SemesterExam::findOrFail($id);

        $validated = $request->validate([
            'exam_title' => 'required|string|max:255',
            'max_score' => 'required|numeric|min:0',
            'exam_date' => 'required|date',
        ]);

        $exam->update($validated);

        return redirect()->back()->with('success', 'Semester exam configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request, $id, AuditLoggerService $auditLogger)
    {
        $exam = SemesterExam::findOrFail($id);
        $user = $request->user();

        // Capture snapshot before deletion
        $examSnapshot = $exam->toArray();

        // Delete exam
        $exam->delete();

        // Log audit entry via service
        $auditLogger->logAction([
            'action' => 'delete_semester_exam',
            'target_type' => 'SemesterExam',
            'target_id' => $exam->id,
            'metadata' => [
                'title' => $exam->exam_title ?? 'Untitled',
                'semester_id' => $exam->semester_id,
                'exam_type' => $exam->exam_type ?? null,
            ],
            'old_values' => $examSnapshot,
            'new_values' => null,
            'request' => $request,
            'user' => $user,
        ]);

        return redirect()->back()->with('success', 'Semester exam deleted successfully.');
    }



    public function semExams($semesterId, $courseId)
    {
        Log::info("Fetching semester exam for semester ID: {$semesterId} and course Id: {$courseId} ");
        
        if (!$courseId) {
            return response()->json(['error' => 'Course ID not found in session'], 400);
        }
    
        // Find the semester
        $semester = Semester::findOrFail($semesterId);
    
        // Retrieve semester exam filtered by both semester_id and course_id
        $courseworks = SemesterExam::where('semester_id', $semesterId)
            ->where('course_id', $courseId)
            ->get(['id', 'coursework_title']);
    
        return response()->json($courseworks);
    }

     public function getUploadExplanation($courseId, $semesterId)
    {
        $course = Course::find($courseId);
        return view('semester_exams.upload_explanation', compact('course', 'semesterId'));
    }

    public function uploadResults(Request $request, $courseId)
    {
        $course    = Course::find($courseId);
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type. Please upload a CSV, XLS, or XLSX file.');
                    }
                },
            ],
        ]);

        // dd('Excel import executed successfully');
        if ($validator->fails()) {
            // Return an error response if validation fails
            return back()->with('error', $validator->errors()->first());
        }

        try {
            // Perform the import using the provided Excel file
            Excel::import(new CourseExamResultImport($courseId, $request->semesterId), $request->file('import_file'));

            // Redirect with success message after successful import
            return redirect()->route('semester_exams.index')->with('success', 'Course exam results uploaded successfully.');
        } catch (Exception $e) {
            // Catch any errors during the import process and return an error response
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        return $course;
    }

    public function configure($courseId)
    {
        $course = Course::with('semesterExams')->findOrFail($courseId);
        return view('semester_exams.configurations.index', compact('course'));
    }


}
