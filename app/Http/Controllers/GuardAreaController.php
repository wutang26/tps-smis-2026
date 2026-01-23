<?php

namespace App\Http\Controllers;

use App\Models\GuardArea;
use App\Models\Company;
use App\Models\Campus;
use App\Models\BeatException;
use App\Models\BeatTimeException;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;
class GuardAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/GuardAreaController.php
    public function index()
    {
        $guardAreas = GuardArea::all();
        $guardAreas = $guardAreas->map(function ($guardArea) {
            if($guardArea->beat_exception_ids != NULL){
              $guardArea->beat_exceptions = BeatException::whereIn('id', json_decode($guardArea->beat_exception_ids, true))->get(); 
            }
            if($guardArea->beat_time_exception_ids != NULL){
                $guardArea->beat_time_exceptions = BeatTimeException::whereIn('id', json_decode($guardArea->beat_time_exception_ids, true))->get(); 
              }
            return $guardArea;
        });
        //return $guardAreas;
        return view('guardArea.index', compact('guardAreas'));
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
        return view('guardArea.create', compact('beatExceptions', 'beatTimeExceptions', 'campuses', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guard_area_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'campus_id' => 'required|exists:campuses,id',
            'beat_exception_ids' => 'nullable|array',
            'beat_exception_ids.*' => 'nullable|numeric|exists:beat_exceptions,id',
            'beat_time_exception_ids' => 'nullable|array',
            'beat_time_exception_ids.*' => 'nullable|numeric|exists:beat_time_exceptions,id',
            'number_of_guards' => 'required|numeric|min:1'
        ]);

        GuardArea::create(
            [
                'name' => $request->guard_area_name,
                'company_id' => $request->company_id,
                'campus_id' => $request->campus_id,
                'added_by' => $request->user()->id,
                'beat_exception_ids' => $request->beat_exception_ids  ?  json_encode($request->beat_exception_ids): NULL,
                'beat_time_exception_ids' => $request->beat_time_exception_ids? json_encode($request->beat_time_exception_ids): NULL,
                'number_of_guards' => $request->number_of_guards
            ]);
        return redirect()->route('guard-areas.index')->with('success', "New guard area created successfully.");
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
    public function edit(GuardArea $guardArea)
    {
        $beatExceptions = BeatException::all();
        $beatTimeExceptions = BeatTimeException::all();
        $campuses = Campus::all();
        $companies = Company::where('campus_id', $guardArea->campus_id)->get();
        return view('guardArea.edit', compact('guardArea','beatExceptions', 'beatTimeExceptions', 'campuses', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuardArea $guardArea, AuditLoggerService $auditLogger)
    {
        $request->validate([
            'guard_area_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'campus_id' => 'required|exists:campuses,id',
            'beat_exception_ids' => 'nullable|array',
            'beat_exception_ids.*' => 'nullable|numeric|exists:beat_exceptions,id',
            'beat_time_exception_ids' => 'nullable|array',
            'beat_time_exception_ids.*' => 'nullable|numeric|exists:beat_time_exceptions,id',
            'number_of_guards' => 'required|numeric|min:0'
        ]);
        $guardAreaSnapshot = clone $guardArea;

        $guardArea->name = $request->guard_area_name;
        $guardArea->company_id = $request->company_id;
        $guardArea->campus_id = $request->campus_id;

        $guardArea->beat_exception_ids = $request->beat_exception_ids  ?  json_encode($request->beat_exception_ids): NULL;
        $guardArea->beat_time_exception_ids = $request->beat_time_exception_ids? json_encode($request->beat_time_exception_ids): NULL;
        $guardArea->number_of_guards = $request->number_of_guards;
         $guardArea->save();
        $auditLogger->logAction([
            'action' => 'delete_guard_area',
            'target_type' => 'GuardArea',
            'target_id' => $guardAreaSnapshot->id,
            'metadata' => [
                'title' => $guardAreaSnapshot->name  ?? null,
            ],
            'old_values' => [
                'guard_area' => $guardAreaSnapshot,
            ],
            'new_values' => [
                'guardArea'=> $guardArea,
            ],
            'request' => $request,
        ]);
        return redirect()->route('guard-areas.index')->with('success','Guard Area Updated successfully.');
    }

    // public function update(Request $request, GuardArea $guardArea)
    // {
    //     $data = $request->validate([
    //         'beat_exception_ids' => 'nullable|array',
    //         'beat_exception_ids.*' => 'integer',
    //         'beat_time_exception_ids' => 'nullable|array',
    //         'beat_time_exception_ids.*' => 'integer|nullable',
    //     ]);

    //     // Directly assign the array to the model attributes

    //     $data['beat_exception_ids'] =($data['beat_exception_ids']);
    //     if(!empty($data['beat_time_exception_ids'])){
    //         $data['beat_time_exception_ids'] = ($data['beat_time_exception_ids']);
    //     }

    //     $guardArea->update($data);

    //     return redirect()->route('guard-areas.index');
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GuardArea $guardArea,Request $request, AuditLoggerService $auditLogger)
    {
        $guardAreaSnapshot = clone $guardArea;
        $guardArea->delete();
                $auditLogger->logAction([
            'action' => 'delete_guard_area',
            'target_type' => 'GuardArea',
            'target_id' => $guardAreaSnapshot->id,
            'metadata' => [
                'title' => $guardAreaSnapshot->name  ?? null,
            ],
            'old_values' => [
                'guard_area' => $guardAreaSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('guard-areas.index')->with('success','Guard area deleted successfully.');
    }

    public function get_companies($campus_id){
        $companies = Company::where('campus_id', $campus_id)->get();
        return response()->json($companies);
    }
}
