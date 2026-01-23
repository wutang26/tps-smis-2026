<?php
namespace App\Http\Controllers;

use App\Imports\CourseworkResultImport;
use App\Models\Course;
use App\Models\CourseWork;
use App\Models\CourseworkResult;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\SemesterExam;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CourseworkResultController extends Controller
{

        public function __construct()
    {
        $this->middleware('permission:student-coursework-list')->only(['studentCoursework']);
    }
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
            $semesters = Semester::with(['courses' => function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            }])->get();
        }
        elseif ($user->hasRole('Instructor')) {
            $semesters = Semester::with(['courses' => function ($query) use ($userId, $selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereHas('courseInstructors', function ($subQuery) use ($userId) {
                        $subQuery->where('user_id', $userId);
                    });
            }])->get();
        }
        else {
            $semesters = [];
        }

        // Optional semester filter
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester = $selectedSemesterId
            ? Semester::with(['courses' => function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            }])->find($selectedSemesterId)
            : null;

        return view('course_works_results.index', compact('programme', 'semesters', 'selectedSemester', 'selectedSessionId'));
    }


    public function getResultsByCourse_old($courseId)
    {
        try {
            // Fetch all coursework configurations for the given course
            $courseworks = DB::table('courseworks')
                ->where('course_id', $courseId)
                ->select('id', 'coursework_title')
                ->get();

            // Fetch coursework results with pagination
            $results = DB::table('coursework_results')
                ->join('students', 'coursework_results.student_id', '=', 'students.id')
                ->where('coursework_results.course_id', $courseId)
                ->select(
                    'coursework_results.student_id',
                    'coursework_results.coursework_id',
                    'coursework_results.score',
                    'students.force_number',
                    'students.first_name',
                    'students.middle_name',
                    'students.last_name'
                )
                ->paginate(10); // Limit results to 10 per page

            // Group results by student ID
            $groupedResults = collect($results->items())->groupBy('student_id')->map(function ($studentResults) use ($courseworks) {
                $studentData = $studentResults->first();
                $scores      = collect($studentResults)->pluck('score', 'coursework_id');
                $totalCW     = $scores->sum();

                return [
                    'student'  => [
                        'force_number' => $studentData->force_number,
                        'first_name'   => $studentData->first_name,
                        'middle_name'  => $studentData->middle_name,
                        'last_name'    => $studentData->last_name,
                    ],
                    'scores'   => $scores,
                    'total_cw' => $totalCW,
                ];
            });

            // Sort results by total_cw in descending order
            $sortedResults = $groupedResults->sortByDesc('total_cw');

            // If no results exist, handle empty data
            if ($sortedResults->isEmpty()) {
                return response()->json([
                    'courseworks' => $courseworks ?? [],
                    'results'     => [
                        'data'  => [],
                        'links' => [],
                    ],
                    'message'     => 'No results found for this course.',
                ]);
            }

            // Return JSON response with sorted results and pagination links
            return response()->json([
                'courseworks' => $courseworks ?? [],
                'results'     => [
                    'data'  => $sortedResults,
                    'links' => $results->toArray()['links'], // Provide pagination links
                ],
            ]);
        } catch (\Exception $e) {
            // Log error and return a server error response
            \Log::error('Error fetching coursework results:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getResultsByCourse(Request $request, $courseId)
{
    try {
        \Log::info("Fetching results for course ID: {$courseId}");

        // Optional search query
        $search = $request->input('search');

        // Fetch all courseworks for the course
        $courseworks = DB::table('courseworks')
            ->where('course_id', $courseId)
            ->select('id', 'coursework_title')
            ->get();

        // Base query for results
        $resultsQuery = DB::table('coursework_results')
            ->join('students', 'coursework_results.student_id', '=', 'students.id')
            ->join('courseworks', 'coursework_results.coursework_id', '=', 'courseworks.id')
            ->where('courseworks.course_id', $courseId)
            ->select(
                'coursework_results.student_id',
                'students.force_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                DB::raw('SUM(coursework_results.score) as total_cw')
            )
            ->groupBy(
                'coursework_results.student_id',
                'students.force_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name'
            )
            ->orderByDesc('total_cw');

        // Apply search filter if provided
        if ($search) {
            $resultsQuery->where(function ($q) use ($search) {
                $q->where('students.force_number', 'like', "%{$search}%")
                  ->orWhere('students.first_name', 'like', "%{$search}%")
                  ->orWhere('students.middle_name', 'like', "%{$search}%")
                  ->orWhere('students.last_name', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $results = $resultsQuery->paginate(10);

        $currentPage = $results->currentPage();
        $perPage = $results->perPage();

        // Format results with scores and serial numbers
        $groupedResults = collect($results->items())->map(function ($studentResult, $index) use ($courseworks, $currentPage, $perPage) {
            $scores = DB::table('coursework_results')
                ->where('student_id', $studentResult->student_id)
                ->whereIn('coursework_id', $courseworks->pluck('id'))
                ->pluck('score', 'coursework_id');

            return [
                'serial_number' => ($currentPage - 1) * $perPage + $index + 1,
                'student' => [
                    'force_number' => $studentResult->force_number,
                    'first_name'   => $studentResult->first_name,
                    'middle_name'  => $studentResult->middle_name,
                    'last_name'    => $studentResult->last_name,
                ],
                'scores'   => $scores,
                'total_cw' => $studentResult->total_cw,
            ];
        });

        return response()->json([
            'courseworks' => $courseworks ?? [],
            'results' => [
                'data' => $groupedResults,
                'links' => $results->toArray()['links'],
                'pagination' => [
                    'current_page' => $currentPage,
                    'per_page'     => $perPage,
                    'total'        => $results->total(),
                    'courseId' => $courseId,
                ],
            ],
        ]);

    } catch (\Exception $e) {
        \Log::error('Error fetching coursework results:', ['message' => $e->getMessage()]);
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
}


   public function coursework()
{
    $userId = auth()->id();

    $student = Student::where('user_id', $userId)->first();

    if (!$student) {
        return redirect()->back()->with('error', 'Student record not found.');
    }

    // Get student's courses with pivot info (credit_weight, course_type, semester_id)
    $studentCourses = $student->courses()
        ->withPivot(['semester_id', 'course_type', 'credit_weight'])
        ->get()
        ->keyBy('id');  // Key by course id for easy access in Blade

    $results = CourseworkResult::with([
            'student',
            'coursework.assessmentType',
            'coursework.course',
            'coursework.semester',
            'coursework.programme', 
            'programmeCourseSemester'
        ])
        ->where('student_id', $student->id)
        ->get();

    $assessmentTypes = $results->pluck('coursework.assessmentType')->unique()->filter()->values();

    // Group by coursework's semester ID (use 'unknown' if missing)
    $groupedBySemester = $results->groupBy(fn($result) => optional($result->coursework->semester)->id ?? 'unknown');
    $groupedBySemester = $groupedBySemester->sortKeys();

    return view('students.coursework.coursework', compact('groupedBySemester', 'assessmentTypes', 'studentCourses','student'));
}


public function studentCoursework($studentId)
{
    $userId = auth()->id();

    $student = Student::find($studentId);

    if (!$student) {
        return redirect()->back()->with('error', 'Student record not found.');
    }

    // Get student's courses with pivot info (credit_weight, course_type, semester_id)
    $studentCourses = $student->courses()
        ->withPivot(['semester_id', 'course_type', 'credit_weight'])
        ->get()
        ->keyBy('id');  // Key by course id for easy access in Blade

    $results = CourseworkResult::with([
            'student',
            'coursework.assessmentType',
            'coursework.course',
            'coursework.semester',
            'coursework.programme', 
            'programmeCourseSemester'
        ])
        ->where('student_id', $student->id)
        ->get();

    $assessmentTypes = $results->pluck('coursework.assessmentType')->unique()->filter()->values();

    // Group by coursework's semester ID (use 'unknown' if missing)
    $groupedBySemester = $results->groupBy(fn($result) => optional($result->coursework->semester)->id ?? 'unknown');
    $groupedBySemester = $groupedBySemester->sortKeys();

    return view('students.coursework.coursework', compact('groupedBySemester', 'assessmentTypes', 'studentCourses','student'));
}

    public function summary($id)
    {
        $result = CourseworkResult::with(['student', 'course', 'coursework', 'semester', 'programmeCourseSemester'])
            ->findOrFail($id);

        return view('students.coursework.summary', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retrieve necessary data for the form (students, courses, course works, semesters)
        $students    = Student::where('programme_id', 1)->where('session_programme_id', 4)->orderBy('first_name', 'ASC')->get();
        $courses     = Course::all();
        $courseWorks = CourseWork::all();
        $semesters   = Semester::all();

        return view('course_works_results.create', compact('students', 'courses', 'courseWorks', 'semesters'));
    }

    



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'    => 'required|exists:students,id',
            'coursework_id' => 'required|exists:courseworks,id',
            'score'         => 'required|integer',
        ]);

        CourseworkResult::create($request->all());

        return redirect()->route('coursework_results.index')->with('success', 'Coursework Result created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseworkResult $courseworkResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseworkResult $courseworkResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseworkResult $courseworkResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseworkResult $courseworkResult)
    {
        //
    }

    public function create_import($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('course_works_results.upload_explanation', compact('course'));
    }

    public function import(Request $request, $courseId)
    {
        // dd($request);
        // dd($request->file('import_file'));
        // dd(file_exists($request->file('import_file')->getRealPath()));

        // Validate the request input
        $request->validate([
            'courseworkId' => 'required|exists:courseworks,id',
            'import_file'  => 'required|file|mimes:csv,xls,xlsx|max:4048',
        ]);

        // Assigning variables correctly from the request
        $semesterId   = $request->semesterId;
        $courseworkId = $request->courseworkId;

        // dd($courseworkId);
        // Validate the import file format
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
            Excel::import(new CourseworkResultImport($courseworkId), $request->file('import_file'));

            // Redirect with success message after successful import
            return redirect()->route('coursework_results.index')->with('success', 'Coursework results uploaded successfully.');
        } catch (Exception $e) {
            // Catch any errors during the import process and return an error response
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadSample()
    {
        $path = storage_path('app/public/sample/coursework_result.xlsx');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }
    public function getAssignedCourses()
    {
        $user = Auth::user();
        // $user = User::findOrFail($userId);

        // Retrieve the user instance

        // Retrieve all course instructors for this user and eagerly load related semester and course
        $courseInstructors = $user->courseInstructors()
            ->with('programmeCourseSemester.semester', 'course') // Eager loading for programmeCourseSemester and semester
            ->get();

        // Group the course instructors by semester name
        $groupedCourses = $courseInstructors->groupBy(function ($course_instructor) {
            // Check if the semester data is available, if not group under 'Unassigned'
            if ($course_instructor->programmeCourseSemester && $courseInstructor->programmeCourseSemester->semester) {
                return $course_instructor->programmeCourseSemester->semester->name;
            }
            // Return 'Unassigned' if semester data is missing
            return 'Unassigned';
        });

        // Add labels to the groups
        $groupedWithLabels = $groupedCourses->map(function ($group, $semesterName) {
            return [
                'label'   => "Courses in $semesterName", // Label for the group
                'courses' => $group->map(function ($courseInstructor) {
                    return $courseInstructor->course; // Only return the Course model
                }),
            ];
        });

        // Filter out the empty keys or groups
        $groupedWithLabels = $groupedWithLabels->filter(function ($group) {
            return $group['courses']->isNotEmpty(); // Only keep groups with courses
        });

        // Return the grouped courses with labels
        return $groupedWithLabels;
    }

}
