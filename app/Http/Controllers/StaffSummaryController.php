<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        // Aggregate summary counts
        $stats = [
            'total' => Staff::all(),
            'active' => Staff::where('status', 'active')->get(),
            'secondment' => Staff::where('status', 'secondment')->get(),
            'safari' => Staff::where('status', 'safari')->get(),
            'leave' => Staff::where('status', 'leave')->get(),
        ];

        return view('staffs.summary.index', compact('stats'));
    }

    public function filterStaff(Request $request)
    {
        $type = $request->get('type', 'total'); // optional type filter, default total
        $validTypes = ['active', 'leave', 'safari', 'secondment'];

        $query = Staff::query();

        // Filter by status type if valid
        if (in_array($type, $validTypes)) {
            $query->where('status', $type);
        }

        // Filter by staff name (search firstName or lastName)
        if ($request->filled('staff_name')) {
            $name = $request->staff_name;
            $query->where(function ($q) use ($name) {
                $q->where('firstName', 'like', "%$name%")
                    ->orWhere('lastName', 'like', "%$name%")
                    ->orWhere('forceNumber', 'like', "%$name%");
            });
        }

        // Optionally, filter by force number
        if ($request->filled('force_number')) {
            $query->where('forceNumber', 'like', '%'.$request->force_number.'%');
        }
        // Optionally, filter by rank
        if ($request->filled('rank')) {
            $query->where('rank', $request->rank);
        }
        // Paginate the result (10 per page)
        $staffs = $query->paginate(10);

        return response()->json([
            'staffs' => $staffs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
