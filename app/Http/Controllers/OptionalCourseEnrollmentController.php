<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OptionalCourseEnrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;

class OptionalCourseEnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:optional-enrollment-create')->only(['create', 'store']);
        $this->middleware('permission:optional-enrollment-list')->only(['index', 'show']);
        $this->middleware('permission:optional-enrollment-update')->only(['edit', 'update']);
        $this->middleware('permission:optional-enrollment-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $enrollments = OptionalCourseEnrollment::orderBy('id','DESC')->paginate(5);
        return view('course_enrollments.index',compact('enrollments'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        $semesters = Semester::all();
        return view('course_enrollments.create', compact('students', 'courses', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'enrollment_date' => 'required|date',
        ]);

        OptionalCourseEnrollment::create($request->all());

        return redirect()->route('enrollments.index')
                         ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OptionalCourseEnrollment $optionalCourseEnrollment)
    {
        return view('course_enrollments.show', compact('optionalCourseEnrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OptionalCourseEnrollment $optionalCourseEnrollment)
    {
        $students = Student::all();
        $courses = Course::all();
        $semesters = Semester::all();
        return view('course_enrollments.edit', compact('optionalCourseEnrollment', 'students', 'courses', 'semesters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OptionalCourseEnrollment $optionalCourseEnrollment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'enrollment_date' => 'required|date',
        ]);

        $optionalCourseEnrollment->update($request->all());

        return redirect()->route('enrollments.index')
                         ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OptionalCourseEnrollment $optionalCourseEnrollment)
    {
        $optionalCourseEnrollment->delete();

        return redirect()->route('enrollments.index')
                         ->with('success', 'Enrollment deleted successfully.');
    }
}
