<?php
namespace App\Http\Controllers;

use App\Models\Armory;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('staff', 'secondarystaff')->latest()->paginate(10);
        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        $staff = Armory::all();
        return view('shifts.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_date' => 'required|date',
            'start_time' => 'required',
            'staff_id' => 'required|exists:armories,id',
            'secondary_staff_id' => 'nullable|exists:armories,id',
        ]);

        Shift::create([
            'shift_date' => $request->shift_date,
            'start_time' => $request->start_time,
            'end_time' => date("H:i:s", strtotime($request->start_time) + 8 * 3600),
            'staff_id' => $request->staff_id,
            'secondary_staff_id' => $request->secondary_staff_id
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift scheduled successfully.');
    }

    public function edit(Shift $shift)
    {
        $staff = Armory::all();
        return view('shifts.edit', compact('shift', 'staff'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_date' => 'required|date',
            'start_time' => 'required',
            'staff_id' => 'required|exists:armories,id',
            'secondary_staff_id' => 'nullable|exists:armories,id',
        ]);

        $shift->update([
            'shift_date' => $request->shift_date,
            'start_time' => $request->start_time,
            'end_time' => date("H:i:s", strtotime($request->start_time) + 8 * 3600),
            'staff_id' => $request->staff_id,
            'secondary_staff_id' => $request->secondary_staff_id
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift updated successfully.');
    }
}
