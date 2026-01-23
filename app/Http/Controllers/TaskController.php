<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Task;
use App\Models\Region;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Http\Request;
use App\Exports\AssignedStaffExport;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();

        return view('staffs.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staffs.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($request->only('title', 'description', 'priority', 'due_date'));

        // return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
        return redirect()->route('tasks.assign', $task->id)->with('success', 'Task created! Now assign staff.');
    }

    public function assignForm(Request $request, Task $task)
    {
        $activeTaskIds = Task::whereIn('status', ['active', 'scheduled', 'overdue'])->pluck('id');

        $busyStaffIds = DB::table('staff_task')
            ->whereIn('task_id', $activeTaskIds)
            ->pluck('staff_id');

        $staff = Staff::query()
            ->whereNotIn('id', $busyStaffIds)
            ->where('status', 'active')
            ->when($request->designation, fn($q) => $q->where('designation', $request->designation))
            ->when($request->rank, fn($q) => $q->where('rank', $request->rank))
            ->when($request->search, function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($query) use ($search) {
                        $query->where('firstName', 'like', "%$search%")
                            ->orWhere('middleName', 'like', "%$search%")
                            ->orWhere('lastName', 'like', "%$search%");
                    });
                })
            ->when($request->sort, function ($q) use ($request) {
                $direction = $request->direction === 'desc' ? 'desc' : 'asc';
                $q->orderBy($request->sort, $direction);
            })
            ->get();


        return view('staffs.tasks.assign', [
            'task' => $task,
            'staff' => $staff,
            'regions' => Region::orderBy('name')->get(),
            'districts' => District::orderBy('name')->get(),
            'designations' => Staff::select('designation')->distinct()->pluck('designation'),
            'ranks' => Staff::select('rank')->distinct()->pluck('rank'),
        ]);
    }

    public function assignStaff(Request $request, Task $task)
    {
        $request->validate([
            'staff_ids' => 'required|array|min:1',
            'staff_ids.*' => 'exists:staff,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        foreach ($request->staff_ids as $id) {
            $task->staff()->attach($id, [
                'region_id' => $request->region_id,
                'district_id' => $request->district_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'assigned_at' => now(),
                'is_active' => true,
                'assigned_by' => Auth::id(),
            ]);
        }

        return redirect()
            ->route('tasks.staff', ['task' => $task->id, 'region_id' => $request->region_id])
            ->with('success', 'Staff assigned successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function showStaff(Request $request, Task $task)
    {
        $selectedRegion = $request->region_id;

        // Eager load staff with pivot data
        $task->load('staff');

        // Extract unique region IDs from pivot
        $regionIds = $task->staff->pluck('pivot.region_id')->unique()->filter();

        // Map region IDs to region names
        $regionMap = Region::whereIn('id', $regionIds)->orderBy('name')->pluck('name', 'id');

        // Filter staff by selected region (if any)
        $filteredStaff = $selectedRegion
            ? $task->staff->filter(fn($member) => $member->pivot->region_id == $selectedRegion)
            : collect();

        // Group filtered staff by region name
        $grouped = $filteredStaff->groupBy(fn($member) => $regionMap[$member->pivot->region_id] ?? 'Unknown Region');

        return view('staffs.tasks.show', compact('task', 'regionMap', 'grouped', 'selectedRegion'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('staffs.tasks.edit', compact('task'));
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'priority', 'due_date'));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function exportAssignedStaff(Request $request, Task $task)
    {
        $task->load('staff');

        $regionIds = $task->staff->pluck('pivot.region_id')->unique()->filter();
        $regionMap = Region::whereIn('id', $regionIds)->orderBy('name')->pluck('name', 'id');
        $selectedRegion = $request->region_id;

        return Excel::download(new AssignedStaffExport($task, $regionMap, $selectedRegion), 'assigned_staff.xlsx');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function filterStaff(Request $request)
    {
        $staff = Staff::query()
            ->when($request->designation, fn($q) => $q->where('designation', $request->designation))
            ->when($request->rank, fn($q) => $q->where('rank', $request->rank))
            ->when($request->gender, fn($q) => $q->where('gender', $request->gender))
            ->where('status', 'active')
            ->get();

        return response()->json($staff);
    }

}
