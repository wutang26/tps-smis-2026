<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;

class CampusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:campus-create')->only(['create', 'store']);
        $this->middleware('permission:campus-list')->only(['index', 'show']);
        $this->middleware('permission:campus-edit')->only(['edit', 'update']);
        $this->middleware('permission:campus-delete')->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campuses = Campus::get();
        return view('settings.campuses.index',compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.campuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'campusName' => 'required|unique:campuses,campusName',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        Campus::create($request->all());
    
        return redirect()->route('campuses.index')
                        ->with('success','Campus added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        return view('settings.campuses.show',compact('campus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campus $campus)
    {
        return view('settings.campuses.edit',compact('campus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campus $campus, AuditLoggerService $auditLogger)
    {
        $campusSnapshot = clone $campus;
        request()->validate([
            'campusName' => 'required|unique:campuses,campusName,'.$campus->id,
            'description' => 'required',
        ]);
        
       $campus->update($request->all());
        $auditLogger->logAction([
                'action' => 'update_campus',
                'target_type' => 'Campus',
                'target_id' => $campus->id,
                'metadata' => [
                    'title' => $campusSnapshot->campusName ?? null,
                ],
                'old_values' => [
                    'campus' => $campusSnapshot,
                ],
                'new_values' => null,
                'request' => $request,
            ]);
       return redirect()->route('campuses.index')
                       ->with('success','Campus updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus, Request $request, AuditLoggerService $auditLogger)
    {
        $campusSnapshot = clone $campus;
        $campus->delete();
        $auditLogger->logAction([
                'action' => 'delete_campus',
                'target_type' => 'Campus',
                'target_id' => $campus->id,
                'metadata' => [
                    'title' => $campusSnapshot->campusName ?? null,
                ],
                'old_values' => [
                    'campus' => $campusSnapshot,
                ],
                'new_values' => null,
                'request' => $request,
            ]);
        return redirect()->route('campuses.index')
                        ->with('success','Campus deleted successfully');
    }
}
