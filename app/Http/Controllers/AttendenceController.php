<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\AttendanceRequest;
use App\Models\Attendence;
use App\Models\AttendenceType;
use App\Models\Company;
use App\Models\CompanyAttendance;
use App\Models\MPS;
use App\Models\NotificationAudience;
use App\Models\Patient;
use App\Models\Platoon;
use App\Models\Student;
use App\Models\StudentDismissal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendenceController extends Controller
{
    private $user;

    private $companies;

    private $selectedSessionId;

    public function __construct()
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }

        $this->selectedSessionId = session('selected_session');
        if (! $this->selectedSessionId) {
            $this->selectedSessionId = 1;
        }

        $this->middleware('permission:attendance-list|attendance-create|attendance-edit|attendance-delete', ['only' => ['index', 'today', 'attendence']]);
        $this->middleware('permission:attendance-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:attendance-delete', ['only' => ['destroy']]);

    }

    public function index(Request $request)
    {
        $companyId = $request->company_id ?? null;

        return redirect()->route('attendences.type', [
            'type' => 1,
            'company' => $companyId, // this becomes a query parameter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $attendenceType_id)
    {
        $date = $request->date;
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }

        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        $attendenceType = AttendenceType::find($attendenceType_id);
        $platoon = Platoon::find($request->platoon);
        $lockUp = $this->getLockUpStudentsIds($platoon);
        $kazini = $this->getKaziniStudentsIds($platoon, $request->date);
        $sick_ids = $this->getSickStudentIds($platoon);
        $adm = $this->getAdmStudentIds($platoon);
        $ed = $this->getEdStudentIds($platoon);
        $safari_ids = $this->getSafariStudentIds($platoon, $request->date);

        $students = $platoon->students()->where('session_programme_id', $selectedSessionId)
            ->where('company_id', $platoon->company_id)
            ->whereDoesntHave('dismissal') // exclude dismissed students
            ->whereNotIn('id', $lockUp->toArray())
            ->whereNotIn('id', $kazini)
            ->whereNotIn('id', $ed)
            ->whereNotIn('id', $adm)
            ->whereNotIn('id', $safari_ids)
            ->get();

        return view(
            'attendences/create',
            compact('students', 'attendenceType', 'platoon', 'date')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendence $attendence) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        $attendenceTypes = AttendenceType::all();
        $platoon = $attendence->platoon->id;

        return view('attendences/create', compact('platoon', 'attendence', 'attendenceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $attendence_id)
    {

        $total = 0;
        $total = $total + $request->present + $request->absent + $request->sentry + $request->adm;
        $total = $total + $request->safari + $request->off + $request->mess;

        $attendence = Attendence::find($attendence_id);
        $attendence->update(
            [
                'present' => $request->present,
                'sentry' => $request->sentry,
                'absent' => $request->absent,
                'adm' => $request->adm,
                'safari' => $request->safari,
                'off' => $request->off,
                'mess' => $request->mess,
                'female' => $request->female,
                'male' => $request->male,
                'total' => $total,
            ]
        );

        return redirect()->route('attendences.index')->with('success', 'Attendences updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendence $attendence)
    {
        //
    }

    /**
     * List all attendence for all companies
     */
    public function today($company_id, $type, $date, $attendenceTypeId)
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

        $attendences = new \Illuminate\Database\Eloquent\Collection;
        $company = Company::find($company_id);
        $page = AttendenceType::find($type);
        $date = Carbon::parse($date)->format('Y-m-d');
        foreach ($company->platoons as $platoon) {
            if ($platoon->attendences->isNotEmpty()) {
                $platoon_attendences = $platoon->attendences()->where('attendenceType_id', $type)->whereDate('date', $date)->where('session_programme_id', $selectedSessionId)->get();
                $absent_students = new \Illuminate\Database\Eloquent\Collection;
                $safari_students = new \Illuminate\Database\Eloquent\Collection;

                foreach ($platoon_attendences as $att) {

                    $absent_students_ids = json_decode($att->absent_student_ids);
                    // dd($absent_students_ids == NULL);
                    $att->absent_students = $absent_students_ids != null ? Student::whereIn('id', $absent_students_ids)->get() : [];
                    $safari_students_ids = explode(',', $att->safari_student_ids);

                    // return $att->absent_students;
                    // Safari students
                    if (count($safari_students_ids) > 0) {
                        for ($i = 0; $i < count($safari_students_ids); $i++) {
                            $student = Student::find($safari_students_ids[$i]);
                            $safari_students->push($student);
                        }
                        $att->safari_students = $safari_students;
                        $attendences->push($att);
                    }

                }

            }

        }
        $attendenceType = AttendenceType::find($attendenceTypeId);

        // dd($attendences);
        return view('attendences.attended', compact('attendences', 'page', 'company', 'date', 'attendenceType'));
    }

    protected function statistics($attendence, $company_id)
    {
        $data = new \Illuminate\Database\Eloquent\Collection;
        $present = 0;
        $absent = 0;
        $total = 0;
        $sick = 0;
        $safari = 0;
        $off = 0;
        $mps = 0;
        for ($i = 0; $i < count($attendence); $i++) {
            if (count($attendence[$i]) > 0) {
                $present += $attendence[$i][0]['present'];
                $absent += $attendence[$i][0]['absent'];
                $safari += $attendence[$i][0]['safari'];
                $total += $attendence[$i][0]['total'];
                $sick += $attendence[$i][0]['adm'];
                $sick += $attendence[$i][0]['ed'];
            } else {

            }
        }

        $mps = $this->getMPSdata($company_id);
        $data->put('present', $present);
        $data->put('absent', $absent);
        $data->put('total', $total - $mps);
        $data->put('sick', $sick);
        $data->put('safari', $safari);
        $data->put('mps', $mps);

        return $data;
    }

    protected function setZero($company_id)
    {
        return [
            'present' => 0,
            'absent' => 0,
            'total' => 0,
            'sick' => 0,
            'safari' => 0,
            'mps' => $this->getMPSdata($company_id),
        ];
    }

    public function testAttendence($type)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        $attendenceType = AttendenceType::find($type);
        $attendences = new \Illuminate\Database\Eloquent\Collection;
        $data = [];
        $statistics = new \Illuminate\Database\Eloquent\Collection;
        $companies = Company::all();
        foreach ($companies as $company) {
            $hasAttendence = false;
            $platoon_attendence = new \Illuminate\Database\Eloquent\Collection;
            foreach ($company->platoons as $platoon) {
                if (! empty($platoon->attendences)) {

                    $att = $platoon->attendences()
                        ->where('attendenceType_id', $type)->whereDate('created_at', Carbon::today())->get();
                    if ($att) {
                        $platoon_attendence->put($platoon->name, $att);
                        $hasAttendence = true;
                    }
                }
            }
            if ($hasAttendence) {
                $attendences->put($company->name, $platoon_attendence);
                $hasAttendence = false;
            }
        }
        foreach ($companies as $company) {
            $statistics->put($company->name, $this->statistics($attendences[$company->id], $company->name));
        }

        return view('attendences/index', compact('statistics', 'companies', 'attendenceType'));
    }

    public function attendence(Request $request, $type)
    {
        $companyId = $request->input('companyId') ?? null;
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }

        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        if (! $request->date) {
            $date = Carbon::today();
        } else {
            $date = $request->date;
        }
        $date = Carbon::parse($date)->format('Y-m-d');
        $attendenceType = AttendenceType::find($type);
        $user = Auth::user();
        $roles = Auth::user()->roles;

        if ($user->hasRole(['Teacher', 'Instructor', 'OC Coy']) || $user->hasRole('Sir Major')) {
            $this->companies = collect([$user->staff->company]);
            if (count($this->companies) != 0) {
                if ($this->companies[0] == null) {
                    return view('attendences/index', compact('attendenceType', 'date'));
                }
            }

        } elseif ($user->hasAnyRole(['Admin', 'Academic Coordinator', 'Super Administrator', 'Chief Instructor', 'Staff Officer'])) {
            $selectedSessionId = session('selected_session');
            if (! $selectedSessionId) {
                $selectedSessionId = 1;
            }

            $this->companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })->get();

        }
        $statistics = [];

        foreach ($this->companies as $company) {
            $company_stats = [];
            $company->platoons = $company->platoons()
                ->whereHas('students', function ($query) use ($selectedSessionId) {
                    $query->where('session_programme_id', $selectedSessionId);
                })->get()->unique('id');

            foreach ($company->platoons as $platoon) {
                if (count($platoon->attendences()->whereDate('date', $date)->where('session_programme_id', $selectedSessionId)->where('attendenceType_id', $type)->get()) > 0) {
                    array_push($company_stats, $platoon->attendences()->whereDate('date', $date)->where('session_programme_id', $selectedSessionId)->where('attendenceType_id', $type)->get());
                }
            }

            array_push($statistics, [
                'company' => $company,
                'statistics' => count($company_stats) > 0 ? $this->statistics($company_stats, $company->id) : $this->setZero($company->id),
            ]);
        }
        $companies = $this->companies;

        $companyId = $request->companyId;
        // Use fallback if null, string "null", or invalid
        $selectedCompany = (is_numeric($companyId) && Company::find($companyId))
        ? Company::findOrFail($companyId)
        : $this->companies->first();

        return view('attendences/index', compact('statistics', 'companies', 'attendenceType', 'date', 'selectedCompany'));

    }

    public function getMPSdata($company_id)
    {
        $mpsStudents = MPS::WhereNull('released_at')->get();
        $count = 0;
        foreach ($mpsStudents as $mpsStudent) {
            if ($mpsStudent->student->company_id == $company_id) {
                $count++;
            }
        }

        return $count;
    }

    public function list($list_type, $attendence_id, $date)
    {
        $attendence = Attendence::find($attendence_id);
        $students = $attendence->platoon->students()->where('session_programme_id', $this->selectedSessionId)->orderBy('first_name')->get();
        $absent_student_ids = explode(',', $attendence->absent_student_ids);

        // $students = Company::join('students', 'companies.name', 'students.company')->join('platoons', 'platoons.id', 'students.platoon')
        //     ->where('students.company', 'HQ')->where('students.platoon', '1')->get('students.*');
        return view('attendences.select_absent', compact('students', 'attendence_id', 'list_type', 'absent_student_ids', 'date'));
    }

    public function list_safari($list_type, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        $students = $attendence->platoon->students;

        return view('attendences.select_safari', compact('students', 'attendence_id', 'list_type'));
    }

    public function storeAbsent(Request $request, $attendence_id, $date)
    {
        $attendence = Attendence::find($attendence_id);
        if (! $attendence) {
            abort(404);
        }
        $ids = $request->input('student_ids');
        $absent_student_ids = implode(',', $ids);
        $attendence->absent_student_ids = $absent_student_ids;
        $attendence->absent = count(value: $ids);
        $attendence->present = $attendence->total - $attendence->mps - $attendence->absent;
        $attendence->save();
        $page = $attendence->attendenceType;

        return $this->today($attendence->platoon->company_id, $attendence->attendenceType_id, $date)->with('page', $attendence->attendenceType);
    }

    public function storeSafari(Request $request, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        if (! $attendence) {
            abort(404);
        }
        $ids = $request->input('student_ids');
        $safari_student_ids = implode(',', $ids);
        $attendence->safari_student_ids = $safari_student_ids;
        $attendence->safari = count(value: $ids);
        $attendence->save();
        $page = $attendence->attendenceType;

        return $this->today($attendence->platoon->company_id, $attendence->attendenceType_id, Carbon::today())->with('page', $page);
    }

    public function getPlatoons($company_id)
    {
        $company = Company::find($company_id);

        return response()->json($company->platoons()->withCount('students')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $type, $platoon_id)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }

        $selectedSessionId = session('selected_session', 1);
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        $platoon = Platoon::find($platoon_id);
        if ($request->date != Carbon::today()->format('Y-m-d')) {
            $requestAttendances = AttendanceRequest::where('company_id', $platoon->company_id)
                ->where('date', $request->date)
                ->get();

            if ($requestAttendances->isEmpty()) {
                return redirect()->back()->with('info', 'No requested attendance for the specified date.');
            }

            if ($requestAttendances->contains('status', 'pending')) {
                return redirect()->back()->with('info', 'At least one request is still pending.');
            }
        }
        $ids = $request->input('student_ids');
        if ($ids == null) {
            return redirect()->to('attendences/type/'.$type)->with('error', 'Students must be selected.');
        }
        $attendenceType = AttendenceType::find($type);
        $present = count($ids);

        $students = Student::where('company_id', $platoon->company_id)
            ->where('session_programme_id', $selectedSessionId)
            ->where('platoon', $platoon->name)
            ->whereDoesntHave('dismissal') // exclude dismissed students
            ->pluck('id')->toArray();
        $female = Student::where('company_id', $platoon->company_id)
            ->where('session_programme_id', $this->selectedSessionId)
            ->where('platoon', $platoon->name)
            ->whereDoesntHave('dismissal') // exclude dismissed students
            ->where('gender', 'F')->count();
        $male = Student::where('company_id', $platoon->company_id)
            ->where('session_programme_id', $this->selectedSessionId)
            ->where('platoon', $platoon->name)
            ->whereDoesntHave('dismissal') // exclude dismissed students
            ->where('gender', 'M')->count();
        $lockUp = $this->getLockUpStudentsIds($platoon);
        $kazini = $this->getKaziniStudentsIds($platoon, $request->date);
        $sick_ids = $this->getSickStudentIds($platoon);
        $adm = $this->getAdmStudentIds($platoon);
        $ed = $this->getEdStudentIds($platoon);
        $safari_ids = $this->getSafariStudentIds($platoon, $request->date);

        $absent_ids = array_values(array_diff($students, $ids,
            $lockUp->toArray(),
            $kazini,
            // $sick_ids,
            $adm,
            $ed,
            $safari_ids));
        $total = count($students);
        $todayRecords = Attendence::leftJoin('platoons', 'attendences.platoon_id', 'platoons.id')
            ->where('attendences.platoon_id', $platoon_id)
            ->where('attendences.attendenceType_id', $type)
            ->where('attendences.date', $request->date)
            ->where('session_programme_id', $selectedSessionId)
            ->whereDate('attendences.date', Carbon::parse($request->date))->get();
        if (! $todayRecords->isEmpty()) {
            return redirect()->to('attendences/type/'.$type)->with('error', 'Attendences for this platoon already recorded.');
        }
        for ($i = 0; $i < count($absent_ids); $i++) {
            $student = Student::find($absent_ids[$i]);
            if ($student->gender == 'M') {
                $male -= 1;
            } elseif ($student->gender = 'F') {
                $female -= 1;
            }
        }

        // $hardcodedDate = Carbon::createFromFormat('d-m-Y', '8-6-2025');

        $type = AttendenceType::find($type);
        $attendence = Attendence::create([
            'attendenceType_id' => $type->id,
            'platoon_id' => $platoon_id,
            'present' => $present,
            'sentry' => 0,
            'absent' => count($absent_ids),
            'adm' => count($adm),
            'ed' => count($ed),
            'safari' => count($safari_ids),
            'off' => 0,
            'mess' => 0,
            'female' => $female,
            'male' => $male,
            'lockUp' => count($lockUp),
            'kazini' => count($kazini),
            'sick' => 0, // count($sick_ids),
            'session_programme_id ' => $selectedSessionId,
            'adm_student_ids' => count($adm) > 0 ? json_encode($adm) : null,
            'ed_student_ids' => count($ed) > 0 ? json_encode($ed) : null,
            'lockUp_students_ids' => count($lockUp) > 0 ? json_encode($lockUp) : null,
            'absent_student_ids' => count($absent_ids) > 0 ? json_encode($absent_ids) : null,
            'total' => $total,
            'recorded_by' => $request->user()->id,
            'date' => $request->date,
            // 'created_at' => $hardcodedDate,
            // 'updated_at' =>$hardcodedDate
        ]);

        $attendence->session_programme_id = $selectedSessionId;

        CompanyAttendance::updateOrCreate(
            [
                'company_id' => $platoon->company_id,
                'date' => $request->date,
                'attendenceType_id' => $type->id,
            ],
            [
                'company_id' => $platoon->company_id,
                'date' => $request->date,
                'attendenceType_id' => $type->id,
            ]
        );
        $attendence->save();

        $companyId = $platoon->company_id;

        return redirect()
            ->to('attendences/type/'.$type->id.'?companyId='.$companyId)
            ->with('success', 'Attendances saved successfully.');

    }

    public function day_report($companyId, $date)
    {
        $summary = [];
        $total_present = 0;
        $total_absent = 0;
        $total = 0;
        $company = Company::find($companyId);
        foreach ($company->platoons as $platoon) {
            $attendances = $platoon->attendences()->whereDate('created_at', $date)->get();
            if ($attendances->isNotEmpty()) {
                foreach ($attendances as $attendance) {
                    $summary[$platoon->id] = ['present' => $attendance->present, 'absent' => $attendance->absent, 'total' => $attendance->total];
                    $total_present += $attendance->present;
                    $total_absent += $attendance->absent;
                    $total += $attendance->total;
                }
            } else {
                $summary[$platoon->id] = ['present' => 0, 'absent' => 0, 'total' => 0];
            }
        }

        return [
            'summary' => $summary,
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total' => $total,
            'absent_percentage' => $total != 0 ? round(($total_absent / $total) * 100, 4) : 0,
        ];
    }

    public function current_week_report($companyId)
    {
        $company = Company::find($companyId);
        $summary = [];
        $total_present = $total_absent = $total = 0;

        // Determine the start and end dates of the current week
        $startDate = Carbon::now()->startOfWeek()->toDateString();
        $endDate = Carbon::now()->endOfWeek()->toDateString();

        foreach ($company->platoons as $platoon) {
            $platoon_summary = ['present' => 0, 'absent' => 0, 'total' => 0];

            $attendances = $platoon->attendences()->whereBetween('created_at', [$startDate, $endDate])->get();

            foreach ($attendances as $attendance) {
                $platoon_summary['present'] += $attendance->present;
                $platoon_summary['absent'] += $attendance->absent;
                $platoon_summary['total'] += $attendance->total;
                $total_present += $attendance->present;
                $total_absent += $attendance->absent;
                $total += $attendance->total;
            }

            $summary[$platoon->id] = $platoon_summary;
        }

        $absent_percentage = $total > 0 ? round(($total_absent / $total) * 100, 4) : 0;

        return [
            'summary' => $summary,
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total' => $total,
            'absent_percentage' => $absent_percentage,
        ];
    }

    public function report_for_date_range($companyId, $startDate, $endDate)
    {
        $company = Company::find($companyId);
        $summary = [];
        $total_present = $total_absent = $total = 0;

        foreach ($company->platoons as $platoon) {
            $platoon_summary = ['present' => 0, 'absent' => 0, 'total' => 0];

            $attendances = $platoon->attendences()->whereBetween('created_at', [$startDate, $endDate])->get();

            foreach ($attendances as $attendance) {
                $platoon_summary['present'] += $attendance->present;
                $platoon_summary['absent'] += $attendance->absent;
                $platoon_summary['total'] += $attendance->total;
                $total_present += $attendance->present;
                $total_absent += $attendance->absent;
                $total += $attendance->total;
            }

            $summary[$platoon->id] = $platoon_summary;
        }

        $absent_percentage = $total > 0 ? round(($total_absent / $total) * 100, 4) : 0;

        return [
            'summary' => $summary,
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total' => $total,
            'absent_percentage' => $absent_percentage,
        ];
    }

    public function generatePdf($companyId, $date, $attendenceTypeId)
    {
        $attendenceType = AttendenceType::find($attendenceTypeId);
        $company = Company::find($companyId);
        $sick_ids = [];
        foreach ($company->platoons as $platoon) {
            $sick_ids = array_merge($sick_ids, $this->getSickStudentIds($platoon));
        }
        // $sick_students = Student::where('company_id', $company->id)->where('session_programme_id', $this->selectedSessionId)->whereIn('id', $sick_ids)->get();
        // return view('attendences.daily_report', compact('company', 'date', 'sick_students'));
        $company = Company::find($companyId);
        $companyId = $company->id; // your specific company ID

        $attendances = Attendence::with('sessionProgramme')->where('attendenceType_id', $attendenceTypeId) // eager load sessionProgramme relationship
            ->whereIn('platoon_id', function ($query) use ($companyId) {
                $query->select('id')
                    ->from('platoons')
                    ->where('company_id', $companyId);
            })
            ->whereDate('date', $date)
            ->get()
            ->groupBy('session_programme_id');

        // Prepare an array to store session program attendance data
        $sessionProgrammeAttendance = [];

        // Loop through the grouped attendance data
        foreach ($attendances as $sessionProgrammeId => $records) {
            $session = $records->first()->sessionProgramme; // Get the first record's sessionProgramme relationship
            // Store the grouped data in the array
            $sessionProgrammeAttendance[] = [
                'session_programme_id' => $sessionProgrammeId,
                'session_programme' => [
                    'id' => $session->id,
                    'programme_name' => $session->session_programme_name, // Check field name for correct session program name
                    'year' => $session->year,
                    'startDate' => $session->startDate,
                    'endDate' => $session->endDate,
                ],
                'total_attendance_records' => $records->count(),  // Get the total number of attendance records for this session programme
                'attendances' => $records->values(), // Get all the attendance records for this session
            ];
        }
        if ($date != Carbon::today()->toDateString()) {
            $platoons = $company->platoons()
                ->whereHas('attendences', function ($query) use ($date) {
                    $query->whereDate('date', $date);
                })
                ->get();
        } else {
            $platoons = $company->platoons()
                ->whereHas('attendences', function ($query) {
                    $query->whereDate('date', Carbon::today());
                })
                ->get();
        }
        $ids = [];
        foreach ($platoons as $platoon) {
            if ($date != Carbon::today()->toDateString()) {
                $attendence = $platoon->attendences->where('date', $date)->first();
            } else {
                $attendence = $platoon->today_attendence()->first();
            }

            if ($attendence) {
                $ids = array_merge(
                    $ids,
                    json_decode($attendence->adm_student_ids) ?? [],
                    json_decode($attendence->ed_student_ids) ?? []
                );
            }
        }
        $sick_students = Patient::where('company_id', $companyId)->whereIn('excuse_type_id', [3, 1])->whereIn('student_id', $ids)->whereNull('released_at')->get();
        $pdf = Pdf::loadView('report.attendancePdf', compact('company', 'date', 'sick_students', 'attendenceType', 'sessionProgrammeAttendance'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream($date.'-'.$company->name.'-attendance.pdf');
    }

    public function changanua($attendenceId)
    {
        $attendence = Attendence::find($attendenceId);
        $platoon = $attendence->platoon;
        $kaziniStudentsId = $this->getKaziniStudentsIds($platoon, $attendence->date);
        //     return Student::whereIn('id', $kaziniStudentsId)->get();
        //    return count($kaziniStudentsId);
        $absent = $attendence->absent_student_ids != null ? explode(',', $attendence->absent_student_ids) : [];
        $sentry = $attendence->sentry_student_ids != null ? explode(',', $attendence->sentry_student_ids) : [];
        $mess = $attendence->mess_student_ids != null ? explode(',', $attendence->mess_student_ids) : [];
        $safari = $attendence->safari_student_ids != null ? explode(',', $attendence->safari_student_ids) : [];
        $off = $attendence->off_student_ids != null ? explode(',', $attendence->off_student_ids) : [];

        $notEligibleStudent_ids = array_merge($absent, $sentry, $mess, $safari, $off, $kaziniStudentsId);
        $students = $platoon->students->where('company_id', $platoon->company_id)->where('session_programme_id', $this->selectedSessionId)->whereNotIn('id', $notEligibleStudent_ids)->values();
        $sentry_students = $platoon->students->whereIn('id', $sentry)->values();
        $mess_students = $platoon->students->whereIn('id', $mess)->values();
        $off_students = $platoon->students->whereIn('id', $off)->values();
        $safari_students = $platoon->students->whereIn('id', $safari)->values();

        return view(
            'attendences.changanua',
            compact(
                'students',
                'attendence',
                'sentry_students',
                'mess_students',
                'off_students',
                'safari_students'
            )
        );
    }

    public function storeMchanganuo(Request $request, $attendenceId)
    {
        $attendence = Attendence::find($attendenceId);
        $sentry_ids = $request->input('sentry_student_ids');
        $off_ids = $request->input('off_student_ids');
        $mess_ids = $request->input('mess_student_ids');
        $safari_ids = $request->input('safari_student_ids');
        // return $sentry_ids;
        if ($sentry_ids) {
            $attendence->sentry_student_ids = implode(',', $sentry_ids);
            $attendence->sentry = count($sentry_ids);
            $attendence->present -= count($sentry_ids);
        }
        if ($off_ids) {
            $attendence->off_student_ids = implode(',', $off_ids);
            $attendence->off = count($off_ids);
            $attendence->present -= count($off_ids);
        }
        // }else if($request->type == "adm"){
        // $attendence->adm_student_ids = implode(",",$ids);
        // $attendence->adm = count($ids);
        // $attendence->present -= count($ids);
        if ($mess_ids) {
            $attendence->mess_student_ids = implode(',', $mess_ids);
            $attendence->mess = count($mess_ids);
            $attendence->present -= count($mess_ids);
        }
        if ($safari_ids) {
            $attendence->safari_student_ids = implode(',', $safari_ids);
            $attendence->safari = count($safari_ids);
            $attendence->present -= count($safari_ids);
        }

        $attendence->save();

        return redirect()->to('attendences/type/'.$attendence->attendenceType_id)->with('success', 'Attendance updated successfully.');
    }

    private function getLockUpStudentsIds($platoon)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        $lockUpStudentsIds = $platoon->lockUp()->where('company_id', $platoon->company_id)->whereNull('released_at')->pluck('students.id');
        $students_ids = Student::whereIn('id', $lockUpStudentsIds)->where('session_programme_id', $selectedSessionId)->pluck('id');

        return $students_ids;
    }

    private function kaziniPlatoonStudents($platoon, $date = null)
    {
        // Fetch students based on the collected student IDs and platoon name

        // return $students->pluck('platoon');
        return count($this->getKaziniStudentsIds($platoon, $date = null));
    }

    private function getKaziniStudentsIds($platoon, $date = null)
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

        $date = $date != null ? Carbon::parse($date)->subDays(1)->format('Y-m-d') : Carbon::yesterday()->format('Y-m-d');
        $guardAreas = $platoon->company->guardAreas;
        $patrolAreas = $platoon->company->patrolAreas;
        $beats = collect();
        // Loop for each guard area and patrol area then merge to the collection beat
        // so as to get all beats for specified date.
        foreach ($guardAreas as $guardArea) {
            $beats = $beats->merge($guardArea->beats()->where('date', $date)->where(function ($query) {
                $query->where('start_at', '18:00:00')
                    ->orWhere('start_at', '00:00:00');
            })->get());
        }
        foreach ($patrolAreas as $patrolArea) {
            $beats = $beats->merge($patrolArea->beats()->where('date', $date)->where(function ($query) {
                $query->where('start_at', '18:00:00')
                    ->orWhere('start_at', '00:00:00');
            })->get());
        }

        /**
         * Iterate the beats find the students and assign the number of students who are on duty
         */
        $studentIds = []; // Initialize as a collection to use the merge method correctly
        foreach ($beats as $beat) {
            $studentIds = array_merge($studentIds, json_decode($beat->student_ids));
        }
        $students = Student::whereIn('id', $studentIds)->where('platoon', $platoon->name)->where('session_programme_id', $selectedSessionId)->get();

        $studentIds = $students->pluck('id')->toArray();

        return $studentIds;
    }

    private function getSickStudentIds($platoon, $date = null)
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

        $sick_ids = [];
        $sick_students = $platoon->today_sick->where('company_id', $platoon->company_id)->where('session_programme_id', $selectedSessionId);
        $_date = $date == null ? Carbon::today() : Carbon::parse($date)->format('Y-m-d');
        foreach ($sick_students as $sick_student) {
            if ($sick_student->created_at->copy()->addDays($sick_student->rest_days) >= $_date) {
                if ($sick_student->excuse_type_id == 1 || $sick_student->excuse_type_id == 3) {
                    $sick_ids = array_merge($sick_ids, [$sick_student->student->id]);
                }
            }
        }

        return $sick_ids;
    }

    private function getEdStudentIds($platoon, $date = null)
    {
        $ed_ids = [];
        $_date = $date == null ? Carbon::today() : Carbon::parse($date)->format('Y-m-d');
        $ed_students = $platoon->todayEd($_date)->where('patients.company_id', $platoon->company_id)->get();
        foreach ($ed_students as $ed_student) {
            if ($ed_student->created_at->copy()->addDays($ed_student->rest_days) >= $_date) {
                $ed_ids = array_merge($ed_ids, [$ed_student->student->id]);
            }
        }

        return $ed_ids;
    }

    private function getAdmStudentIds($platoon)
    {
        $adm_ids = [];
        $adm_students = $platoon->today_admitted()->where('patients.company_id', $platoon->company_id)->get();
        foreach ($adm_students as $adm_student) {
            $adm_ids = array_merge($adm_ids, [$adm_student->student->id]);
        }

        return $adm_ids;
    }

    private function getDismissedStudents($platoon)
    {
        $sessionProgrammeId = session('selected_id', 1);
        $dismissedStudentIds = StudentDismissal::whereHas('student', function ($query) use ($sessionProgrammeId, $platoon) {
            $query->where('session_programme_id', $sessionProgrammeId)
                ->where('platoon', $platoon->id);
        })
            ->pluck('student_id')->toArray();

        return $dismissedStudentIds;
    }

    private function getSafariStudentIds($platoon, $date = null)
    {
        $selectedSessionId = session('selected_session', 1);
        $_date = $date == null ? Carbon::today()->format('Y-m-d') : $date;
        $leaves = $platoon->leaves()
            ->where('students.company_id', $platoon->company_id)
            ->where('session_programme_id', $selectedSessionId)
            ->where(function ($query) use ($_date) {
                $query->whereNull('leave_requests.return_date') // not yet returned
                    ->orWhereDate('leave_requests.return_date', '>=', $_date); // or still on leave
            })
            ->where('leave_requests.created_at', '<=', $_date)
            ->whereNull('leave_requests.rejected_at') // not rejected
            ->whereNotNull('leave_requests.approved_at') // approved
            ->distinct()
            ->pluck('student_id')
            ->toArray();

        return $leaves;

    }

    public function requestAttendance(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'date' => 'required|date',
            'reason' => 'required|string|min:5|max:500',
        ], [
            'company_id.required' => 'Company is required.',
            'reason.required' => 'Please provide a reason.',
            'reason.min' => 'Reason must be at least 5 characters.',
        ]);

        //  Check if a pending request already exists
        $alreadyPending = AttendanceRequest::where('company_id', $request->company_id)
            ->where('date', $request->date)
            ->where('requested_by', $request->user()->id)
            ->where('status', 'pending')
            ->where('attendenceType_id', $request->attendenceType_id)
            ->exists();

        if ($alreadyPending) {
            return redirect()->back()->with('info', 'You have already made a pending attendance request for this company and date.');
        }
        $attendanceRequest = AttendanceRequest::create([
            'date' => $request->date,
            'company_id' => $request->company_id,
            'requested_by' => $request->user()->id,
            'attendenceType_id' => $request->attendenceType_id,
            'reason' => $request->reason,
        ]);
        $audience = NotificationAudience::find(1);
        broadcast(new NotificationEvent(
            $attendanceRequest->id,   // ID from announcement
            $audience,                // Audience object or instance
            1,  // Notification type
            3,                        // Category (ensure 1 is a valid category ID)
            'Attendance request created', // Title of the notification
            $attendanceRequest,           // Full announcements object
            'body'  // Body of the notification
        ));

        return redirect()->back()->with('success', 'Attendance request submitted successfully.');
    }

    public function createAttendanceRequests()
    {
        $requests = AttendanceRequest::orderBy('created_at', 'desc')->paginate(15);
        //          $audience = NotificationAudience::find(1);

        // broadcast(new NotificationEvent(
        //     $requests[0]->id,   // ID from announcement
        //     $audience,                // Audience object or instance
        //     1,  // Notification type
        //     3,                        // Category (ensure 1 is a valid category ID)
        //     "Attendance request created", // Title of the notification
        //     $requests[0],           // Full announcements object
        //     "body"  // Body of the notification
        // ));
        return view('attendences.request', compact('requests'));
    }

    public function updateRequestStatus(Request $request)
    {
        $attendanceRequest = AttendanceRequest::find($request->attendanceRequestId);
        $attendanceRequest->status = $request->status;
        $attendanceRequest->approved_by = $request->user()->id;
        $attendanceRequest->save();

        return redirect()->back()->with('success', 'Attendance request status updated successfully.');
    }

    public function updateCompanyAttendance(Request $request, Company $company, $date)
    {
        // Validate the incoming status input
        $request->validate([
            'status' => ['required', 'in:unverified,verified,falsified'],
        ]);

        // Retrieve the attendance record for the company on the given date
        $attendance = $company->company_attendance($date);

        if (! $attendance) {
            return redirect()->back()->withErrors('Attendance record not found.');
        }

        $newStatus = $request->input('status');
        $hoursSinceUpdate = Carbon::parse($attendance->updated_at)->diffInHours(now());

        // Business logic based on current status and requested status
        switch ($newStatus) {
            case 'verified':
                $attendance->status = 'verified';
                break;
            case 'unverified':
                if ($hoursSinceUpdate >= 2) {
                    return redirect()->back()->with('error', 'Attendance is locked and cannot be changed.');
                }
                $attendance->status = 'unverified';
                break;
            case 'falsified':
                if ($hoursSinceUpdate >= 2) {
                    return redirect()->back()->with('error', 'Attendance is locked and cannot be changed.');
                }
                $attendance->status = 'falsified';
                $attendance->falsified_reason = $request->falsified_reason;
                break;
            default:
                return redirect()->back()->with('error', 'Invalid attendance status.');
        }

        $attendance->save();

        return redirect()->back()->with('success', 'Attendance status updated successfully.');
    }
}