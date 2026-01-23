<?php

namespace App\Http\Controllers;

use App\Models\TimeSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\AuditLoggerService;

class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timesheets = [];
        $user = Auth::user();
        if($user->hasRole('Super Administrator') ||
        $user->hasRole('Chief Instructor') || 
        $user->hasRole('Commandant')|| $user->hasRole('Chief Instructor') ||
        $user->hasRole('Academic Coordinator')){
            $timesheets = TimeSheet::all();
        }
    else{
            $timesheets = TimeSheet::where('user_id', $user->id)->get();
          } 
        return view('time_sheets.index', compact('timesheets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('time_sheets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'description' => 'required|string|max:500',
            'tasks' => 'required|array',
            'tasks.*' => 'required|string|max:255', // Ensure each task is a string with a max length
            'hours' => 'required|numeric',
            'date' => 'required|date'
        ]);

        TimeSheet::create([
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'tasks' => json_encode($request->tasks), 
            'hours' => $request->hours,
            'date' => $request->date
        ]);
        return redirect()->route('timesheets.index')->with('success', 'Time sheet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSheet $timeSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeSheet $timeSheet, $timeSheetId)
    {
    
        $timeSheet = TimeSheet::findOrFail($timeSheetId);
        if(!Gate::allows('view-timesheet', $timeSheet)){
            abort(403);
          }
        return view('time_sheets.edit', compact('timeSheet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeSheet $timeSheet, $timeSheetId,AuditLoggerService $auditLogger)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:500',
            'tasks' => 'required|array',
            'tasks.*' => 'required|string|max:255', // Ensure each task is a string with a max length
            'hours' => 'required|numeric',
            'date' => 'required|date'
        ]);    
        $timeSheet = TimeSheet::findOrFail($timeSheetId);
        if(!Gate::allows('update-timesheet', $timeSheet)){
            abort(403);
          }
          $timeSheetSnapshot = clone $timeSheet;
        $timeSheet -> tasks= json_encode($request->tasks);
        $timeSheet -> hours= $request->hours;
        $timeSheet -> date= $request->date;
        $timeSheet -> description= $request->description;
        $timeSheet->save();
                $auditLogger->logAction([
            'action' => 'update_timesheet',
            'target_type' => 'TimeSheet',
            'target_id' => $timeSheetSnapshot->id,
            'metadata' => [
                'title' => $timeSheetSnapshot->description ?? null,
                'data' => $timeSheetSnapshot ,
            ],
            'old_values' => [
                'timesheet' => $timeSheetSnapshot,
                'staff' => $timeSheetSnapshot->staff ?? null,
            ],
            'new_values' => [
                'timesheet'=> $timeSheet,
            ],
        ]);
        return redirect()->route('timesheets.index')->with('success', 'Timesheet updated successfully.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSheet $timeSheet, $timeSheetId, AuditLoggerService $auditLogger)
    {
        $timeSheet = TimeSheet::findOrFail($timeSheetId);
        $timeSheetSnapshot = clone $timeSheet;
        $timeSheet->delete();
        $auditLogger->logAction([
            'action' => 'delete_timesheet',
            'target_type' => 'TimeSheet',
            'target_id' => $timeSheetSnapshot->id,
            'metadata' => [
                'title' => $timeSheetSnapshot->description ?? null,
                'data' => $timeSheetSnapshot,
            ],
            'old_values' => [
                'timesheet' => $timeSheetSnapshot,
                'staff' => $timeSheetSnapshot->staff ?? null,
            ],
            'new_values' => null,
        ]);
        return redirect()->route('timesheets.index')->with('success', 'Timesheet deleted successfully.');

    }

    public function approve($timeSheetId){
        $user = Auth::user();
        if(!Gate::allows('viewAny', $user)){
            abort(403);
        }
        $timeSheet = TimeSheet::findOrFail($timeSheetId);

        $timeSheet->status = "approved";
        $timeSheet->approved_by = $user->id;
        $timeSheet->save();

        return redirect()->route('timesheets.index')->with('success', 'Timesheet approved successfully.');

    }
    public function reject($timeSheetId){
        $user = Auth::user();
        if(!Gate::allows('viewAny', $user)){
            abort(403);
        }
        $timeSheet = TimeSheet::findOrFail($timeSheetId);

        $timeSheet->status = "rejected";
        $timeSheet->approved_by = $user->staff->id?? '1';
        $timeSheet->save();

        return redirect()->route('timesheets.index')->with('success', 'Timesheet rejected successfully.');

    }
    public function filter(Request $request){
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]); 
        $timesheets = [];
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user = Auth::user();
          if(!Gate::allows('viewAny', $user)){
            $timesheets = TimeSheet::where('staff_id', $user->staff->id)->whereBetween('date', [$request->start_date, $request->end_date])->get();
          }else{
            $timesheets = TimeSheet::whereBetween('date', [$request->start_date, $request->end_date])->get();
          }  
        return view('time_sheets.index',compact('timesheets','start_date', 'end_date'));

    }
}
