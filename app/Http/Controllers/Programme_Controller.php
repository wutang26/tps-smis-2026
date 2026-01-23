<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;
use App\Http\Requests\StoreProgrammeRequest;
use App\Http\Requests\UpdateProgrammeRequest;
use DB;
use App\Models\Department;
use App\Models\StudyLevel;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProgrammeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:programme-create')->only(['create', 'store']);
        $this->middleware('permission:programme-list')->only(['index', 'show']);
        $this->middleware('permission:programme-update')->only(['edit', 'update']);
        $this->middleware('permission:programme-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $studyLevels = StudyLevel::get();
        $programmes = Programme::orderBy('id','DESC')->paginate(5);
        return view('programmes.index',compact('programmes','studyLevels'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
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
    public function store(StoreProgrammeRequest $request)
    {
        $this->authorize('create', Programme::class);
        $this->validate($request, [
            'programme_name' => 'required|unique:programmes,programme_name',
            'year' => 'required',
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Programme $programme)
    {
        $departments = Department::get();
        $studylevels = StudyLevel::get();
        return view('programmes.edit',compact('programme', 'departments', 'studylevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgrammeRequest $request, Programme $programme)
    {
        request()->validate([
            'programme_name' => 'required|unique:programmes,programme_name',
            'year' => 'required',
       ]);
   
       $session_programme->update($request->all());
   
       return redirect()->route('programmes.index')
                       ->with('success','Session programme updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programme $programme)
    {
        $product->delete();
    
        return redirect()->route('programmes.index')
                        ->with('success','Session programme deleted successfully');
    }
}
