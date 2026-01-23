<?php

namespace App\Http\Controllers;

use App\Models\CourseworkResult;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseWork;
use App\Models\Semester;
use App\Models\Programme;
use Illuminate\Http\Request;
use App\Imports\CourseworkResultImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class CourseworkResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programme = Programme::findOrFail(1);

        $semesters = Semester::with('courses')->get();
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester = $selectedSemesterId ? Semester::with('courses')->find($selectedSemesterId) : null;

        // dd($courseworkResults);
        return view('course_works_results.index', compact('programme','semesters','selectedSemester'));
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
                $scores = collect($studentResults)->pluck('score', 'coursework_id');
                $totalCW = $scores->sum();
    
                return [
                    'student' => [
                        'force_number' => $studentData->force_number,
                        'first_name' => $studentData->first_name,
                        'middle_name' => $studentData->middle_name,
                        'last_name' => $studentData->last_name,
                    ],
                    'scores' => $scores,
                    'total_cw' => $totalCW,
                ];
            });
    
            // Sort results by total_cw in descending order
            $sortedResults = $groupedResults->sortByDesc('total_cw');
    
            // If no results exist, handle empty data
            if ($sortedResults->isEmpty()) {
                return response()->json([
                    'courseworks' => $courseworks ?? [],
                    'results' => [
                        'data' => [],
                        'links' => [],
                    ],
                    'message' => 'No results found for this course.',
                ]);
            }
    
            // Return JSON response with sorted results and pagination links
            return response()->json([
                'courseworks' => $courseworks ?? [],
                'results' => [
                    'data' => $sortedResults,
                    'links' => $results->toArray()['links'], // Provide pagination links
                ],
            ]);
        } catch (\Exception $e) {
            // Log error and return a server error response
            \Log::error('Error fetching coursework results:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function getResultsByCourse($courseId)
{
    try {
        // Fetch all coursework configurations for the given course
        $courseworks = DB::table('courseworks')
            ->where('course_id', $courseId)
            ->select('id', 'coursework_title')
            ->get();

        // Fetch and sort coursework results by total_cw in descending order before pagination
        $results = DB::table('coursework_results')
            ->join('students', 'coursework_results.student_id', '=', 'students.id')
            ->where('coursework_results.course_id', $courseId)
            ->select(
                'coursework_results.student_id',
                'students.force_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                DB::raw('SUM(coursework_results.score) as total_cw') // Calculate total CW in query
            )
            ->groupBy(
                'coursework_results.student_id', // Group by student ID
                'students.force_number',        // Group by force number
                'students.first_name',          // Group by first name
                'students.middle_name',         // Group by middle name
                'students.last_name'            // Group by last name
            )
            ->orderByDesc('total_cw') // Sort by total scores
            ->paginate(10); // Paginate sorted results

        // Format results for the frontend
        $groupedResults = collect($results->items())->map(function ($studentResult) use ($courseworks) {
            // Fetch individual scores for this student's coursework
            $scores = DB::table('coursework_results')
                ->where('student_id', $studentResult->student_id)
                ->whereIn('coursework_id', $courseworks->pluck('id'))
                ->pluck('score', 'coursework_id'); // Map scores by coursework ID

            return [
                'student' => [
                    'force_number' => $studentResult->force_number,
                    'first_name' => $studentResult->first_name,
                    'middle_name' => $studentResult->middle_name,
                    'last_name' => $studentResult->last_name,
                ],
                'scores' => $scores,
                'total_cw' => $studentResult->total_cw,
            ];
        });

        // Handle empty results
        if ($groupedResults->isEmpty()) {
            return response()->json([
                'courseworks' => $courseworks ?? [],
                'results' => [
                    'data' => [],
                    'links' => [],
                ],
                'message' => 'No results found for this course.',
            ]);
        }

        // Return JSON response with sorted results and pagination links
        return response()->json([
            'courseworks' => $courseworks ?? [],
            'results' => [
                'data' => $groupedResults,
                'links' => $results->toArray()['links'], // Provide pagination links
            ],
        ]);
    } catch (\Exception $e) {
        // Log error and return a server error response
        \Log::error('Error fetching coursework results:', ['message' => $e->getMessage()]);
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
}

    
    


    public function coursework()
    {
        $user = auth()->user()->id;
        $studentId = Student::where('user_id', $user)->pluck('id');
        // $student = Student::find($studentId[0]);
        // $coursework = $student->coursework();

        $results = CourseworkResult::where('student_id', $studentId[0])
            ->with(['student', 'course', 'coursework', 'semester', 'programmeCourseSemester'])->get();

        $groupedBySemester = $results->groupBy('semester_id');

        // dd($groupedBySemester);

        return view('students.coursework.coursework', compact('groupedBySemester'));
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
        $students = Student::where('programme_id', 1)->where('session_programme_id', 4)->orderBy('first_name', 'ASC')->get();
        $courses = Course::all();
        $courseWorks = CourseWork::all();
        $semesters = Semester::all();

        return view('course_works_results.create', compact('students', 'courses', 'courseWorks', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'coursework_id' => 'required|exists:courseworks,id',
            'score' => 'required|integer',
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
        return view('course_works_results.upload_explanation', compact(  'course'));
    }

    public function import(Request $request, $courseId)
    {
        // dd($request);
        // dd($request->file('import_file'));
        // dd(file_exists($request->file('import_file')->getRealPath()));

        

        // Validate the request input
        $request->validate([
            'courseworkId' => 'required|exists:courseworks,id',
            'import_file' => 'required|file|mimes:csv,xls,xlsx|max:4048',
        ]);
        

        // Assigning variables correctly from the request
        $semesterId = $request->semesterId;
        $courseworkId = $request->courseworkId;

        // dd($courseworkId);
        // Validate the import file format
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type. Please upload a CSV, XLS, or XLSX file.');
                    }
                }
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
}
