<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Course;
use App\Models\Semester;
use App\Models\SessionProgramme;
use App\Models\User;
use App\Models\Staff;
use App\Models\ProgrammeCourseSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class ProgrammeCourseSemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:course-enrollment-create')->only(['create', 'store']);
        $this->middleware('permission:course-enrollment-list')->only(['index', 'show']);
        $this->middleware('permission:course-enrollment-edit')->only(['edit', 'update']);
        $this->middleware('permission:course-enrollment-delete')->only(['destroy']);
    }
    
    public function index()
    {
        $semesterId = 1;
        $sessionProgrammeId = session('selected_session', 4);
        //$programme = Programme::findOrFail(1);

        $programme = Programme::with(['courses' => function($query) use ($semesterId, $sessionProgrammeId) {
            $query->wherePivot('semester_id', $semesterId)
                  ->wherePivot('session_programme_id', $sessionProgrammeId);
        }])->findOrFail($sessionProgrammeId);
        
        $semester = Semester::findOrFail($semesterId);
        $sessionProgramme = SessionProgramme::findOrFail($sessionProgrammeId);
        $courses = $programme->courses;
        $course_opt = $programme->courses->where('course_type','optional');


        $courses1 = $programme->courses()->wherePivot('semester_id', 1)
                            ->wherePivot('session_programme_id', $sessionProgrammeId)
                            ->orderBy('course_type', 'ASC')
                            ->get();
                            
        $courses2 = $programme->courses()->wherePivot('semester_id', 2)
                            ->wherePivot('session_programme_id', $sessionProgrammeId)
                            ->orderBy('course_type', 'ASC')
                            ->get();
            
                            
        $courseses = ProgrammeCourseSemester::all();
        // dd($courseses);

        return view('course_assignments.index', compact('programme', 'semester', 'sessionProgramme', 'courses1','courses2'));
    }

    //Haitumikiii
    // public function create()
    // {
    //     $programme = Programme::findOrFail($id);
    //     $semester = Semester::findOrFail(1);
    //     $sessionProgramme = SessionProgramme::findOrFail(4);
    //     $courses = Course::all();

    //     return view('course_assignments.create', compact('programme', 'semester', 'sessionProgramme', 'courses'));
    // }

    public function assignCourse($id)
    {
        $programme = Programme::findOrFail($id);
        $semester = Semester::findOrFail(2);
        $sessionProgramme = SessionProgramme::findOrFail(session('selected_session', 4));
        $courses = Course::all();
        // Retrieve staff with 'instructor' role
        $staffs = User::whereHas('roles', function($query) {
            $query->where('name', 'Instructor');
        })->get();

        // $staffs = Staff::get();
        $courses1 = $programme->courses()->wherePivot('semester_id', 1)
        ->wherePivot('session_programme_id', $sessionProgramme->id)
        ->orderBy('course_type', 'ASC')
        ->get();
        $courses2 = $programme->courses()->wherePivot('semester_id', 2)
                ->wherePivot('session_programme_id', $sessionProgramme->id)
                ->orderBy('course_type', 'ASC')
                ->get();

        return view('course_assignments.create', compact('programme', 'semester', 'sessionProgramme', 'courses', 'courses1','courses2','staffs'));
    }


public function store(Request $request)
{
    $this->validate($request, [
        'programme_id' => 'required|exists:programmes,id',
        'semester_id' => 'required|exists:semesters,id',
        'session_programme_id' => 'required|exists:session_programmes,id',
        'course_type' => 'required|in:core,minor,optional',
        'credit_weight' => 'required|integer',
        'created_by' => 'required|exists:users,id'
    ]);

    $validCourseIds = [];
    $skippedCourseIds = [];

    foreach ($request->course_ids as $course_id) {
        $validator = Validator::make(['course_id' => $course_id], [
            'course_id' => [
                'required',
                Rule::unique('programme_course_semesters')->where(function ($query) use ($request) {
                    return $query->where('programme_id', $request->programme_id)
                                 ->where('session_programme_id', $request->session_programme_id);
                }),
            ],
        ], [
            'course_id.unique' => 'The course is already assigned to the selected programme and session.',
        ]);

        if (!$validator->fails()) {
            // Add course_id to validCourseIds array if validation passes
            $validCourseIds[] = $course_id;
        } else {
            // Add course_id to skippedCourseIds array if validation fails
            $skippedCourseIds[] = $course_id;
        }
    }

    if (empty($validCourseIds) && !empty($skippedCourseIds)) {
        return redirect()->back()->withErrors(['error' => 'The selected course(s) are already assigned.'])->withInput();
    }

    // Save only valid course_ids
    foreach ($validCourseIds as $course_id) {
        DB::table('programme_course_semesters')->insert([
            'programme_id' => $request->programme_id,
            'course_id' => $course_id,
            'semester_id' => $request->semester_id,
            'session_programme_id' => $request->session_programme_id,
            'course_type' => $request->course_type,
            'credit_weight' => $request->credit_weight,
            'created_by' => $request->created_by
        ]);
    }

    $successMessage = 'Course(s) assigned successfully.';
    if (!empty($skippedCourseIds)) {
        $successMessage .= ' Some courses were skipped because they were already assigned.';
    }

    return redirect()->route('assign-courses.index', [$request->programme_id, $request->semester_id, $request->session_programme_id])
                     ->with('success', $successMessage);
}


    


    public function edit($programmeId, $semesterId, $sessionProgrammeId, $courseId)
    {
        $programme = Programme::findOrFail($programmeId);
        $semester = Semester::findOrFail($semesterId);
        $sessionProgramme = SessionProgramme::findOrFail($sessionProgrammeId);
        $course = Course::findOrFail($courseId);

        return view('course_assignments.edit', compact('programme', 'semester', 'sessionProgramme', 'course'));
    }

    public function update(Request $request, $programmeId, $semesterId, $sessionProgrammeId, $courseId)
    {
        $programme = Programme::findOrFail($programmeId);

        $programme->courses()->updateExistingPivot($courseId, [
            'semester_id' => $semesterId,
            'course_type' => $request->input('course_type'),
            'credit_weight' => $request->input('credit_weight'),
            'session_programme_id' => $sessionProgrammeId
        ]);

        return redirect()->route('assign-courses.index', [$programmeId, $semesterId, $sessionProgrammeId])
                         ->with('success', 'Course updated successfully');
    }

    public function destroy($programmeId, $semesterId, $sessionProgrammeId, $courseId)
    {
        $programme = Programme::findOrFail($programmeId);

        $programme->courses()->detach($courseId, [
            'semester_id' => $semesterId,
            'session_programme_id' => $sessionProgrammeId
        ]);

        return redirect()->route('assign-courses.index', [$programmeId, $semesterId, $sessionProgrammeId])
                         ->with('success', 'Course removed successfully');
    }
}
