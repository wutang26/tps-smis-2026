<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\MPS;
use App\Models\LeaveRequest;
use App\Events\NotificationEvent;
use App\Models\NotificationAudience;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\AuditLoggerService;

class MPSController extends Controller
{
    private $selectedSessionId;
    public function __construct()
    {

        $this->middleware('permission:mps-list|mps-create|mps-edit|mps-delete', ['only' => ['index']]);
        $this->middleware('permission:mps-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:mps-edit', ['only' => ['edit', 'update', 'release']]);
        $this->middleware('permission:mps-delete', ['only' => ['destroy']]);
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId) {
            $this->selectedSessionId = 1;
        }

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedSessionId = $this->selectedSessionId;
        $mpsStudents = MPS::whereNull('released_at')
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->get();
        /*$student = $mpsStudents->first();
        $audience = NotificationAudience::find(1);
        $student->title ="Locked in.";
        $student->category ="mps";

        broadcast(new NotificationEvent(
            $student->id,   // ID from announcement
            $audience,                // Audience object or instance
            1,  // Notification type
            2,                        // Category (ensure 1 is a valid category ID)
            "Locked in.", // Title of the notification
            $student,           // Full announcements object
            "body"  // Body of the notification
        ))->toOthers();*/
        return view('mps.index', compact('mpsStudents'));
    }

    public function all()
    {
        $selectedSessionId = $this->selectedSessionId;
        $mpsStudents = MPS::orderBy('created_at', 'desc')->whereHas('student', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
            ->get();
        $scrumbName = "All";
        return view('mps.index', compact('mpsStudents', 'scrumbName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $selectedSessionId = $this->selectedSessionId;
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })->get();
        return view('mps.search', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $student_id)
    {
        $student = Student::find($student_id);
        if (!$student) {
            abort(404);
        }

        $mpsStudentData = $student->mps;
        if ($mpsStudentData) {
            foreach ($mpsStudentData as $data) {
                if (!$data->released_at) {
                    return redirect()->route('mps.create')->with('error', 'Student  not released yet.');
                }
            }
        }
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'arrested_at' => 'required||date',
            //'days' => 'required|numeric'
        ]);

        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        MPS::create([
            'added_by' => Auth::user()->id,
            'student_id' => $student->id,
            'description' => $request->description,
            'previous_beat_status' => $student->beat_status,
            'arrested_at' => $request->arrested_at,
        ]);

        $student->beat_status = 6;
        $student->save();
        $audience = NotificationAudience::find(1);
        $student->title = "Student Locked in.";
        $student->category = "mps";

        broadcast(new NotificationEvent(
            $student->id,   // ID from announcement
            $audience,                // Audience object or instance
            1,  // Notification type
            2,                        // Category (ensure 1 is a valid category ID)
            "Student Locked in.", // Title of the notification
            $student,           // Full announcements object
            "body"  // Body of the notification
        ));
        return redirect()->route('mps.index')->with('success', 'Student recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($studentId)
    {
        $mpsStudents = MPS::where('student_id', $studentId)->get();
        return view('mps.show', compact('mpsStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MPS $mPS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $mPSStudentId, AuditLoggerService $auditLogger)
    {

        $mPSstudent = MPS::find($mPSStudentId);
        if (!$mPSstudent) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'description' => 'required|alpha',
            'arrested_at' => 'required||date',
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $mPSstudentSnapshot = $mPSstudent;
        
        $mPSstudent->arrested_at = $request->arrested_at;
        $mPSstudent->description = $request->description;
        $mPSstudent->save();
        $auditLogger->logAction([
            'action' => 'update_mps_visitor',
            'target_type' => 'mPSstudent',
            'target_id' => $mPSstudentSnapshot->id,
            'metadata' => [
                'title' => $mPSstudentSnapshot->names,
            ],
            'old_values' => [
                'mPSstudent' => $mPSstudentSnapshot,
            ],
            'new_values' => [
                'mPSstudent' => $mPSstudent,
            ],
            'request' => $request,
        ]);
        return redirect()->back()->with('success', 'MPS student record updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mPS,Request $request, AuditLoggerService $auditLogger)
    {
        $mPSstudent = MPS::find($mPS);
        if (!$mPSstudent) {
            abort(404);
        }
   
        $mPSstudentSnapshot = $mPSstudent;
        $mPSstudent->delete();

        $auditLogger->logAction([
            'action' => 'delete_mps_visitor',
            'target_type' => 'mPSstudent',
            'target_id' => $mPSstudentSnapshot->id,
            'metadata' => [
                'title' => $mPSstudentSnapshot->names,
            ],
            'old_values' => [
                'mPSstudent' => $mPSstudentSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->back()->with('success', 'MPS student record deleted succesfully.');

    }

    public function search(Request $request)
    {
        $selectedSessionId = $this->selectedSessionId;
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })->get();
        $students = Student::where('platoon', $request->platoon)
            ->where('company_id', $request->company_id)
            ->where('session_programme_id', $selectedSessionId)

            // Exclude students still in MPS (no release date)
            ->whereNotIn('id', function ($q) use ($request) {
                $q->select('student_id')
                    ->from('m_p_s')
                    ->where('platoon', $request->platoon)
                    ->whereNull('released_at'); // still locked up
            })

            // Exclude students still on leave (active leave period + no return date)
            ->whereNotIn('id', function ($q) use ($request) {
                $q->select('student_id')
                    ->from('leave_requests')
                    ->where('platoon', $request->platoon)
                    ->whereDate('start_date', '<=', Carbon::today())   // leave started
                    ->where(function ($q2) {
                        $q2->whereDate('end_date', '>=', Carbon::today())     // leave not finished
                            ->orWhereNull('return_date'); // only exclude if not returned
                    });                       // not returned yet
            });
        if ($request->name) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }
        $students = $students->get();
        return view('mps.search', compact('students', 'companies'));
    }

    private function searchStudent($company_id, $platoon, $name = null)
    {
        $students = Student::where('platoon', $platoon)->where('company_id', $company_id)->where('session_programme_id', $this->selectedSessionId); //orWhere('last_name', 'like', '%' . $request->last_name . '%')->get();
        if ($name) {
            $students = $students->where(function ($query) use ($name) {
                $query->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%')
                    ->orWhere('middle_name', 'like', '%' . $name . '%');
            });
        }
        $students = $students->get();
        return $students;
    }

    public function release(Request $request, $mPSstudent)
    {
        $mPSstudent = MPS::find($mPSstudent);
        if (!$mPSstudent) {
            abort(404);
        }
        $mPSstudent->released_at = Carbon::now();
        $mPSstudent->days = Carbon::parse($mPSstudent->arrested_at)->diffInDays(Carbon::now());
        $student = $mPSstudent->student;
        $mPSstudent->release_reason = $request->reason;
        $student->beat_status = $mPSstudent->previous_beat_status;
        $student->save();
        $mPSstudent->save();
        $audience = NotificationAudience::find(1);
        $student->title = "Student released form lockup.";
        $student->category = "mps";

        broadcast(new NotificationEvent(
            $student->id,   // ID from announcement
            $audience,                // Audience object or instance
            1,  // Notification type
            2,                        // Category (ensure 1 is a valid category ID)
            "Student released form lockup.", // Title of the notification
            $student,           // Full announcements object
            "body"  // Body of the notification
        ));
        return redirect()->back()->with('success', 'Student released successfuly.');
    }

    public function company($companyId)
    {
        $company = Company::find($companyId);
        $mpsStudents = $company->lockUp->whereNull('released_at');
        $scrumbName = $company->description;
        return view('mps.index', compact('mpsStudents', 'scrumbName'));

    }
}
