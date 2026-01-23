<?php

namespace App\Http\Controllers;

use App\Models\TerminationReason;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;

class TerminationReasonController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:terminationReason-create')->only(['create', 'store']);
    //     $this->middleware('permission:terminationReason-list')->only(['index', 'show']);
    //     $this->middleware('permission:terminationReason-update')->only(['edit', 'update']);
    //     $this->middleware('permission:terminationReason-delete')->only(['destroy']);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terminationReasons = TerminationReason::orderBy('category')->get();
        return view('settings.termination_reasons.index',compact('terminationReasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.termination_reasons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|unique:termination_reasons,reason',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        TerminationReason::create($request->all());
    
        return redirect()->route('termination_reasons.index')
                        ->with('success','Reason for Termination added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TerminationReason $terminationReason)
    {
        return view('settings.termination_reasons.show',compact('terminationReason'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TerminationReason $terminationReason)
    {
        return view('settings.termination_reasons.edit',compact('terminationReason'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TerminationReason $terminationReason)
    {
        if($request->reason == $terminationReason->reason){
                request()->validate([
                    'reason' => 'required|unique:termination_reasons,reason,' . $terminationReason->id,
                    'description' => 'required',
               ]);
            }else{
                request()->validate([
                    'reason' => 'required|unique:termination_reasons,reason,' . $terminationReason->id,
                    'description' => 'required',
                ]);
            }
   
        $terminationReason->update($request->all());
   
        return redirect()->route('termination_reasons.index')
                       ->with('success','Reason updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TerminationReason $terminationReason, Request $request, AuditLoggerService $auditLogger)
    {
        $terminationReasonSnapShot= $terminationReason;
        $terminationReason->delete();
        $auditLogger->logAction([
            'action' => 'delete_termination_reason',
            'target_type' => 'TerminationReason',
            'target_id' => $terminationReasonSnapShot->id,
            'metadata' => [
                'title' => $terminationReasonSnapShot->reason ?? null,
            ],
            'old_values' => [
                'termination_reason' => $terminationReasonSnapShot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('termination_reasons.index')
                        ->with('success','Reason deleted successfully');
    }
}
