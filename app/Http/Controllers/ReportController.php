<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\Company;
use App\Models\LeaveRequest;
use App\Models\MPS;
use App\Models\MPSVisitor;
use App\Models\Patient;
use App\Models\SessionProgramme;
use App\Models\Student;
use App\Services\GraphDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private $companies;

    private $selectedSessionId;

    protected $graphDataService;

    public function __construct(GraphDataService $graphDataService)
    {
        $this->graphDataService = $graphDataService;
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId) {
            $this->selectedSessionId = 1;
        }
        $this->middleware('permission:report-list', ['only' => ['index', 'generateAttendanceReport', 'hospital', 'leaves', 'mps', 'generateHospitalReport', 'generateLeavesReport']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        $selectedSessionId = session('selected_session') ?: 1;

        // Determine the companies based on user role
        $companiesQuery = Company::query();

        if ($user->hasRole(['Sir Major', 'Instructor', 'OC Coy'])) {
            $companiesQuery->where('id', $user->staff->company_id);
        }

        $companiesQuery->whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        });

        $companies = $companiesQuery->get();
        $companyIds = $companies->pluck('id');

        // Parse optional date range filters
        $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date')) : null;
        // Call data helper only with dates if both are provided
        if ($startDate && $endDate) {
            $data = $this->getAttendanceData($companyIds, $startDate, $endDate);
        } else {
            $data = $this->getAttendanceData($companyIds); // use default logic (all data)
        }

        return view('report.index', [
            'companies' => $companies,
            'dailyCounts' => $data['dailyCounts'],
            'weeklyCounts' => $data['weeklyCounts'],
            'monthlyCounts' => $data['monthlyCounts'],
            'mostAbsentStudent' => $data['mostAbsentStudent'],
            'graphData' => $this->graphDataService->getGraphData($startDate, $endDate),
        ]);
    }

    private function getAttendanceData($companyIds, $startDate = null, $endDate = null)
    {
        $now = Carbon::today();

        // If either date is missing, treat as no date filter
        $hasDateFilter = $startDate !== null && $endDate !== null;

        // Use defaults only if you want a default range when dates not provided,
        // but your request was to query total if dates null, so skip default range here.
        if (!$hasDateFilter) {
            $startDate = null;
            $endDate = null;
        }

        // DAILY QUERY
        $dailyQuery = DB::table('attendences')
            ->join('platoons', 'attendences.platoon_id', '=', 'platoons.id')
            ->whereIn('platoons.company_id', $companyIds);

        if ($hasDateFilter) {
            $dailyQuery->whereBetween('attendences.date', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
        }

        $rawDaily = $dailyQuery
            ->select(
                DB::raw('DATE(attendences.date) as date'),
                DB::raw('SUM(attendences.absent) as total_absent'),
                DB::raw('SUM(attendences.kazini) as total_kazini')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // For date labels, if no date filter, get all distinct dates from data
        if ($hasDateFilter) {
            $days = collect();
            $period = Carbon::parse($startDate)->diffInDays($endDate);
            for ($i = 0; $i <= $period; $i++) {
                $days->push(Carbon::parse($startDate)->addDays($i)->toDateString());
            }
        } else {
            // Use dates present in the data for labels
            $days = $rawDaily->keys();
        }

        $dailyCounts = $days->map(fn($date) => [
            'date' => $date,
            'total_absent' => $rawDaily[$date]->total_absent ?? 0,
            'total_kazini' => $rawDaily[$date]->total_kazini ?? 0,
        ]);

        // WEEKLY QUERY
        $weeklyQuery = DB::table('attendences')
            ->join('platoons', 'attendences.platoon_id', '=', 'platoons.id')
            ->join('companies', 'platoons.company_id', '=', 'companies.id')
            ->whereIn('companies.id', $companyIds);

        if ($hasDateFilter) {
            $weeklyQuery->whereBetween('attendences.date', [$startDate, $endDate]);
        }

        $rawWeekly = $weeklyQuery
            ->select(
                DB::raw('YEARWEEK(attendences.date, 1) as year_week'),
                DB::raw('MIN(DATE(attendences.date)) as week_start'),
                DB::raw('SUM(attendences.absent) as total_absent'),
                DB::raw('SUM(attendences.kazini) as total_kazini'),
            )
            ->groupBy(DB::raw('YEARWEEK(attendences.date, 1)'))
            ->orderBy('year_week')
            ->get()
            ->keyBy('year_week');

        $weeklyCounts = collect();

        if ($hasDateFilter) {
            $startOfWeek = $startDate->copy()->startOfWeek();
            while ($startOfWeek <= $endDate) {
                $key = $startOfWeek->format('oW');
                $record = $rawWeekly->get($key);
                $weeklyCounts->push([
                    'week_start' => $startOfWeek->toDateString(),
                    'total_absent' => $record?->total_absent ?? 0,
                    'total_kazini' => $record?->total_kazini ?? 0,
                ]);
                $startOfWeek->addWeek();
            }
        } else {
            // No date filter: just return all weeks found
            foreach ($rawWeekly as $weekData) {
                $weeklyCounts->push([
                    'week_start' => $weekData->week_start,
                    'total_absent' => $weekData->total_absent,
                    'total_kazini' => $weekData->total_kazini,
                ]);
            }
        }

        // MONTHLY QUERY
        $monthlyQuery = DB::table('attendences')
            ->join('platoons', 'attendences.platoon_id', '=', 'platoons.id')
            ->join('companies', 'platoons.company_id', '=', 'companies.id')
            ->whereIn('companies.id', $companyIds);

        if ($hasDateFilter) {
            $monthlyQuery->whereBetween('attendences.date', [$startDate, $endDate]);
        }

        $rawMonthly = $monthlyQuery
            ->select(
                DB::raw('YEAR(attendences.date) as year'),
                DB::raw('MONTH(attendences.date) as month'),
                DB::raw('SUM(attendences.absent) as total_absent'),
                DB::raw('SUM(attendences.kazini) as total_kazini')
            )
            ->groupBy(DB::raw('YEAR(attendences.date)'), DB::raw('MONTH(attendences.date)'))
            ->orderByRaw('YEAR(attendences.date), MONTH(attendences.date)')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT) => $item,
            ]);

        $monthlyCounts = collect();

        if ($hasDateFilter) {
            $currentMonth = $startDate->copy()->startOfMonth();
            while ($currentMonth <= $endDate) {
                $key = $currentMonth->format('Y-m');
                $record = $rawMonthly->get($key);
                $monthlyCounts->push([
                    'month' => $currentMonth->format('F Y'),
                    'total_absent' => $record?->total_absent ?? 0,
                    'total_kazini' => $record?->total_kazini ?? 0,
                ]);
                $currentMonth->addMonth();
            }
        } else {
            foreach ($rawMonthly as $monthData) {
                $monthlyCounts->push([
                    'month' => Carbon::createFromDate($monthData->year, $monthData->month)->format('F Y'),
                    'total_absent' => $monthData->total_absent,
                    'total_kazini' => $monthData->total_kazini,
                ]);
            }
        }

        // Top 10 absent students
        $absentQuery = DB::table('attendences');
        if ($hasDateFilter) {
            $absentQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $absentCounts = $absentQuery
            ->pluck('absent_student_ids')
            ->flatMap(fn($v) => json_decode($v, true) ?? [])
            ->filter(fn($id) => is_numeric($id))
            ->countBy()
            ->sortDesc()
            ->take(10);

        $students = Student::whereIn('id', $absentCounts->keys())->get()->keyBy('id');
        $mostAbsentStudent = $absentCounts->map(fn($count, $id) => [
            'student' => $students[$id] ?? null,
            'count' => $count,
        ])->values();

        return [
            'dailyCounts' => $dailyCounts,
            'weeklyCounts' => $weeklyCounts,
            'monthlyCounts' => $monthlyCounts,
            'mostAbsentStudent' => $mostAbsentStudent,
        ];
    }

    public function generateAttendanceReport(Request $request)
    {
        $company = Company::find($request->company_id);
        $companyId = $company->id; // your specific company ID

        $attendances = Attendence::with('sessionProgramme') // eager load sessionProgramme relationship
            ->whereIn('platoon_id', function ($query) use ($companyId) {
                $query->select('id')
                    ->from('platoons')
                    ->where('company_id', $companyId);
            })
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->groupBy('session_programme_id');

        if ($attendances->isEmpty()) {
            return redirect()->back()->with('info', 'No attendances recorded for today.');
        }
        // Prepare an array to store session program attendance data
        $sessionProgrammeAttendance = [];
        // Loop through the grouped attendance data
        $sick_student_ids = [];
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
        foreach ($sessionProgrammeAttendance[0]['attendances'] as $attendance) {
            $sick_student_ids = array_merge(
                $sick_student_ids,
                json_decode($attendance->adm_student_ids) ?? [],
                json_decode($attendance->ed_student_ids) ?? []
            );

        }
        $sick_students = Patient::where('company_id', $companyId)->whereIn('excuse_type_id', [3, 1])->whereIn('student_id', $sick_student_ids)->whereNull('released_at')->get();
        // return $sessionProgrammeAttendance;
        $pdf = PDF::loadView('report.attendancePdf', compact('company', 'sessionProgrammeAttendance', 'sick_students'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream('attendance_report.pdf');
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

    public function hospital(Request $request)
    {
        //return $request->all();
        // Parse optional date range filters
        $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date')) : null;
        $data = $this->getHospitalData($startDate, $endDate);

        $dailyCounts = $data['dailyCounts'];
        $weeklyCounts = $data['weeklyCounts'];
        $monthlyCounts = $data['monthlyCounts'];
        $mostOccurredStudents = $data['mostOccurredStudents'];

        $dailyCounts = [
            'labels' => array_column($dailyCounts, 'date'),
            'total' => array_column($dailyCounts, 'total'),
            'ED' => array_column($dailyCounts, 'ED'),
            'LD' => array_column($dailyCounts, 'LD'),
            'Adm' => array_column($dailyCounts, 'Adm'),
        ];

        $weeklyCounts = [
            'labels' => array_column($weeklyCounts, 'date'),
            'total' => array_column($weeklyCounts, 'total'),
            'ED' => array_column($weeklyCounts, 'ED'),
            'LD' => array_column($weeklyCounts, 'LD'),
            'Adm' => array_column($weeklyCounts, 'Adm'),
        ];

        $monthlyCounts = [
            'labels' => array_column($monthlyCounts, 'month'),
            'total' => array_column($monthlyCounts, 'total'),
            'ED' => array_column($monthlyCounts, 'ED'),
            'LD' => array_column($monthlyCounts, 'LD'),
            'Adm' => array_column($monthlyCounts, 'Adm'),
        ];

        $date = Carbon::today()->format('d F, Y');

        return view(
            'report.hospital',
            compact(
                'date',
                'dailyCounts',
                'weeklyCounts',
                'monthlyCounts',
                'mostOccurredStudents'
            )
        );
    }

    public function leaves(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Conditionally call with or without parameters
        if ($startDate && $endDate) {
            $data = $this->getLeavesData($startDate, $endDate);
        } else {
            $data = $this->getLeavesData(); // default 7 days, 5 weeks, 3 months
        }
        $leaveRequests = $data['leaveRequests'];
        $dailyCounts = $data['dailyCounts'];

        $dailyCounts = [
            'labels' => array_column($dailyCounts, 'date'),
            'returned' => array_column($dailyCounts, 'returned'),
            'on_leave' => array_column($dailyCounts, 'on_leave'),
            'late' => array_column($dailyCounts, 'late'),
        ];
        $weeklyCounts = $data['weeklyCounts'];

        $weeklyCounts = [
            'labels' => array_column($weeklyCounts, 'week_start_date'),
            'returned' => array_column($weeklyCounts, 'returned'),
            'on_leave' => array_column($weeklyCounts, 'on_leave'),
            'late' => array_column($weeklyCounts, 'late'),
        ];
        $monthlyCounts = $data['monthlyCounts'];

        $monthlyCounts = [
            'labels' => array_column($monthlyCounts, 'month_start_date'),
            'returned' => array_column($monthlyCounts, 'returned'),
            'on_leave' => array_column($monthlyCounts, 'on_leave'),
            'late' => array_column($monthlyCounts, 'late'),
        ];

        return view('report.leaves', compact(
            'leaveRequests',
            'dailyCounts',
            'weeklyCounts',
            'monthlyCounts'
        ));
    }

    public function mps(Request $request)
    {

        // Get optional start and end dates from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Pass the dates to getMPSData
        $data = $this->getMPSData($startDate, $endDate);
        $dailyCounts = [
            'labels' => array_column($data['dailyCounts'], 'date'),
            'mps_counts' => array_column($data['dailyCounts'], 'mps_count'),
            'visitor_counts' => array_column($data['dailyCounts'], 'visitor_count'),
        ];
        $weeklyCounts = [
            'labels' => array_column($data['weeklyCounts'], 'week_start'),
            'mps_counts' => array_column($data['weeklyCounts'], 'mps_count'),
            'visitor_counts' => array_column($data['weeklyCounts'], 'visitor_count'),
        ];
        $monthlyCounts = [
            'labels' => array_column($data['monthlyCounts'], 'month_label'),
            'mps_counts' => array_column($data['monthlyCounts'], 'mps_count'),
            'visitor_counts' => array_column($data['monthlyCounts'], 'visitor_count'),
        ];

        $currentLockedUpStudents = $data['currentLockedUpStudents'];
        $topLockedUpStudents = $data['topLockedUpStudents'];
        $topVisitedStudents = $data['topVisitedStudents'];

        return view(
            'report.mps',
            compact(
                'currentLockedUpStudents',
                'topLockedUpStudents',
                'topVisitedStudents',
                'dailyCounts',
                'weeklyCounts',
                'monthlyCounts'
            )
        );
    }

    private function getWeekNumber($date)
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            $selectedSessionId = 1;
        }
        $sessionProgramme = SessionProgramme::find($selectedSessionId);
        // Define the specified start date (September 30, 2024)
        $startDate = Carbon::createFromFormat('d-m-Y', Carbon::parse($sessionProgramme->startDate)->format('d-m-Y'));
        // dd($date);
        // Define the target date for which you want to calculate the week number
        $date = Carbon::parse($date); // This could be the current date, or any specific date
        // Calculate the difference in weeks between the start date and the target date
        $weekNumber = (int) ceil($startDate->diffInDays($date) / 7) + 1; // Adding 1 to make it 1-based (Week 1, Week 2, ...)

        return (int) $weekNumber;
    }

    public function generateHospitalReport(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $data = $this->getHospitalData($start_date, $end_date);
        $pdf = PDF::loadView('report.hospitalPdf', compact('data', 'start_date', 'end_date'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream('hospital_report.pdf');
    }

    public function generateLeavesReport(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $data = $this->getLeavesData($start_date, $end_date);
        $pdf = PDF::loadView('report.leavesPdf', compact('data', 'start_date', 'end_date'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream('leaves_report.pdf');
    }

    public function generateMPSReport(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $data = $this->getMPSData($start_date, $end_date);

        $pdf = PDF::loadView('report.mpsPdf', compact('data'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream('mps_report.pdf');
    }

    private function getHospitalData($startDate = null, $endDate = null)
    {
        // 🗓️ Handle default date range (last 7 days, including today)
        if (!$startDate || !$endDate) {
            $dailyEnd = Carbon::today()->endOfDay();
            $dailyStart = Carbon::today()->subDays(6)->startOfDay(); // 6 days ago + today = 7 days
        } else {
            $dailyStart = Carbon::parse($startDate)->copy()->startOfDay();
            $dailyEnd = Carbon::parse($endDate)->copy()->endOfDay();
        }

        $user = Auth::user();
        $company_ids = $user->hasRole(['Sir Major', 'Instructor', 'OC Coy'])
            ? [$user->staff->company_id]
            : Company::pluck('id');

        $selectedSessionId = session('selected_session') == '' ? 1 : session('selected_session');

        // 🏥 Fetch aggregated counts
        $rawCounts = Patient::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total,
            SUM(CASE WHEN excuse_type_id = 1 THEN 1 ELSE 0 END) as ED,
            SUM(CASE WHEN excuse_type_id = 2 THEN 1 ELSE 0 END) as LD,
            SUM(CASE WHEN excuse_type_id = 3 THEN 1 ELSE 0 END) as Adm
        ')
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereIn('company_id', $company_ids);
            })
            ->whereIn('excuse_type_id', [1, 2, 3])
            ->whereNull('released_at') // ✅ Only active patients
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->date => [
                        'date' => $item->date,
                        'total' => (int) $item->total,
                        'ED' => (int) $item->ED,
                        'LD' => (int) $item->LD,
                        'Adm' => (int) $item->Adm,
                    ],
                ];
            })
            ->toArray();

        // 📅 Build full date range (no skipping days)
        $period = new \DatePeriod(
            $dailyStart,                         // ✅ start date
            new \DateInterval('P1D'),
            $dailyEnd->copy()->addDay()          // ✅ add 1 day to include the end date
        );

        $dailyCounts = [];
        foreach ($period as $date) {
            $dateStr = Carbon::instance($date)->toDateString();
            $dailyCounts[] = [
                'date' => $dateStr,
                'total' => $rawCounts[$dateStr]['total'] ?? 0,
                'ED' => $rawCounts[$dateStr]['ED'] ?? 0,
                'LD' => $rawCounts[$dateStr]['LD'] ?? 0,
                'Adm' => $rawCounts[$dateStr]['Adm'] ?? 0,
            ];
        }


        // Determine start and end dates
        if (!$startDate || !$endDate) {
            // Default: last 5 full weeks including the current week
            $weekEnd = Carbon::today()->endOfWeek()->endOfDay();
            $weekStart = Carbon::today()->subWeeks(4)->startOfWeek()->startOfDay();
        } else {
            $weekStart = Carbon::parse($startDate)->copy()->startOfDay();
            $weekEnd = Carbon::parse($endDate)->copy()->endOfDay();
        }



        // Fetch raw daily counts from DB
        $rawCounts = Patient::selectRaw('
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN excuse_type_id = 1 THEN 1 ELSE 0 END) as ED,
        SUM(CASE WHEN excuse_type_id = 2 THEN 1 ELSE 0 END) as LD,
        SUM(CASE WHEN excuse_type_id = 3 THEN 1 ELSE 0 END) as Adm
    ')
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereIn('company_id', $company_ids);
            })
            ->whereIn('excuse_type_id', [1, 2, 3])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->date => [
                        'total' => (int) $item->total,
                        'ED' => (int) $item->ED,
                        'LD' => (int) $item->LD,
                        'Adm' => (int) $item->Adm,
                    ],
                ];
            })
            ->toArray();

        // Build weekly periods dynamically
        $weeklyCounts = [];
        $periodStart = $weekStart->copy()->startOfWeek();
        $periodEnd = $weekEnd->copy()->startOfWeek();

        for ($week = $periodStart; $week <= $periodEnd; $week->addWeek()) {
            $weekStartStr = $week->toDateString();

            // Aggregate daily counts within the week
            $weekTotal = 0;
            $weekED = 0;
            $weekLD = 0;
            $weekAdm = 0;

            foreach (range(0, 6) as $dayOffset) {
                $day = $week->copy()->addDays($dayOffset)->toDateString();
                if (isset($rawCounts[$day])) {
                    $weekTotal += $rawCounts[$day]['total'];
                    $weekED += $rawCounts[$day]['ED'];
                    $weekLD += $rawCounts[$day]['LD'];
                    $weekAdm += $rawCounts[$day]['Adm'];
                }
            }

            $weeklyCounts[] = [
                'date' => $this->getWeekNumber($weekStartStr) . ' Week', // week starting date
                'total' => $weekTotal,
                'ED' => $weekED,
                'LD' => $weekLD,
                'Adm' => $weekAdm,
            ];
        }


        // Determine start and end dates
        if ($startDate && $endDate) {
            $startMonth = Carbon::parse($startDate)->copy()->startOfMonth();
            $endMonth = Carbon::parse($endDate)->copy(); // use actual endDate, not end of month
        } else {
            $endMonth = Carbon::now();
            $startMonth = Carbon::now()->subMonths(2)->startOfMonth(); // last 3 months default
        }

        // Fetch raw counts grouped by month
        $rawCounts = Patient::selectRaw("
        DATE_FORMAT(created_at, '%Y-%m') as month_key,
        COUNT(*) as total,
        SUM(CASE WHEN excuse_type_id = 1 THEN 1 ELSE 0 END) as ED,
        SUM(CASE WHEN excuse_type_id = 2 THEN 1 ELSE 0 END) as LD,
        SUM(CASE WHEN excuse_type_id = 3 THEN 1 ELSE 0 END) as Adm
    ")
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereIn('company_id', $company_ids);
            })
            ->whereIn('excuse_type_id', [1, 2, 3])
            ->whereBetween('created_at', [$startMonth, $endMonth->copy()->endOfDay()])
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month_key')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->month_key => [
                        'month' => $item->month_key,
                        'total' => (int) $item->total,
                        'ED' => (int) $item->ED,
                        'LD' => (int) $item->LD,
                        'Adm' => (int) $item->Adm,
                    ],
                ];
            })
            ->toArray();

        // Build month range dynamically
        $monthlyCounts = [];
        for ($month = $startMonth->copy(); $month->lte($endMonth); $month->addMonth()) {
            $monthKey = $month->format('Y-m');

            $monthlyCounts[] = [
                'month' => $month->format('F Y'),
                'total' => $rawCounts[$monthKey]['total'] ?? 0,
                'ED' => $rawCounts[$monthKey]['ED'] ?? 0,
                'LD' => $rawCounts[$monthKey]['LD'] ?? 0,
                'Adm' => $rawCounts[$monthKey]['Adm'] ?? 0,
            ];
        }


        $mostOccurredStudents = Patient::select('student_id', DB::raw('COUNT(*) as occurrence_count'))
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereIn('company_id', $company_ids);
            })
            ->when($weekStart && $weekEnd, function ($query) use ($weekStart, $weekEnd) {
                $query->whereBetween('created_at', [Carbon::parse($weekStart)->copy()->startOfDay(), Carbon::parse($weekEnd)->copy()->endOfDay()]);
            })
            ->groupBy('student_id')           // Group by student_id to count occurrences
            ->orderByDesc('occurrence_count') // Sort by occurrence count in descending order
            ->limit(10)                       // Limit to the top 10
            ->get()
            ->map(function ($item) {
                $student = $item->student; // Assumes 'student' relationship exists in Patient model
    
                return [
                    'student_id' => $student->id,
                    'student' => $student, // Full student object, or you can select specific fields like $student->name
                    'count' => $item->occurrence_count,
                ];
            });


        return [
            'mostOccurredStudents' => $mostOccurredStudents,
            'dailyCounts' => $dailyCounts,
            'weeklyCounts' => $weeklyCounts,
            'monthlyCounts' => $monthlyCounts,
        ];
    }

    private function getLeavesData($startDate = null, $endDate = null)
    {
                // 🗓️ Handle default date range (last 7 days, including today)
        if (!$startDate || !$endDate) {
            $dailyEnd = Carbon::today()->endOfDay();
            $dailyStart = Carbon::today()->subDays(6)->startOfDay(); // 6 days ago + today = 7 days
        } else {
            $dailyStart = Carbon::parse($startDate)->copy()->startOfDay();
            $dailyEnd = Carbon::parse($endDate)->copy()->endOfDay();
        }
        $user = Auth::user();
        $company_ids = [];

        if ($user->hasRole(['Sir Major', 'Instructor', 'OC Coy'])) {
            $company_ids = [$user->staff->company_id];
        } else {
            $company_ids = Company::pluck('id')->toArray();
        }

        $selectedSessionId = session('selected_session') ?: 1;

        // // Handle date range
        // $today = Carbon::today();
        // $defaultStart = $today->copy()->subDays(6)->startOfDay(); // last 7 days
        // $defaultEnd = $today->copy()->endOfDay();

        // $start = $start_date ? Carbon::parse($start_date)->startOfDay() : $defaultStart;
        // $end = $end_date ? Carbon::parse($end_date)->endOfDay() : $defaultEnd;

        // ======================== DAILY ========================
        $rawDaily = LeaveRequest::whereBetween('created_at', [$dailyStart, $dailyEnd])
            ->whereIn('company_id', $company_ids)
            ->selectRaw('
            DATE(created_at) AS date,
            COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
            COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
            COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
        ')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyCounts = [];
        $current = Carbon::parse($dailyStart)->copy();
        while ($current->lte(Carbon::parse($dailyEnd))) {
            $dateStr = $current->toDateString();
            $raw = $rawDaily[$dateStr] ?? null;

            $dailyCounts[] = [
                'date' => $dateStr,
                'returned' => $raw->returned ?? 0,
                'on_leave' => $raw->on_leave ?? 0,
                'late' => $raw->late ?? 0,
            ];

            $current->addDay();
        }

        // ======================== WEEKLY ========================
        // If no custom dates, use last 5 full weeks
        if (!$startDate && !$endDate) {
            $startOfFirstWeek = Carbon::today()->subWeeks(4)->startOfWeek();
            $endOfLastWeek = Carbon::today()->endOfWeek();
        } else {
            $startOfFirstWeek = Carbon::parse($startDate)->copy()->startOfWeek();
            $endOfLastWeek = Carbon::parse($endDate)->copy()->endOfWeek();
        }

        $rawWeekly = LeaveRequest::whereBetween('created_at', [$startOfFirstWeek, $endOfLastWeek])
            ->whereIn('company_id', $company_ids)
            ->selectRaw('
            YEARWEEK(created_at, 1) AS year_week,
            MIN(DATE(created_at)) AS created_at,
            COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
            COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
            COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
        ')
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('year_week')
            ->get()
            ->keyBy(fn($row) => Carbon::parse($row->created_at)->format('oW'));

        $weeklyCounts = [];
        $week = $startOfFirstWeek->copy();
        while ($week->lte($endOfLastWeek)) {
            $weekKey = $week->format('oW');
            $raw = $rawWeekly[$weekKey] ?? null;

            $weeklyCounts[] = [
                'week_start_date' => 'Week ' . $week->isoWeek,
                'returned' => $raw->returned ?? 0,
                'on_leave' => $raw->on_leave ?? 0,
                'late' => $raw->late ?? 0,
            ];

            $week->addWeek();
        }

        // ======================== MONTHLY ========================
        // Default: current month + 2 previous
        if (!$startDate && !$endDate) {
            $startOfFirstMonth = Carbon::today()->subMonths(2)->startOfMonth();
            $endOfLastMonth = Carbon::today()->endOfMonth();
        } else {
            $startOfFirstMonth = Carbon::parse($startDate)->copy()->startOfMonth();
            $endOfLastMonth = Carbon::parse($endDate)->copy()->endOfMonth();
        }

        $rawMonthly = LeaveRequest::whereBetween('created_at', [$startOfFirstMonth, $endOfLastMonth])
            ->whereIn('company_id', $company_ids)
            ->selectRaw("
            DATE_FORMAT(created_at, '%Y-%m-01') AS month_start_date,
            COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
            COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
            COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
        ")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-01')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-01')"))
            ->get()
            ->keyBy('month_start_date');

        $monthlyCounts = [];
        $month = $startOfFirstMonth->copy();
        while ($month->lte($endOfLastMonth)) {
            $monthKey = $month->format('Y-m-01');
            $raw = $rawMonthly[$monthKey] ?? null;

            $monthlyCounts[] = [
                'month_start_date' => $month->format('F Y'),
                'returned' => $raw->returned ?? 0,
                'on_leave' => $raw->on_leave ?? 0,
                'late' => $raw->late ?? 0,
            ];

            $month->addMonth();
        }

        // ======================== TOP 10 STUDENTS ========================
        $start = $dailyStart;
        $end = $dailyEnd;
        $leaveRequests = LeaveRequest::whereBetween('created_at', [$start, $end])
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereIn('company_id', $company_ids);
            })
            ->select('student_id', DB::raw('COUNT(*) as occurrence_count'))
            ->groupBy('student_id')
            ->orderByDesc('occurrence_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $student = $item->student;

                return [
                    'student_id' => $student->id,
                    'student' => $student,
                    'count' => $item->occurrence_count,
                ];
            });

        return [
            'leaveRequests' => $leaveRequests,
            'dailyCounts' => $dailyCounts,
            'weeklyCounts' => $weeklyCounts,
            'monthlyCounts' => $monthlyCounts,
        ];
    }

    private function getLeavesData2()
    {
        $user = Auth::user();
        $company_ids = [];
        if ($user->hasRole(['Sir Major', 'Instructor', 'OC Coy'])) {
            $company_ids = [$user->staff->company_id];
        } else {
            $company_ids = Company::pluck('id');
        }
        $selectedSessionId = session('selected_session') == '' ? 1 : session('selected_session');

        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();

        $rawCounts = LeaveRequest::whereDate('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('
        DATE(created_at) AS date,
        COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
        COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
        COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
        ')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->whereIn('company_id', $company_ids)
            ->keyBy('date') // <-- group result by date for easier mapping
            ->toArray();

        // Step 2: Initialize daily counts with default values
        $dailyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays(6 - $i)->toDateString();

            $raw = $rawCounts[$date] ?? null;

            $dailyCounts[] = [
                'date' => $date,
                'returned' => $raw['returned'] ?? 0,
                'on_leave' => $raw['on_leave'] ?? 0,
                'late' => $raw['late'] ?? 0,
            ];
        }

        // Step 1: Determine start of current week and build weekly range
        $startOfCurrentWeek = Carbon::now()->startOfWeek(); // Monday
        $weeksAgo = 4;                            // This week + 4 previous = 5 weeks

        $weeklyRange = [];
        for ($i = $weeksAgo; $i >= 0; $i--) {
            $weeklyRange[] = $startOfCurrentWeek->copy()->subWeeks($i)->startOfWeek(); // Always Monday
        }

        // Step 2: Query the DB for counts within the 5-week window
        $startDate = $startOfCurrentWeek->copy()->subWeeks($weeksAgo)->startOfWeek(); // 4 weeks ago Monday
        $endDate = Carbon::now()->endOfWeek();                                      // End of current week

        $rawCounts = LeaveRequest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
        YEARWEEK(created_at, 1) AS year_week, -- mode 1 = weeks start on Monday
        MIN(DATE(created_at)) as week_start_date, -- not reliable for empty weeks, used just as backup
        COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
        COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
        COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
    ')
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('year_week')
            ->get()
            ->keyBy('year_week');

        $weeklyCounts = [];

        foreach ($weeklyRange as $startOfWeek) {
            $weekKey = $startOfWeek->format('oW'); // 'o' = ISO year, 'W' = ISO week number (matches YEARWEEK(..., 1))
            $weekStartDate = $startOfWeek->toDateString();

            $raw = $rawCounts[$weekKey] ?? null;

            $weeklyCounts[] = [
                'week_start_date' => $this->getWeekNumber($weekStartDate) . ' Week',
                'returned' => $raw->returned ?? 0,
                'on_leave' => $raw->on_leave ?? 0,
                'late' => $raw->late ?? 0,
            ];
        }

        // Setup dates
        $today = Carbon::today();
        $startOfCurrentMonth = $today->copy()->startOfMonth();
        $monthsAgo = 2;

        $monthlyRange = [];
        for ($i = $monthsAgo; $i >= 0; $i--) {
            $monthlyRange[] = $startOfCurrentMonth->copy()->subMonths($i)->startOfMonth();
        }

        // Fetch counts from DB
        $rawCounts = LeaveRequest::whereBetween('created_at', [
            $startOfCurrentMonth->copy()->subMonths($monthsAgo),
            $today->endOfDay(),
        ])
            ->selectRaw("
        DATE_FORMAT(created_at, '%Y-%m-01') AS month_start_date,
        COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
        COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
        COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
    ")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-01')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-01')"))
            ->whereIn('company_id', $company_ids)
            ->get()
            ->keyBy('month_start_date');

        // Initialize structure with all months
        $monthlyCounts = [];
        foreach ($monthlyRange as $startOfMonth) {
            $startOfMonthDate = $startOfMonth->toDateString();

            $raw = $rawCounts[$startOfMonthDate] ?? null;

            $monthlyCounts[] = [
                'month_start_date' => Carbon::parse($startOfMonthDate)->format('F Y'),
                'returned' => $raw->returned ?? 0,
                'on_leave' => $raw->on_leave ?? 0,
                'late' => $raw->late ?? 0,
            ];
        }

        $leaveRequests = LeaveRequest::select('student_id', DB::raw('COUNT(*) as occurrence_count'))
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)->whereIn('company_id', $company_ids);
            })
            ->groupBy('student_id')           // Group by student_id to count occurrences
            ->orderByDesc('occurrence_count') // Sort by occurrence count in descending order
            ->limit(10)                       // Limit to the top 10 most occurred students
            ->get()
            ->map(function ($item) {
                // Retrieve the student associated with the patient by student_id
                $student = $item->student; // Assumes a relationship named 'student' in the Patient model
    
                return [
                    'student_id' => $student->id,            // Access student ID
                    'student' => $student,                // Access student's name or any other student details
                    'count' => $item->occurrence_count, // The number of occurrences
                ];
            });

        return [
            'leaveRequests' => $leaveRequests,
            'dailyCounts' => $dailyCounts,
            'weeklyCounts' => $weeklyCounts,
            'monthlyCounts' => $monthlyCounts,
        ];
    }

    private function getMPSData($start_date = null, $end_date = null)
    {
        $user = Auth::user();
        $company_ids = $user->hasRole(['Sir Major', 'Instructor', 'OC Coy'])
            ? [$user->staff->company_id]
            : Company::pluck('id')->toArray();

        // ===== DATE RANGES =====
        if ($start_date && $end_date) {
            $dailyStart = Carbon::parse($start_date)->startOfDay();
            $dailyEnd = Carbon::parse($end_date)->endOfDay();

            $weeklyStart = Carbon::parse($start_date)->startOfWeek();
            $weeklyEnd = Carbon::parse($end_date)->endOfWeek();

            $monthlyStart = Carbon::parse($start_date)->startOfMonth();
            $monthlyEnd = Carbon::parse($end_date)->endOfMonth();
        } else {
            $dailyStart = Carbon::now()->subDays(6)->startOfDay();
            $dailyEnd = Carbon::now()->endOfDay();

            $weeklyStart = Carbon::now()->startOfWeek()->subWeeks(4);
            $weeklyEnd = Carbon::now()->endOfWeek();

            $monthlyStart = Carbon::now()->subMonths(2)->startOfMonth();
            $monthlyEnd = Carbon::now()->endOfMonth();
        }

        // ===== DAILY =====
        $mpsCounts = DB::table('m_p_s as mps')
            ->join('students as s', 'mps.student_id', '=', 's.id')
            ->selectRaw('DATE(mps.arrested_at) as date, COUNT(DISTINCT mps.student_id) as mps_count')
            ->whereBetween('mps.arrested_at', [$dailyStart, $dailyEnd])
            ->whereIn('s.company_id', $company_ids)
            ->groupByRaw('DATE(mps.arrested_at)')
            ->orderByRaw('DATE(mps.arrested_at)')
            ->get()
            ->keyBy('date');

        $mpsVisitorCounts = DB::table('m_p_s_visitors as v')
            ->join('students as s', 'v.student_id', '=', 's.id')
            ->selectRaw('DATE(v.visited_at) as date, COUNT(DISTINCT v.student_id) as visitor_count')
            ->whereBetween('v.visited_at', [$dailyStart, $dailyEnd])
            ->whereIn('s.company_id', $company_ids)
            ->groupByRaw('DATE(v.visited_at)')
            ->orderByRaw('DATE(v.visited_at)')
            ->get()
            ->keyBy('date');

        $dailyCounts = [];
        $period = Carbon::parse($dailyStart)->daysUntil(Carbon::parse($dailyEnd)->addDay());
        foreach ($period as $date) {
            $key = $date->toDateString();
            $dailyCounts[] = [
                'date' => $key,
                'mps_count' => $mpsCounts[$key]->mps_count ?? 0,
                'visitor_count' => $mpsVisitorCounts[$key]->visitor_count ?? 0,
            ];
        }

        // ===== WEEKLY =====
        $weeklyRaw = DB::table('m_p_s as mps')
            ->join('students as s', 'mps.student_id', '=', 's.id')
            ->selectRaw("DATE_FORMAT(mps.arrested_at, '%x%v') as year_week, COUNT(DISTINCT mps.student_id) as mps_count")
            ->whereBetween('mps.arrested_at', [$weeklyStart, $weeklyEnd])
            ->whereIn('s.company_id', $company_ids)
            ->groupBy(DB::raw("DATE_FORMAT(mps.arrested_at, '%x%v')"))
            ->orderBy(DB::raw("DATE_FORMAT(mps.arrested_at, '%x%v')"))
            ->get()
            ->keyBy('year_week');

        $weeklyVisitorRaw = DB::table('m_p_s_visitors as v')
            ->join('students as s', 'v.student_id', '=', 's.id')
            ->selectRaw("DATE_FORMAT(v.visited_at, '%x%v') as year_week, COUNT(DISTINCT v.student_id) as visitor_count")
            ->whereBetween('v.visited_at', [$weeklyStart, $weeklyEnd])
            ->whereIn('s.company_id', $company_ids)
            ->groupBy(DB::raw("DATE_FORMAT(v.visited_at, '%x%v')"))
            ->orderBy(DB::raw("DATE_FORMAT(v.visited_at, '%x%v')"))
            ->get()
            ->keyBy('year_week');

        $weeklyCounts = [];
        $weekCursor = Carbon::parse($weeklyStart);
        while ($weekCursor->lte($weeklyEnd)) {
            $yearWeekKey = $weekCursor->format('oW');
            $label = 'Week ' . $this->getWeekNumber($weekCursor);

            $weeklyCounts[] = [
                'week_start' => $label,
                'mps_count' => $weeklyRaw[$yearWeekKey]->mps_count ?? 0,
                'visitor_count' => $weeklyVisitorRaw[$yearWeekKey]->visitor_count ?? 0,
            ];
            $weekCursor->addWeek();
        }

        // ===== MONTHLY =====
        $monthlyRaw = DB::table('m_p_s as mps')
            ->join('students as s', 'mps.student_id', '=', 's.id')
            ->selectRaw('YEAR(mps.arrested_at) as year, MONTH(mps.arrested_at) as month, COUNT(DISTINCT mps.student_id) as mps_count')
            ->whereBetween('mps.arrested_at', [$monthlyStart, $monthlyEnd])
            ->whereIn('s.company_id', $company_ids)
            ->groupByRaw('YEAR(mps.arrested_at), MONTH(mps.arrested_at)')
            ->orderByRaw('YEAR(mps.arrested_at), MONTH(mps.arrested_at)')
            ->get()
            ->mapWithKeys(fn($item) => [sprintf('%04d-%02d', $item->year, $item->month) => $item->mps_count]);

        $monthlyVisitorRaw = DB::table('m_p_s_visitors as v')
            ->join('students as s', 'v.student_id', '=', 's.id')
            ->selectRaw('YEAR(v.visited_at) as year, MONTH(v.visited_at) as month, COUNT(DISTINCT v.student_id) as visitor_count')
            ->whereBetween('v.visited_at', [$monthlyStart, $monthlyEnd])
            ->whereIn('s.company_id', $company_ids)
            ->groupByRaw('YEAR(v.visited_at), MONTH(v.visited_at)')
            ->orderByRaw('YEAR(v.visited_at), MONTH(v.visited_at)')
            ->get()
            ->mapWithKeys(fn($item) => [sprintf('%04d-%02d', $item->year, $item->month) => $item->visitor_count]);

        $monthlyCounts = [];
        $monthCursor = Carbon::parse($monthlyStart);
        while ($monthCursor->lte($monthlyEnd)) {
            $monthKey = $monthCursor->format('Y-m');
            $monthlyCounts[] = [
                'month_label' => $monthCursor->format('F Y'),
                'mps_count' => $monthlyRaw[$monthKey] ?? 0,
                'visitor_count' => $monthlyVisitorRaw[$monthKey] ?? 0,
            ];
            $monthCursor->addMonth();
        }

        // ===== CURRENT + TOP STUDENTS =====
        $currentLockedUpStudents = Mps::whereNull('released_at')
            ->whereHas('student', fn($q) => $q->whereIn('company_id', $company_ids))
            ->with('student')
            ->get();

        $topLockedUpStudents = Mps::select(
            'student_id',
            DB::raw('COUNT(*) as count'),
            'students.first_name',
            'students.last_name',
            'students.company_id'
        )
            ->join('students', 'm_p_s.student_id', '=', 'students.id')
            ->when($start_date, fn($q) => $q->where('arrested_at', '>=', $start_date))
            ->when($end_date, fn($q) => $q->where('arrested_at', '<=', $end_date))
            ->whereIn('students.company_id', $company_ids)
            ->groupBy('student_id', 'students.first_name', 'students.last_name', 'students.company_id')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        $topVisitedStudents = MpsVisitor::select(
            'student_id',
            DB::raw('COUNT(*) as count'),
            'students.first_name',
            'students.last_name',
            'students.company_id'
        )
            ->join('students', 'm_p_s_visitors.student_id', '=', 'students.id')
            ->when($start_date, fn($q) => $q->where('visited_at', '>=', $start_date))
            ->when($end_date, fn($q) => $q->where('visited_at', '<=', $end_date))
            ->whereIn('students.company_id', $company_ids)
            ->groupBy('student_id', 'students.first_name', 'students.last_name', 'students.company_id')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // ===== RETURN DATA INCLUDING START + END DATE =====
        return [
            'dailyCounts' => $dailyCounts,
            'weeklyCounts' => $weeklyCounts,
            'monthlyCounts' => $monthlyCounts,
            'currentLockedUpStudents' => $currentLockedUpStudents,
            'topLockedUpStudents' => $topLockedUpStudents,
            'topVisitedStudents' => $topVisitedStudents,
            'start_date' => $dailyStart->format('Y-m-d'),
            'end_date' => $dailyEnd->format('Y-m-d'),
        ];
    }
}
