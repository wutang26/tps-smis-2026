<?php

namespace App\Http\Controllers;

use App\Models\PatrolArea;
use App\Models\BeatException;
use App\Models\Company;
use App\Models\Campus;
use App\Models\BeatTimeException;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;
class PatrolAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patrolAreas = PatrolArea::all();
        $patrolAreas = $patrolAreas->map(function ($patrolArea) {
            if($patrolArea->beat_exception_ids != NULL){
              $patrolArea->beat_exceptions = BeatException::whereIn('id', json_decode($patrolArea->beat_exception_ids, true))->get(); 
            }
            if($patrolArea->beat_time_exception_ids != NULL){
                $patrolArea->beat_time_exceptions = BeatTimeException::whereIn('id', json_decode($patrolArea->beat_time_exception_ids, true))->get(); 
              }
            return $patrolArea;
        });
        //return $patrolAreas;
        return view('patrolArea.index', compact('patrolAreas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $beatExceptions = BeatException::all();
        $beatTimeExceptions = BeatTimeException::all();
        $campuses = Campus::all();
        $companies = Company::all();
        return view('patrolArea.create', compact('beatExceptions', 'beatTimeExceptions', 'campuses', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_area' => 'required',
            'end_area' => 'required',
            'company_id' => 'required|exists:companies,id',
            'campus_id' => 'required|exists:campuses,id',
            'beat_exception_ids' => 'nullable|array',
            'beat_exception_ids.*' => 'nullable|numeric|exists:beat_exceptions,id',
            'beat_time_exception_ids' => 'nullable|array',
            'beat_time_exception_ids.*' => 'nullable|numeric|exists:beat_time_exceptions,id',
            'number_of_guards' => 'required|numeric|min:0'
        ]);

        PatrolArea::create(
            [
                'start_area' => $request->start_area,
                'end_area' => $request->end_area,
                'company_id' => $request->company_id,
                'campus_id' => $request->campus_id,
                'added_by' => $request->user()->id,
                'beat_exception_ids' => $request->beat_exception_ids  ?  json_encode($request->beat_exception_ids): NULL,
                'beat_time_exception_ids' => $request->beat_time_exception_ids? json_encode($request->beat_time_exception_ids): NULL,
                'number_of_guards' => $request->number_of_guards
            ]);
        return redirect()->route('patrol-areas.index')->with('success', "New patrol area created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(PatrolArea $patrolArea)
    {
        $beatExceptions = BeatException::all();
        $beatTimeExceptions = BeatTimeException::all();
        $campuses = Campus::all();
        $companies = Company::where('campus_id', $patrolArea->campus_id)->get();
        return view('patrolArea.edit', compact('patrolArea','beatExceptions', 'beatTimeExceptions', 'campuses', 'companies'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatrolArea $patrolArea, AuditLoggerService $auditLogger)
    {
        $request->validate([
            'start_area' => 'required',
            'end_area' => 'required',
            'company_id' => 'required|exists:companies,id',
            'campus_id' => 'required|exists:campuses,id',
            'beat_exception_ids' => 'nullable|array',
            'beat_exception_ids.*' => 'nullable|numeric|exists:beat_exceptions,id',
            'beat_time_exception_ids' => 'nullable|array',
            'beat_time_exception_ids.*' => 'nullable|numeric|exists:beat_time_exceptions,id',
            'number_of_guards' => 'required|numeric|min:0'
        ]);

        $patrolAreaSnapshot = clone  $patrolArea;

        $patrolArea->start_area = $request->start_area;
        $patrolArea->end_area = $request->end_area;
        $patrolArea->company_id = $request->company_id;
        $patrolArea->campus_id = $request->campus_id;

        $patrolArea->beat_exception_ids = $request->beat_exception_ids  ?  json_encode($request->beat_exception_ids): NULL;
        $patrolArea->beat_time_exception_ids = $request->beat_time_exception_ids? json_encode($request->beat_time_exception_ids): NULL;
        $patrolArea->number_of_guards = $request->number_of_guards;

        $auditLogger->logAction([
            'action' => 'update_patrol_area',
            'target_type' => 'PatrolArea',
            'target_id' => $patrolAreaSnapshot->id,
            'metadata' => [
                'title' => $patrolAreaSnapshot->start_area. '-'.$patrolAreaSnapshot->end_area  ,
            ],
            'old_values' => [
                'patrol_area' => $patrolAreaSnapshot,
            ],
            'new_values' => [
                'patrol_area' => $patrolArea,
            ],
            'request' => $request,
        ]);
         $patrolArea->save();

        return redirect()->route('patrol-areas.index')->with('success','Patrol Area Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatrolArea $patrolArea, Request $request, AuditLoggerService $auditLogger)
    {
        $patrolAreaSnapshot = $patrolArea;
        $patrolArea->delete();
        $auditLogger->logAction([
            'action' => 'delete_patrol_area',
            'target_type' => 'PatrolArea',
            'target_id' => $patrolAreaSnapshot->id,
            'metadata' => [
                'title' => $patrolAreaSnapshot->start_area. '-'.$patrolAreaSnapshot->end_area,
            ],
            'old_values' => [
                'patrol_area' => $patrolAreaSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('patrol-areas.index')->with('success','Guard area deleted successfully.');
    }
}
