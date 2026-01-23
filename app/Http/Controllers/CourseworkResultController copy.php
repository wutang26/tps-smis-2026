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
    
    public function getResultsByCoursez($courseId)
    {
        try {
            \Log::info('getResultsByCourse method called with courseId: ' . $courseId); // Log for debugging

            $results = CourseworkResult::where('course_id', $courseId)
                ->with(['student', 'course', 'coursework', 'semester'])
                ->paginate(10); // Paginate the results, 10 per page

            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Error fetching coursework results: ' . $e->getMessage()); // Log the error
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getResultsByCourse($courseId)
    {
        try {
            \Log::info('Fetching results for course ID: ' . $courseId); // Log for debugging

            // Fetch coursework headings for the course
            $courseworks = CourseWork::where('course_id', $courseId)->get(['coursework_title', 'id']);

            // Fetch coursework results for the course
            $results = CourseworkResult::where('course_id', $courseId)
                ->with(['student', 'course', 'coursework', 'semester'])
                ->paginate(10); // Paginate the results, 10 per page

            return response()->json([
                'courseworks' => $courseworks, // For dynamic table headings
                'results' => $results, // For dynamic table rows
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching coursework results: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function getCourseworkConfiguration($courseId)
    {
        try {
            // Fetch coursework configuration for the specific course
            $courseworks = CourseWork::where('course_id', $courseId)->get(['coursework_title', 'id']);

            // Fetch coursework results for the specific course
            $results = CourseworkResult::where('course_id', $courseId)
                ->with(['student'])
                ->get();

            return response()->json([
                'courseworks' => $courseworks,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching coursework configuration and results: ' . $e->getMessage());
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
            'course_id' => 'required|exists:courses,id',
            'coursework_id' => 'required|exists:course_works,id',
            'score' => 'required|integer',
            'semester_id' => 'required|exists:semesters,id',
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
            'semesterId' => 'required|exists:semesters,id',
            'courseworkId' => 'required|exists:course_works,id',
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
            Excel::import(new CourseworkResultImport($semesterId, $courseId, $courseworkId), $request->file('import_file'));

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
