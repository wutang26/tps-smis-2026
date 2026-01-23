<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Staff;
use App\Models\TeacherOnDuty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherOnDutyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:teacher_on_duty-view', ['only' => ['index']]);
        $this->middleware('permission:teacher_on_duty-assign', ['only' => ['store', 'unassign']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = [];

        $user = Auth::user();
        if ($user->hasRole(['OC Coy'])) {
            $companies = [$user->staff->company];
            $teachers  = TeacherOnDuty::whereNull('end_date')->where('company_id', $user->staff->company_id)->get();
            $staffs    = $companies[0]->staffs()->where('rank', 'CPL')->orWhere('rank', 'PC')->where('company_id', $user->staff->company_id)->paginate(10);
        } else {
            $companies = Company::has('staffs')->get();
            $teachers  = TeacherOnDuty::whereNull('end_date')->orderBy('company_id', 'asc')->get();
            $staffs    = $companies[0]->staffs()->where('rank', 'CPL')->orWhere('rank', 'PC')->paginate(10);
        }

        // return $companies[0];
        //$staffs = $companies[0]->staffs()->where('rank', 'CPL')->orWhere('rank', 'PC')->where('company_id',$companies[0]->id)->paginate(10);
        return view('teacher_on_duty.index', compact('staffs', 'companies', 'teachers'))->with('i', ($request->input('page', 1) - 1) * 10);
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
public function store(Request $request, $staffId)
{
    $staff = Staff::find($staffId);

    if (!$staff) {
        return redirect()->back()->withErrors('Staff not found.');
    }

    $request->validate([
        'start_date' => 'required|date',
    ]);

    // Check if any TeacherOnDuty in the company has the same gender and is currently assigned (end_date is null)
    $exists = TeacherOnDuty::where('company_id', $staff->company_id)
        ->whereNull('end_date')
        ->whereHas('staff', function ($query) use ($staff) {
            $query->where('gender', $staff->gender);
        })
        ->exists();

    if ($exists) {
        return redirect()->back()->with('info', 'There is already a Teacher on Duty of the same gender in this company. Please unassign first.');
    }

    // Assign new TeacherOnDuty
    TeacherOnDuty::create([
        'user_id'    => $staff->user_id,
        'company_id' => $staff->company_id,
        'start_date' => $request->start_date,
    ]);

    return redirect()->back()->with('success', 'Teacher on duty assigned successfully.');
}


    public function unassign(Request $request, $teacherOnDutyId)
    {
        $teacherOnDuty = TeacherOnDuty::find($teacherOnDutyId);
        if (! $teacherOnDuty) {
            return redirect()->back()->with('error', 'Not found.');
        }
        $teacherOnDuty->end_date = $request->end_date;
        $teacherOnDuty->save();
        return redirect()->back()->with('success', 'Teacher on duty Unassigned successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    public function search(Request $request)
    {
if (!$request->filled('name') && !$request->filled('company_id')) {
    return redirect()->back()->with('error','At least one of name, force number or company is required.');
}

$staffs = Staff::query();

if ($request->filled('company_id')) {
    $staffs->where('company_id', $request->company_id);
}

if ($request->filled('name')) {
    $staffs->whereIn('rank', ['CPL', 'PC'])
        ->where(function ($query) use ($request) {
            $query->where('firstName', 'like', '%' . $request->name . '%')
                ->orWhere('lastName', 'like', '%' . $request->name . '%')
                ->orWhere('middleName', 'like', '%' . $request->name . '%')
                ->orWhere('forceNumber', 'like', '%' . $request->name . '%');
        });
}

$staffs = $staffs->orderBy('firstName');


        $companies = Company::has('staffs')->get();
        $teachers  = TeacherOnDuty::whereNull('end_date')->orderBy('company_id', 'asc')->get();

        // 👇 Append search parameters to pagination
        $staffs = $staffs->paginate(10)->appends($request->all());

        return view('teacher_on_duty.index', compact('staffs', 'companies', 'teachers'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

}
