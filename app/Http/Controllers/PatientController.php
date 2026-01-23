<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ExcuseType;
use App\Models\Patient;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hospital-create')->only([
            'save',
            'sendToReceptionist',
            // 'index',
            'sirMajorStatistics',
        ]);

        $this->middleware('permission:hospital-list')->only([
            'index',
            'doctorPage',
        ]);

        $this->middleware('permission:hospital-approve')->only([
            'receptionistIndex',
            'approvePatient',
        ]);

        $this->middleware('permission:hospital-edit')->only([
            'saveDetails',
            'doctorPage',
        ]);

        $this->middleware('permission:student-list')->only([
            'index',
        ]);
    }

 public function index(Request $request)
{
    $dailyCount = $weeklyCount = $monthlyCount = 0;
    $user       = Auth::user();

    $companies = $user->hasRole(['Sir Major', 'OC Coy', 'Instructor'])
        ? [$user->staff->company]
        : Company::all();

    $companyId = $request->input('company_id');
    $date      = $request->has('date') ? Carbon::parse($request->date) : now();

    if ($companyId) {
        $company = Company::find($companyId);
        if ($company) {
            $companies = [$company];
        }
    }

    foreach ($companies as $company) {
        $admittedNotReleasedIds = $company->sickStudents()
            ->where('excuse_type_id', 3)
            ->whereNull('released_at')
            ->pluck('id');

        $dailyIds = $company->sickStudents()
            ->whereDate('created_at', Carbon::today())
            ->pluck('id');

        $weeklyIds = $company->sickStudents()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->pluck('id');

        $monthlyIds = $company->sickStudents()
            ->whereMonth('created_at', now()->month)
            ->pluck('id');

        $dailyCount += $admittedNotReleasedIds->merge($dailyIds)->unique()->count();
        $weeklyCount += $admittedNotReleasedIds->merge($weeklyIds)->unique()->count();
        $monthlyCount += $admittedNotReleasedIds->merge($monthlyIds)->unique()->count();
    }

    // Patient distribution
    if ($companyId) {
        $patientDistribution = Patient::where('company_id', $companyId)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->selectRaw('platoon, COUNT(*) as count')
            ->groupBy('platoon')
            ->pluck('count', 'platoon');

        $isCompanySelected = true;
    } else {
        if ($user->hasRole(['Sir Major', 'OC Coy', 'Instructor'])) {
            $patientDistribution = Patient::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->selectRaw('platoon, COUNT(*) as count')
                ->groupBy('platoon')
                ->pluck('count', 'platoon');

            $isCompanySelected = true;
        } else {
            $patientDistribution = Patient::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->selectRaw('company_id, COUNT(*) as count')
                ->groupBy('company_id')
                ->pluck('count', 'company_id');

            $isCompanySelected = false;
        }
    }

    // Prepare month options
    $months = collect(range(2, 0))->map(fn($i) => Carbon::now()->subMonths($i));

    // Students list with search
    $students = null;
    
    if ($isCompanySelected) {
        $selectedCompany = $company; // Selected or role-restricted company
        $studentsQuery = $company->students();
        $programmeId = session('selected_session',1);
        if ($request->filled('platoon')) {
            $studentsQuery->where('platoon', $request->input('platoon'))->where('session_programme_id',$programmeId);
        }

        if ($request->filled('name')) {
            $name = $request->input('name');
            $studentsQuery->where(function ($query) use ($name) {
                $query->where('first_name', 'like', "%$name%")
                    ->orWhere('middle_name', 'like', "%$name%")
                    ->orWhere('last_name', 'like', "%$name%");
            });
        }

        $students = $studentsQuery->paginate(50)->appends($request->query());
    }

    return view('hospital.index', compact(
        'companies', 'dailyCount', 'weeklyCount', 'monthlyCount',
        'patientDistribution', 'isCompanySelected', 'months', 'students'
    ))->with('date', $date->format('F Y'));
}




    public function show($id)
    {
        // Fetch patient details from the database
        $patient = Patient::findOrFail($id);

        // Pass the patient data to the view
        return view('hospital.show', compact('patient'));
    }

    public function showPatient($id)
    {
        // Fetch patient details from the database
        $patients = Patient::where('student_id', $id)->get();

        // Pass the patient data to the view
        return view('hospital.show', compact('patients'));
    }

    public function sendToReceptionist(Request $request)
    {
        \Log::info('sendToReceptionist method called.');
        \Log::info('Received student_id: ' . $request->student_id);

        // Validate input
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        // Debug query
        $student = Student::where('id', $request->student_id)->first();
        \Log::info('Student Query Result: ' . json_encode($student));

        if (! $student) {
            \Log::error('Student with ID ' . $request->student_id . ' not found!');
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Store patient details
        Patient::updateOrCreate(
            ['student_id' => $student->id],
            [
                'company_id' => $student->company_id,
                'platoon'    => $student->platoon,
                'status'     => 'pending',
            ]
        );

        return redirect()->route('hospital.index')->with('success', 'Details sent to receptionist for approval.');
    }

    public function sendForApproval(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::findOrFail($request->student_id);

        // Check if already pending
        if (Patient::where('student_id', $student->id)->where('status', 'pending')->exists()) {
            return response()->json(['message' => 'Patient details already sent for approval.'], 400);
        }

        // Create new patient record for approval
        Patient::create([
            'student_id' => $student->id,
            'company_id' => $student->company_id,
            'platoon'    => $student->platoon,
            'status'     => 'pending',
        ]);

        return response()->json(['message' => 'Patient details sent for approval successfully.']);
    }

    public function receptionistIndex()
    {
        // Ensure only users with the "Receptionist" role can access
        if (! auth()->user()->hasRole('Receptionist')) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch only patients who are pending approval
        $patients = Patient::where('status', 'pending')
            ->with('student') // Load the related student details
            ->get();

        return view('receptionist.index', compact('patients'));
    }

    public function approvePatient(Request $request, $id)
    {
        // Find the patient by ID
        $patient = Patient::findOrFail($id);

        // Update the patient's status to 'approved'
        $patient->status = 'approved';
        $patient->save();

        return redirect()->route('receptionist.index')->with('success', 'Patient approved and forwarded to the doctor.');
    }

    public function receptionistPage()
    {
        // Fetch patients that need approval by the receptionist
        $patients = Patient::where('status', 'pending')->get();

        // Return the receptionist view
        return view('receptionist.index', compact('patients'));
    }

    public function doctorPage()
    {
        if (! (auth()->user()->hasRole('Doctor') || auth()->user()->hasRole('Super Administrator'))) {
            abort(403, 'You do not have access to this page.');
        }

        // Fetch approved patients and include related student details
        $patients = Patient::where('status', 'approved')
            ->with('student:id,first_name,last_name')
            ->get();

        // Fetch excuse names from excuse_types table
        $excuseTypes = ExcuseType::pluck('excuseName', 'id');

        return view('doctor.index', compact('patients', 'excuseTypes'));
    }

    public function saveDetails(Request $request)
    {
        // Log the incoming student_id
        \Log::info('Incoming student_id:', ['student_id' => $request->student_id]);

        $patient = Patient::find($request->student_id);

        if (! $patient) {
            return redirect()->back()->with('error', 'Patient record not found.');
        }

        // Proceed with saving details
        $patient->excuse_type_id = $request->excuse_type_id;
        $patient->doctor_comment = $request->doctor_comment;
        $patient->rest_days      = $request->rest_days;
        $patient->status         = 'treated';
        $patient->admitted_type  = $request->admitted_type ?? null;

        $patient->save();

        // Update beat_status in students table
        $student = Student::where('id', $patient->student_id)->first();
        if ($student) {
            $student->beat_status = 0;
            $student->save();
        }

        return redirect()->back()->with('success', 'Patient details saved successfully.');
    }

    public function viewDetails(Request $request, $timeframe)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        if (! in_array($timeframe, ['daily', 'weekly', 'monthly'])) {
            abort(404, 'Invalid timeframe');
        }

        // Fetch filters
        $company_id = $request->input('company_id', $user->company_id);
        $platoon    = $request->input('platoon');

        // Start base query
        $query = Patient::query();

        // Apply filters
        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        if ($user->hasRole(['Sir Major'])) {
            $query->where('company_id', $user->staff->company_id);
        }
        // if ($platoon) {
        //     $query->where('platoon', $platoon);
        // }

        // Apply timeframe filter
        switch ($timeframe) {
            case 'daily':
                $query->where(function ($q) {
                    $q->whereDate('created_at', Carbon::today())
                        ->orWhere(function ($q2) {
                            $q2->where('excuse_type_id', 3)
                                ->whereNull('released_at');
                        });
                });
                break;
            case 'weekly':
                $query->where(function ($q) {
                    $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()])
                        ->orWhere(function ($q2) {
                            $q2->where('excuse_type_id', 3)
                                ->whereNull('released_at');;
                        });
                });
                break;

            case 'monthly':
                $query->where(function ($q) {
                    $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])
                        ->orWhere(function ($q2) {
                            $q2->where('excuse_type_id', 3)
                                ->whereNull('released_at');
                        });
                });
                break;

        }

        // Fetch patients
        $patients = $query->get();

        return view('hospital.viewDetails', compact('patients', 'timeframe', 'company_id', 'platoon'));
    }

// public function dispensaryPage(Request $request)
// {
//     $query = Patient::query();

//     // Filter by company_id if provided
//     if ($request->filled('company_id')) {
//         $query->where('company_id', $request->company_id);
//     }

//     // Filter by platoon if provided
//     if ($request->filled('platoon')) {
//         $query->where('platoon', $request->platoon);
//     }

//     $today = Carbon::today();
//     $thisWeek = Carbon::now()->startOfWeek();
//     $thisMonth = Carbon::now()->startOfMonth();
//     $thisYear = Carbon::now()->startOfYear();

//     // Count statistics based on the selected filters
//     $dailyCount = (clone $query)->whereDate('created_at', $today)->count();
//     $weeklyCount = (clone $query)->whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
//     $monthlyCount = (clone $query)->whereBetween('created_at', [$thisMonth, Carbon::now()])->count();

//     // Fetch list of companies
//     $companies = Company::all();

//     // Patient distribution for the selected year (used in Pie Chart)
//     $patientDistribution = (clone $query)
//         ->whereBetween('created_at', [$thisYear, Carbon::now()])
//         ->selectRaw('platoon, COUNT(*) as count')
//         ->groupBy('platoon')
//         ->pluck('count', 'platoon');

//     return view('dispensary.index', compact('dailyCount', 'weeklyCount', 'monthlyCount', 'patientDistribution', 'companies'));
// }
    public function dispensaryPage(Request $request)
    {
        $dailyCount = $weeklyCount = $monthlyCount = 0;
        $user       = Auth::user();
        $companies  = [];
        if ($user->hasRole(['Sir Major', 'OC Coy', 'Instructor'])) {
            $companies = [$user->staff->company];
        } else {
            $companies = Company::all();

        }

        $companyId = $request->input('company_id');
        $platoon   = $request->input('platoon');
        if ($companyId) {
            $companies = [Company::find($companyId)];
        }

        foreach ($companies as $company) {
            $admittedNotReleasedCount = $company->sickStudents->where('excuse_type_id', 3)->whereNull('released_at')->pluck('id');
            $daily                    = $company->sickStudents()->whereDate('created_at', Carbon::today())->pluck('id');
            $dailyCount += $admittedNotReleasedCount->merge($daily)->unique()->count();

            $weekly = $company->sickStudents()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->pluck('id');
            $weeklyCount += $admittedNotReleasedCount->merge($weekly)->unique()->count();

            $monthly = $company->sickStudents()->whereMonth('created_at', now()->month)->pluck('id');
            $monthlyCount += $admittedNotReleasedCount->merge($monthly)->unique()->count();
        }
        // Fetch counts
        // $admittedNotReleasedCount = Patient::where('excuse_type_id', 3)->whereNull('is_discharged')->pluck('id');
        // $daily                    = Patient::whereDate('created_at', Carbon::today())->pluck('id');
        // $dailyCount               = $admittedNotReleasedCount->merge($daily)->unique()->count();
        // $weekly                   = Patient::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->pluck('id');
        // $weeklyCount              = $admittedNotReleasedCount->merge($weekly)->unique()->count();
        // $monthly                  = Patient::whereMonth('created_at', now()->month)->pluck('id');
        // $monthlyCount             = $admittedNotReleasedCount->merge($monthly)->unique()->count();
        if ($companyId) {
            // If a company is selected, show platoon-based statistics
            $patientDistribution = Patient::where('company_id', $companyId)
                ->selectRaw('platoon, COUNT(*) as count')
                ->groupBy('platoon')
                ->whereMonth('created_at', $request->has('date')? Carbon::parse($request->date)->month : now()->month)
                ->whereYear('created_at', $request->has('date')? Carbon::parse($request->date)->year : now()->year)
                ->pluck('count', 'platoon');

            $isCompanySelected = true;
        } else {
            // Default: Show statistics grouped by company (HQ, A, B, C)
            if ($user->hasRole(['Sir Major', 'OC Coy', 'Instructor'])) {
                $patientDistribution = Patient::selectRaw('platoon, COUNT(*) as count')
                ->whereMonth('created_at', $request->has('date')? Carbon::parse($request->date)->month : now()->month)
                ->whereYear('created_at', $request->has('date')? Carbon::parse($request->date)->year : now()->year)
                    ->groupBy('platoon')
                    ->pluck('count', 'platoon');
                $isCompanySelected = true;

            } else {
                $patientDistribution = Patient::selectRaw('company_id, COUNT(*) as count')
                    ->groupBy('company_id')
                    ->whereMonth('created_at', $request->has('date')? Carbon::parse($request->date)->month : now()->month)
                    ->whereYear('created_at', $request->has('date')? Carbon::parse($request->date)->year : now()->year)
                    ->pluck('count', 'company_id');
                $isCompanySelected = false;
            }

        }
        $months = [];

        for ($i = 2; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i); // e.g. "April 2025"
        }
        //return $request->has('date')? Carbon::parse($request->date)->month : now()->month;
        //$companies = Company::all();
        $date =$request->has('date')? Carbon::parse($request->date)->format('F Y'): now()->format('F Y');
        return view('dispensary.index', compact(
            'companies', 'dailyCount', 'weeklyCount', 'monthlyCount', 'patientDistribution', 'isCompanySelected','months','date'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:Admitted,Excuse Duty,Light Duty',
            'rest_days'  => 'required|integer|min:1',
        ]);

        $patient             = new Patient();
        $patient->student_id = $request->student_id;
        $patient->company_id = $request->company_id;
        $patient->platoon    = $request->platoon;
        $patient->status     = $request->status;
        $patient->rest_days  = $request->rest_days;
        $patient->save();

        // Update the student's beat_status to 0 if they are sick
        Student::where('id', $request->student_id)->update(['beat_status' => 0]);

        return back()->with('success', 'Patient record added successfully.');
    }

    public function updateSickReport(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);

        // Check if the student is a patient
        if (in_array($request->status, ['Light Duty', 'Excuse Duty', 'Admitted'])) {
            // Set beat_status to 0 and define sick period
            $student->beat_status = 0;
            $student->rest_days   = $request->rest_days;
            $student->sick_until  = Carbon::now()->addDays($request->rest_days);
        }

        $student->save();
    }

// public function discharge($id)
// {
//     $patient = Patient::findOrFail($id);

//     if ($patient->is_discharged) {
//         return response()->json(['success' => false, 'message' => 'Patient already discharged.']);
//     }

//     $patient->is_discharged = true;
//     $patient->discharged_date = now();
//     $patient->save();

//     // Reset student's beat status
//     if ($patient->student) {
//         $patient->student->beat_status = 1;
//         $patient->student->save();
//     }

//     return response()->json(['success' => true, 'message' => 'Patient discharged successfully.']);
// }

    public function downloadStatisticsReport(Request $request, $timeframe)
    {
        $company_id = $request->input('company_id');
        $platoon    = $request->input('platoon');

        // Query for patient details based on timeframe
        $query = Patient::query()->whereYear('created_at', now()->year);

        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        if ($platoon) {
            $query->where('platoon', $platoon);
        }

        switch ($timeframe) {
            case 'daily':
                $query->whereDate('created_at', now());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()]);
                break;
            case 'monthly':
                $query->whereBetween('created_at', [now()->startOfMonth(), now()]);
                break;
        }

        $patients = $query->get();

        // ✅ Fix: Count total patients without modifying the query
        $totalPatientsPresent = $patients->count();

        // ✅ Fix: Count excuse types separately without affecting the main query
        $excuseDutyCount = $patients->where('excuse_type', 'Excuse Duty')->count();
        $lightDutyCount  = $patients->where('excuse_type', 'Light Duty')->count();
        $admittedCount   = $patients->whereIn('excuse_type', ['Referral', 'Internal', 'Admitted'])->count();

        // Fetch students with 5+ excuses
        $frequentExcuses = Student::select('students.first_name', 'students.last_name', 'patients.platoon')
            ->join('patients', 'students.id', '=', 'patients.student_id')
            ->selectRaw('COUNT(patients.excuse_type_id) as excuse_count')
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'patients.platoon')
            ->having('excuse_count', '>=', 5)
            ->orderByDesc('excuse_count')
            ->get();

        // Load PDF view with all required data
        $pdf = Pdf::loadView('pdf.statistics', compact(
            'patients',
            'frequentExcuses',
            'totalPatientsPresent',
            'excuseDutyCount',
            'lightDutyCount',
            'admittedCount',
            'timeframe',
            'company_id',
            'platoon'
        ));

        return $pdf->download('statistics_report.pdf');
    }

    public function admitted(Request $request)
    {
        // Start the query properly
        $query = Patient::with('student', 'excuseType')
            ->whereHas('excuseType', function ($q) {
                $q->where('excuse_type_id', 3);
            })
            ->whereIn('admitted_type', ['Referral', 'Internal'])->whereNull('released_at');

        // Filter: Search by name
        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%');

            });
        }

        // Filter: Company
        if ($request->filled('company_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // Filter: From Date
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        // Filter: To Date
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Final list
        $admittedPatients = $query->latest()->get();

        // Sort by latest and paginate (10 per page)
        $admittedPatients = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());
        return view('doctor.admitted', compact('admittedPatients'));
    }

    public function discharge($id)
    {
        $patient = Patient::findOrFail($id);

        if ($patient->is_discharged) {
            return redirect()->back()->with('error', 'Patient already discharged.');
        }

        $patient->is_discharged = true;
        $patient->released_at   = now();
        $patient->save();

        // Restore student's beat_status back to active (1)
        if ($patient->student) {
            $student = $patient->student;

            // Only update if currently sick (beat_status = 0)
            if ($student->beat_status == 0) {
                $student->beat_status = 1; // assuming 1 is the active status (anaweza kuingia lindo)
                $student->save();
            }
        }
        return redirect()->back()->with('success', 'Patient discharged successfully.');
    }

}
