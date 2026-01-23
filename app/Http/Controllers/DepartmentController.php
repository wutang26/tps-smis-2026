<?php

namespace App\Http\Controllers;
use App\Services\AuditLoggerService;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:department-create')->only(['create', 'store']);
        $this->middleware('permission:department-list')->only(['index', 'show']);
        $this->middleware('permission:department-edit')->only(['edit', 'update']);
        $this->middleware('permission:department-delete')->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::get();
        return view('settings.departments.index',compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'departmentName' => 'required|unique:departments,departmentName',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        Department::create($request->all());
    
        return redirect()->route('departments.index')
                        ->with('success','Department added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return view('settings.departments.show',compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('settings.departments.edit',compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department, AuditLoggerService $auditLogger)
    {
        request()->validate([
            'departmentName' => 'required|unique:departments,departmentName,'.$department->id,
            'description' => 'required',
            'is_active' => 'required',
        ]);
   
        $departmentSnapshot = clone $department;
        $department->update($request->all());
        $auditLogger->logAction([
            'action' => 'update_department',
            'target_type' => 'Department',
            'target_id' => $departmentSnapshot->id,
            'metadata' => [
                'title' => $departmentSnapshot->departmentName ?? null,
            ],
            'old_values' => [
                'department' => $departmentSnapshot,
            ],
            'new_values' => [
                'department'=> $department,
            ],
            'request' => $request,
        ]);
       
   
       return redirect()->route('departments.index')
                       ->with('success','Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department, Request $request, AuditLoggerService $auditLogger)
    {
        $departmentSnapshot = clone $department;
        $department->delete();

        $auditLogger->logAction([
            'action' => 'delete_department',
            'target_type' => 'Department',
            'target_id' => $departmentSnapshot->id,
            'metadata' => [
                'title' => $departmentSnapshot->departmentName ?? null,
            ],
            'old_values' => [
                'department' => $departmentSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
    
        return redirect()->route('departments.index')
                        ->with('success','Department deleted successfully');
    }
}
