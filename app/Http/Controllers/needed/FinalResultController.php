<?php

namespace App\Http\Controllers;

use App\Models\FinalResult;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Company;
use App\Services\FinalResultService;
use Illuminate\Http\Request;

class FinalResultController extends Controller
{
    protected $finalResultService;

    public function __construct(FinalResultService $finalResultService)
    {
        $this->finalResultService = $finalResultService;
    }

    public function studentList(){
        
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
        $students = Student::where('session_programme_id', $selectedSessionId)->orderBy('company_id')->orderBy('platoon')->paginate(20);
        $companies = Company::all();
        return view('final_results.student_certificate', compact('students', 'companies'));

    }
    public function index()
    {
        $finalResults = FinalResult::with(['student', 'semester', 'course'])->get();
        return view('final_results.index', compact('finalResults'));
    }

    public function create()
    {
        $students = Student::all();
        $semesters = Semester::all();
        $courses = Course::all();
        return view('final_results.create', compact('students', 'semesters', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $resultData = $this->finalResultService->calculateFinalResult(
            $request->student_id,
            $request->semester_id,
            $request->course_id
        );

        $resultData['student_id'] = $request->student_id;
        $resultData['semester_id'] = $request->semester_id;
        $resultData['course_id'] = $request->course_id;

        FinalResult::create($resultData);

        return redirect()->route('final_results.index')
                         ->with('success', 'Final result created successfully.');
    }

    public function show(FinalResult $finalResult)
    {
        return view('final_results.show', compact('finalResult'));
    }

    public function edit(FinalResult $finalResult)
    {
        $students = Student::all();
        $semesters = Semester::all();
        $courses = Course::all();
        return view('final_results.edit', compact('finalResult', 'students', 'semesters', 'courses'));
    }

    public function update(Request $request, FinalResult $finalResult)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $resultData = $this->finalResultService->calculateFinalResult(
            $request->student_id,
            $request->semester_id,
            $request->course_id
        );

        $resultData['student_id'] = $request->student_id;
        $resultData['semester_id'] = $request->semester_id;
        $resultData['course_id'] = $request->course_id;

        $finalResult->update($resultData);

        return redirect()->route('final_results.index')
                         ->with('success', 'Final result updated successfully.');
    }

    public function destroy(FinalResult $finalResult)
    {
        $finalResult->delete();

        return redirect()->route('final_results.index')
                         ->with('success', 'Final result deleted successfully.');
    }

    public function generate()
    {
        $enrollments = Enrollment::all();

        foreach ($enrollments as $enrollment) {
            $resultData = $this->finalResultService->calculateFinalResult(
                $enrollment->student_id,
                $enrollment->semester_id,
                $enrollment->course_id
            );

            $resultData['student_id'] = $enrollment->student_id;
            $resultData['semester_id'] = $enrollment->semester_id;
            $resultData['course_id'] = $enrollment->course_id;

            $finalResult = FinalResult::updateOrCreate(
                [
                    'student_id' => $enrollment->student_id,
                    'semester_id' => $enrollment->semester_id,
                    'course_id' => $enrollment->course_id,
                ],
                $resultData
            );
        }

        return redirect()->route('final_results.index')
                         ->with('success', 'Final results generated successfully.');
    }
    
}
