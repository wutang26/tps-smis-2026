<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Platoon;
use App\Models\Patient;

class FeedbackController extends Controller
{
    // View all reports
    public function index()
    {
        if(!Auth::user()->hasAnyRole(['Admin','Super Administrator','clerk','staff'])){
            abort(403,'Unauthorized');
        }

        $reports = DailyReport::with('user')->latest()->get();

        $companies = Company::orderBy('name')->get();

        return view('daily-reports.index', compact('reports','companies'));
    }

    // Show create form
    public function create()
    {
        if(!Auth::user()->hasAnyRole(['Admin','Super Administrator','clerk','staff'])){
            abort(403,'Unauthorized');
        }

        $companies = Company::orderBy('name')->get();
        $platoons = Platoon::orderBy('name')->get();

        // Only patients with a valid student, sorted by name
        $patients = Patient::with('student')
            ->get()
            ->filter(fn($p) => $p->student)
            ->sortBy(fn($p) => $p->student->name);

        return view('daily-reports.create', compact('companies','platoons','patients'));
    }

    // Store report safely
    public function store(Request $request)
    {
        if(!Auth::user()->hasAnyRole(['Admin','Super Administrator','clerk','staff'])){
            abort(403,'Unauthorized');
        }

        $request->validate([
            'report_date' => 'required|date',
        ]);

        DailyReport::create([
            'report_date'           => $request->report_date,
            'reported_by'           => Auth::id(),
            'repeated_cases'        => $request->repeated_cases ?? [],
            'overloaded_cases'      => $request->overloaded_cases ?? [],
            'last_assigned_date'    => $request->last_assigned_date ?? [],
            'sick_student_names'    => $request->sick_student_names ?? [],
            'sick_student_platoon'  => $request->sick_student_platoon ?? [],
            'company'               => $request->company ?? [],
            'vitengo_cases'         => $request->vitengo_cases ?? [],
            'emergency_cases'       => $request->emergency_cases ?? [],
            'challenges'            => $request->challenges,
            'suggestions'           => $request->suggestions,
        ]);

        return redirect()->route('daily-reports.index')
            ->with('success','Daily report submitted successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $report = DailyReport::findOrFail($id);

        $companies = Company::orderBy('name')->get();
        $platoons = Platoon::orderBy('name')->get();

        return view('daily-reports.edit', compact('report','companies','platoons'));
    }

    // Update report safely
    public function update(Request $request, $id)
    {
        $report = DailyReport::findOrFail($id);

        $request->validate([
            'report_date' => 'required|date',
        ]);

        $report->update([
            'report_date'           => $request->report_date,
            'repeated_cases'        => $request->repeated_cases ?? [],
            'overloaded_cases'      => $request->overloaded_cases ?? [],
            'last_assigned_date'    => $request->last_assigned_date ?? [],
            'sick_student_names'    => $request->sick_student_names ?? [],
            'sick_student_platoon'  => $request->sick_student_platoon ?? [],
            'company'               => $request->company ?? [],
            'vitengo_cases'         => $request->vitengo_cases ?? [],
            'emergency_cases'       => $request->emergency_cases ?? [],
            'challenges'            => $request->challenges,
            'suggestions'           => $request->suggestions,
        ]);

        return redirect()->route('daily-reports.index')
            ->with('success','Report updated successfully.');
    }

    // Delete report
    public function destroy($id)
    {
        $report = DailyReport::findOrFail($id);
        $report->delete();

        return redirect()->route('daily-reports.index')
            ->with('success','Report deleted successfully.');
    }
}