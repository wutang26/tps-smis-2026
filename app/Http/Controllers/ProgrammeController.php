<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Course;
use App\Models\Semester;
use App\Models\SessionProgramme;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\StudyLevel;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\AuditLoggerService;
use DB;


class ProgrammeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:programme-create')->only(['create', 'store']);
        $this->middleware('permission:programme-list')->only(['index', 'show']);
        $this->middleware('permission:programme-edit')->only(['edit', 'update']);
        $this->middleware('permission:programme-delete')->only(['destroy']);
        $this->middleware('permission:course-enrollment-create|course-enrollment-update')->only(['assignCoursesToSemester']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $studyLevels = StudyLevel::get();
        $programmes = Programme::orderBy('id','DESC')->paginate(10);
        return view('programmes.index',compact('programmes','studyLevels'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        $studylevels = StudyLevel::get();
        return view('programmes.create',compact('departments', 'studylevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'programmeName' => 'required|unique:programmes,programmeName',
            'abbreviation' => 'required',
            'duration' => 'required',
        ]);
    
        Programme::create($request->all());
    
        return redirect()->route('programmes.index')
                        ->with('success','Session programme created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Programme $programme)
    {
        $departmentName = Department::WHERE('id' , $programme->department_id)->pluck('departmentName');
        $studyLevelName = StudyLevel::WHERE('id' , $programme->studyLevel_id)->pluck('studyLevelName');
        return view('programmes.show',compact('departmentName','programme', 'studyLevelName'));
    }

    //Course Assignment
    public function assignCoursesToSemester(Request $request, $programmeId, $semesterId, $sessionProgrammeId)
    {
        $programme = Programme::findOrFail($programmeId);
        $semester = Semester::findOrFail($semesterId);
        $sessionProgramme = SessionProgramme::findOrFail($sessionProgrammeId);

        $courseIds = $request->input('course_ids');
        $courseType = $request->input('course_type'); // Assuming the same course type for all courses
        $creditWeight = $request->input('credit_weight'); // Assuming the same credit weight for all courses

        foreach ($courseIds as $courseId) {
            $programme->courses()->attach($courseId, [
                'semester_id' => $semesterId,
                'course_type' => $courseType,
                'credit_weight' => $creditWeight,
                'session_programme_id' => $sessionProgrammeId
            ]);
        }

        return response()->json(['message' => 'Courses assigned successfully']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Programme $programme): View
    {
        $departments = Department::get();
        $studylevels = StudyLevel::get();
        return view('programmes.edit',compact('programme', 'departments', 'studylevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id,AuditLoggerService $auditLogger): RedirectResponse
    {
        // Retrieve the programme by its ID 
        $programme = Programme::findOrFail($id); 
        // Validate the incoming request 
        $validatedData = $request->validate([ 
            'programmeName' => 'required|unique:programmes,programmeName,' . $id, 
            'abbreviation' => 'required', 
            'duration' => 'required|integer', 
            'department_id' => 'required|exists:departments,id', 
            'studyLevel_id' => 'required|exists:study_levels,id', 
        ]); 

        // dd($validatedData);
        // Update the programme with validated data 

        $programme->update($validatedData);
        $programmeSnapshot = clone $programme;
        $auditLogger->logAction([
            'action' => 'update_programme',
            'target_type' => 'Programme',
            'target_id' => $programme->id,
            'metadata' => [
                'title' => $programmeSnapshot->programmeName ?? null,
            ],
            'old_values' => [
                'programme' => $programmeSnapshot,
            ],
            'new_values' => [
                'programme'=> $programme,
            ],
            'request' => $request,
        ]);
        return redirect()->route('programmes.index')
                       ->with('success','Session programme updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programme $programme, Request $request,AuditLoggerService $auditLogger)
    {
        
        
        $programmeSnapshot = clone $programme;
        $programme->delete();
        $auditLogger->logAction([
            'action' => 'delete_programme',
            'target_type' => 'Programme',
            'target_id' => $programme->id,
            'metadata' => [
                'title' => $programmeSnapshot->programmeName ?? null,
            ],
            'old_values' => [
                'programme' => $programmeSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('programmes.index')
                        ->with('success','Session programme deleted successfully');
    }
}
