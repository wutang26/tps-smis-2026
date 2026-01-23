<?php
namespace App\Http\Controllers;

use App\Imports\CourseExamResultImport;
use App\Models\Course;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\SemesterExam;
use App\Models\FinalResult;
use App\Models\SemesterExamResult;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class SemesterExamResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Handle session selection
        if ($request->has('session_id')) {
            session(['selected_session' => $request->session_id]);
        }

        $selectedSessionId = session('selected_session') ?? 1;

        $programme = Programme::findOrFail(1);
        $user = $request->user();
        $userId = $user->id;

        // Role-based semester/course access
        if (
            $user->hasRole('Super Administrator') ||
            $user->hasRole('Academic Coordinator') ||
            $user->hasRole('Chief Instructor') ||
            $user->hasRole('Head of Department')
        ) {
            $semesters = Semester::with([
                'courses' => function ($query) use ($selectedSessionId) {
                    $query->where('session_programme_id', $selectedSessionId);
                }
            ])->get();
        } elseif ($user->hasRole('Instructor')) {
            $semesters = Semester::with([
                'courses' => function ($query) use ($userId, $selectedSessionId) {
                    $query->where('session_programme_id', $selectedSessionId)
                        ->whereHas('courseInstructors', function ($subQuery) use ($userId) {
                            $subQuery->where('user_id', $userId);
                        });
                }
            ])->get();
        } else {
            $semesters = [];
        }

        // Optional semester filter for tab activation
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester = $selectedSemesterId ? Semester::with([
            'courses' => function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            }
        ])->find($selectedSemesterId) : null;

        $selectedCourseId = $request->get('course_id');
        $selectedCourse = $selectedCourseId ? Course::find($selectedCourseId) : null;

        return view('semester_exams.index', compact(
            'programme',
            'semesters',
            'selectedSemester',
            'selectedSessionId',
            'selectedCourse',
            'user'
        ));
    }

    public function showExamResults2()
    {
        // Get the authenticated student
        $user = Auth::user();
        $student = $user->student;

        // Retrieve results with relationships eager-loaded
        $results = SemesterExamResult::with([
            'semesterExam.semester',
            'semesterExam.course',
            'semesterExam.course.programmes'
        ])
            ->where('student_id', $student->id)
            ->get();
        // Group results by semester
        $groupedBySemester = $results->groupBy(function ($result) {
            return $result->semesterExam->semester->id;
        });

        return view('students.exam.results', [
            'groupedBySemester' => $groupedBySemester,
        ]);
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
        $course = Course::findOrFail($courseId);
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
            'course_id' => $course->id,
            'semester_id' => $coursePivot->semester_id,
            'assessment_type_id' => $request->assessment_type_id,
            'max_score' => $request->max_score,
            'exam_date' => $request->exam_date,
            'session_programme_id' => $coursePivot->session_programme_id,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Course Exam configured successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SemesterExamResult $semesterExamResult)
    {
        //
    }


    public function getExamResultsByCourseold($courseId, $semesterId)
    {
        // Handle session selection
        if ($request->has('session_id')) {
            session(['selected_session' => $request->session_id]);
        }

        $selectedSessionId = session('selected_session') ?? 1;

        // Get course info
        $course = DB::table('courses')
            ->where('id', $courseId)
            ->first();

        // Fetch paginated semester exam results
        $results = SemesterExamResult::whereHas('semesterExam', function ($query) use ($courseId, $semesterId, $selectedSessionId) {
            $query->where('course_id', $courseId)
                ->where('semester_id', $semesterId)
                ->where('session_programme_id', $selectedSessionId);
        })
            ->with(['student', 'semesterExam'])
            ->paginate(10);

        // Check if final results exist
        $hasFinalResults = FinalResult::with('student')
            ->where('course_id', $courseId)
            ->where('semester_id', $semesterId)
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->exists();

        // Return JSON with paginator included
        return response()->json([
            'course' => $course ?? [],
            'hasFinalResults' => $hasFinalResults,
            'results' => $results, // send paginator directly
        ]);
    }

    public function getExamResultsByCourse(Request $request, $courseId, $semesterId)
    {
        try {
            $search = $request->query('search', '');

            // Fetch exam config
            $exam = DB::table('semester_exams')
                ->where('course_id', $courseId)
                ->where('semester_id', $semesterId)
                ->first();

            if (!$exam) {
                return response()->json([
                    'results' => [
                        'data' => [],
                        'links' => [],
                    ],
                    'message' => 'No exam configuration found for this course and semester.',
                ]);
            }

            // Fetch exam results with search
            $results = DB::table('semester_exam_results')
                ->join('students', 'semester_exam_results.student_id', '=', 'students.id')
                ->where('semester_exam_results.semester_exam_id', $exam->id)
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('students.first_name', 'like', "%{$search}%")
                            ->orWhere('students.middle_name', 'like', "%{$search}%")
                            ->orWhere('students.last_name', 'like', "%{$search}%")
                            ->orWhere('students.force_number', 'like', "%{$search}%");
                    });
                })
                ->select(
                    'semester_exam_results.student_id',
                    'students.force_number',
                    'students.first_name',
                    'students.middle_name',
                    'students.last_name',
                    'semester_exam_results.score as exam_score'
                )
                ->orderByDesc('semester_exam_results.score')
                ->paginate(10);

            $groupedResults = collect($results->items())->map(function ($studentResult) {
                return [
                    'student' => [
                        'force_number' => $studentResult->force_number,
                        'first_name' => $studentResult->first_name,
                        'middle_name' => $studentResult->middle_name,
                        'last_name' => $studentResult->last_name,
                    ],
                    'exam_score' => $studentResult->exam_score,
                ];
            });

            return response()->json([
                'exam' => $exam,
                'results' => [
                    'data' => $groupedResults,
                    'links' => $results->toArray()['links'],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching semester exam results:', ['message' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
