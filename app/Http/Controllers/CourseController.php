<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SessionProgramme;
use App\Models\Semester;
use App\Models\ProgrammeCourseSemester;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use App\Services\AuditLoggerService;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:course-create')->only(['create', 'store']);
        $this->middleware('permission:course-list')->only(['index', 'show']);
        $this->middleware('permission:course-edit')->only(['edit', 'update']);
        $this->middleware('permission:course-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

         $user = $request->user();
         if($user->hasRole('Super Administrator') ||
         $user->hasRole('Chief Instructor') || 
         $user->hasRole('Commandant')|| $user->hasRole('Chief Instructor') ||
         $user->hasRole('Academic Coordinator')){
            $courses = Course::orderBy('id','DESC')->paginate(10);
            return view('courses.index',compact('courses'))
                ->with('i', ($request->input('page', 1) - 1) * 10);
         }
     else {
         $courses = collect();
         foreach($user->course_instructor as $course_inst){
            $courses->push($course_inst->course);
         }
         $perPage = 10; // Number of items per page
            $page = LengthAwarePaginator::resolveCurrentPage(); // Current page number

            $paginated = new LengthAwarePaginator(
                $courses->slice(($page - 1) * $perPage, $perPage)->values(), // Items for current page
                $courses->count(), // Total items
                $perPage, // Items per page
                $page, // Current page
                ['path' => $request->url(), 'query' => $request->query()] // Maintain query strings
            );
         return view('courses.index', ['courses' => $paginated]);

        }
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        return view('courses.create',compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'courseName' => 'required|unique:courses,courseName',
            'courseCode' => 'required',
        ]);
    
        Course::create($request->all());
    
        return redirect()->route('courses.index')
                        ->with('success','Course added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $session_programmes = SessionProgramme::all();
        $semesters = Semester::all();
        $departmentName = Department::WHERE('id' , $course->department_id)->pluck('departmentName');
        return view('courses.show',compact('departmentName','course','session_programmes','semesters'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $departments = Department::get();
        return view('courses.edit',compact('course', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course,  AuditLoggerService $auditLogger)
    {
        request()->validate([
            'courseName' => 'required|unique:courses,courseName,' . $course->id,
            'courseCode' => 'required',
       ]);
           $courseSnapshot = clone $course;
            $course->update($request->all());     
            $auditLogger->logAction([
            'action' => 'update_course',
            'target_type' => 'Course',
            'target_id' => $courseSnapshot->id,
            'metadata' => [
                'title' => $courseSnapshot->name ?? null,
            ],
            'old_values' => [
                'course' => $courseSnapshot,
            ],
            'new_values' => [
                'course' => $course,
            ],
            'request' => $request,
        ]);
       
   
       return redirect()->route('courses.index')
                       ->with('success','Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Request $request, AuditLoggerService $auditLogger): RedirectResponse
    {
        $courseSnapshot = clone $course;
        $course->delete();
        $auditLogger->logAction([
            'action' => 'delete_course',
            'target_type' => 'Course',
            'target_id' => $courseSnapshot->id,
            'metadata' => [
                'title' => $courseSnapshot->name ?? null,
            ],
            'old_values' => [
                'course' => $courseSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('courses.index')
                        ->with('success','Course deleted successfully');
    }
}