<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffProgrammeCourse;
use App\Models\SessionProgramme;
use App\Models\Course;
use App\Models\User;
use App\Models\ProgrammeCourseSemester;
use App\Models\CourseInstructor;
use Carbon\Carbon;


class StaffProgrammeCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAssignedInstructors(Request $request, $programmeCourseId)
    {
        // dd($request);
        $programmeCourse = StaffProgrammeCourse::find($programmeCourseId);

        if (!$programmeCourse) {
            return response()->json(['error' => 'Programme course not found'], 404);
        }

        $staffIds = json_decode($programmeCourse->staff_ids, true);
        $instructors = Staff::whereIn('id', $staffIds)->get();

        return response()->json(['instructors' => $instructors]);
    }


    // Function to load the assign_instructors blade
    public function showAssignInstructorsForm($courseId)
    {
        $course = Course::find($courseId);
        $staffs = Staff::get();

         //dd( $staffs );
        return view('instructors.assign_instructors', compact('staffs','course'));
    }
    
    public function assignInstructorsold(Request $request, $programmeCourseId)
    {
        // Validate the request
        $validated = $request->validate([
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:staff,id'
        ]);

        // Find the programme course
        $programmeCourse = StaffProgrammeCourse::find($programmeCourseId);

        if (!$programmeCourse) {
            return response()->json(['error' => 'Programme course not found'], 404);
        }

        // Ensure the staff members are valid instructors (based on roles)
        $instructors = Staff::whereIn('id', $validated['staff_ids'])->role('instructor')->get();

        // Assign the instructors by storing their IDs in the JSON column
        $programmeCourse->staff_ids = $instructors->pluck('id')->toJson();
        $programmeCourse->save();

        return response()->json(['message' => 'Instructors assigned successfully']);
    }


    public function assignInstructors(Request $request)
    {
        //return $request->all();
        // Validate the incoming request data
        $validatedData = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'semester_id' => 'required|exists:semesters,id',
            'session_programme_id' => 'required|exists:session_programmes,id',
            'course_id' => 'required|exists:courses,id',
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:staff,id',
            //'academic_year' => 'required|string',
        ]);

        // Find the matching record in programme_course_semesters
        $programmeCourseSemester = ProgrammeCourseSemester::where('programme_id', $validatedData['programme_id'])
            ->where('semester_id', $validatedData['semester_id'])
            ->where('session_programme_id', $validatedData['session_programme_id'])
            ->where('course_id', $validatedData['course_id'])
            ->first();

        if (!$programmeCourseSemester) {
            return redirect()->back()->with('error', 'Programme course semester record not found.');
        }
        // Get the ID of the programme_course_semesters record
        $programmeCourseSemesterId = $programmeCourseSemester->id;
        $session_programme = SessionProgramme::find($validatedData['session_programme_id']);
        // Logic to assign instructors to the course
        foreach ($validatedData['staff_ids'] as $staffId) {
            $staff = Staff::findOrFail($staffId);
            // Attach the instructor to the course with the programme_course_semester_id and academic_year
            CourseInstructor::updateOrCreate(
                [
                    'programme_course_semester_id' => $programmeCourseSemesterId,
                    'user_id' => $staff->user->id,  
                ],
                [
                'programme_course_semester_id' => $programmeCourseSemesterId,
                'user_id' => $staff->user->id,
                'course_id' => $validatedData['course_id'],
                'academic_year' => Carbon::parse($session_programme->startDate)->year.'/'.Carbon::parse($session_programme->endDate)->year,
            ]);
        }

        return redirect()->route('courses.show', $validatedData['course_id'])
        ->with('success', 'Instructors assigned successfully.');
// return redirect()->back()->with('success', 'Instructors assigned successfully.');
    }
    

    public function unAssignInstructor($courseInstructorId){
        $course_instructor = CourseInstructor::findOrFail($courseInstructorId);
        $course_instructor->delete();
        return redirect()->back()->with('success', 'Instructor Unassigned successfully.');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StaffProgrammeCourse $staffProgrammeCourse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaffProgrammeCourse $staffProgrammeCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaffProgrammeCourse $staffProgrammeCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffProgrammeCourse $staffProgrammeCourse)
    {
        //
    }
}