<?php

namespace App\Http\Controllers;

use App\Models\ExcuseType;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;
class ExcuseTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:excuseType-create')->only(['create', 'store']);
        $this->middleware('permission:excuseType-list')->only(['index', 'show']);
        $this->middleware('permission:excuseType-update')->only(['edit', 'update']);
        $this->middleware('permission:excuseType-delete')->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $excuseTypes = ExcuseType::get();
        return view('settings.excuse_types.index',compact('excuseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.excuse_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'excuseName' => 'required|unique:excuse_types,excuseName',
            'abbreviation' => 'required|unique:excuse_types,abbreviation',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        ExcuseType::create($request->all());
    
        return redirect()->route('excuse_types.index')
                        ->with('success','Excuse type added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExcuseType $excuseType)
    {
        return view('settings.excuse_types.show',compact('excuseType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExcuseType $excuseType)
    {
        return view('settings.excuse_types.edit',compact('excuseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExcuseType $excuseType, AuditLoggerService $auditLogger)
    {
        $excuseTypeSnapshot = clone $excuseType;

        if($request->excuseName == $excuseType->excuseName){
            if($request->abbreviation == $excuseType->abbreviation){
                request()->validate([
                    'excuseName' => 'required',
                    'abbreviation' => 'required',
                    'description' => 'required',
               ]);
            }else{
                request()->validate([
                    'excuseName' => 'required',
                    'abbreviation' => 'required|unique:excuse_types,abbreviation',
                    'description' => 'required',
                ]);
            }
        }else{
            if($request->abbreviation == $excuseType->abbreviation){
                request()->validate([
                    'excuseName' => 'required|unique:excuse_types,excuseName',
                    'abbreviation' => 'required',
                    'description' => 'required',
               ]);
            }else{
                request()->validate([
                    'excuseName' => 'required|unique:excuse_types,excuseName',
                    'abbreviation' => 'required|unique:excuse_types,abbreviation',
                    'description' => 'required',
                ]);
            }
        }
   
       $excuseType->update($request->all());
                $auditLogger->logAction([
            'action' => 'update_excuse_type',
            'target_type' => 'ExcuseType',
            'target_id' => $excuseTypeSnapshot->id,
            'metadata' => [
                'excuseType' => $excuseTypeSnapshot,
            ],
            'old_values' => [
                'department' => $excuseTypeSnapshot,
            ],
            'new_values' => [
                'excuseType' => $excuseType
            ],
            'request' => $request,
        ]);
       return redirect()->route('excuse_types.index')
                       ->with('success','Excuse type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExcuseType $excuseType, Request $request, AuditLoggerService $auditLogger)
    {
        $excuseTypeSnapshot = clone $excuseType;
        $excuseType->delete();
        $auditLogger->logAction([
            'action' => 'delete_excuse_type',
            'target_type' => 'ExcuseType',
            'target_id' => $excuseTypeSnapshot->id,
            'metadata' => [
                'excuseType' => $excuseTypeSnapshot,
            ],
            'old_values' => [
                'department' => $excuseTypeSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('excuse_types.index')
                        ->with('success','Excuse type deleted successfully');
    }
}
