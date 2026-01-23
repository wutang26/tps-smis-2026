<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\Beat;
use App\Models\Company;
use App\Models\BeatReserve;
use App\Models\BeatLeaderOnDuty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

class BeatController extends Controller
{
    protected $usedStudentIds = [];
    public function beatsByDate(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $companies = Company::whereHas('guardAreas.beats', function ($query) use ($date) {
            $query->where('date', $date);
        })
        ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
            $query->where('date', $date);
        })
        ->with(['guardAreas.beats' => function ($query) use ($date) {
            $query->where('date', $date);
        }, 'patrolAreas.beats' => function ($query) use ($date) {
            $query->where('date', $date);
        }])
        ->get();

        return view('beats.by_date', compact('companies', 'date'));
    }


    
    // public function showBeats()
    // {
    //     $companies = Company::with(['guardAreas.beats', 'patrolAreas.beats'])->get();

    //     return view('beats.index', compact('companies'));
    // }

      /**
     * Display a listing of beats.
     */
    public function index()
    {
        $beats = Beat::with(['guardArea', 'patrolArea'])->orderBy('date', 'desc')->get();
        return view('beats.index', compact('beats'));
    }

    public function beatCreate()
    {
        $beats = Beat::with(['guardArea', 'patrolArea'])->orderBy('date', 'desc')->get();
        return view('beats.beat_create', compact('beats'));
    }
    

public function edit($beat_id){
    $beat  = Beat::find($beat_id);
    $beats  = Beat::where('id', $beat_id)->get();
    $stud =     Student::whereIn('id', json_decode($beat->student_ids))->get();
    $eligible_students = Student::where('company_id',2)->whereIn('platoon',[8,9,10,11,12,13,14])->where('beat_round',1)->where('beat_status',1)->get();
    return  view('beats.edit', compact('beat','beats', 'eligible_students', 'stud'));
}

public function update(Request $request, $beat_id){
    $replace_student = array_map('intval',$request->input('replace_students'));
    $student = $request->input('students');
    $student = array_map('intval', $student);
    
     $beat = Beat::where('id', $beat_id)->first();
    $assignedStudentIds = json_decode($beat->student_ids);

    
    //$array = array_diff($assignedStudentIds, $replace_student); 

    $newArray = array_map(function($value) use ($replace_student, $student) {
        $key = array_search($value, $replace_student);
        return ($key !== false) ? $student[$key] : $value;
    }, $assignedStudentIds);

        if (!empty($replace_student)) {
            Student::whereIn('id', $replace_student)
                ->where('beat_round', '>', 0) // Prevent negative values
                ->decrement('beat_round');
        }

    if ($beat) {
        $beat->update([
            'student_ids'   => json_encode($newArray)
        ]);

            
        if (!empty($student)) {
            Student::whereIn('id', $student)
                ->increment('beat_round');
        }
    } 

    return redirect()->route('beats.byDate')->with('success', 'Beat updated successfully.');
}

    public function generatePDF(Request $request, $companyId)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $company = Company::where('id', $companyId)
            ->where(function ($query) use ($date) {
                $query->whereHas('guardAreas.beats', function ($query) use ($date) {
                    $query->where('date', $date);
                })
                ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
                    $query->where('date', $date);
                });
            })
            ->with(['guardAreas.beats' => function ($query) use ($date) {
                $query->where('date', $date);
            }, 'patrolAreas.beats' => function ($query) use ($date) {
                $query->where('date', $date);
            }])
            ->firstOrFail();

        $summary = [];
        $totalPlatoonCount = [];

        foreach ($company->guardAreas as $area) {
            foreach ($area->beats as $beat) {
                $this->updateSummary($summary, $totalPlatoonCount, $beat);
            }
        }

        foreach ($company->patrolAreas as $area) {
            foreach ($area->beats as $beat) {
                $this->updateSummary($summary, $totalPlatoonCount, $beat);
            }
        }

        // return view('beats_summary', compact('company', 'date', 'summary', 'totalPlatoonCount'));
        
    //     // 🟢 Step 4: Generate PDF and pass summary + total platoon count
        $pdf = Pdf::loadView('beats.pdf', compact('company', 'date', 'summary', 'totalPlatoonCount'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('beats_' . $company->name . '_' . $date . '.pdf');
    }  

/**
 * Updates the summary of assigned students per platoon per time slot.
 */
private function updateSummary(&$summary, &$totalPlatoonCount, $beat)
{
    $timeSlot = $beat->start_at . " - " . $beat->end_at;
    $studentIds = json_decode($beat->student_ids, true);
    $students = Student::whereIn('id', $studentIds)->get();

    if (!isset($summary[$timeSlot])) {
        $summary[$timeSlot] = [];
    }

    foreach ($students as $student) {
        $platoon = $student->platoon;

        if (!isset($summary[$timeSlot][$platoon])) {
            $summary[$timeSlot][$platoon] = 0;
        }

        $summary[$timeSlot][$platoon]++;

        if (!isset($totalPlatoonCount[$platoon])) {
            $totalPlatoonCount[$platoon] = 0;
        }

        $totalPlatoonCount[$platoon]++;
    }
}


    function generateBeats($areas, $studentsByCompany, $studentsByPlatoon, $beatType, $date, &$usedStudentIds)
{
    $beats = [];

    // Dynamically determine platoon groups A & B
    $totalPlatoons = $studentsByPlatoon->keys()->sort()->values();
    $mid = floor($totalPlatoons->count() / 2);
    $groupA = $totalPlatoons->slice(0, $mid)->values();
    $groupB = $totalPlatoons->slice($mid)->values();
    $currentGroup = (Carbon::parse($date)->day % 2 === 1) ? $groupA : $groupB;

    foreach ($areas as $areaData) {
        $area = $areaData['area'];
        $startAt = $areaData['start_at'];
        $endAt = $areaData['end_at'];
        $company_id = $area->company_id;
        $requiredStudents = $area->number_of_guards;
        $assignedStudentIds = [];

        // Fetch eligible students
        $companyStudents = $studentsByCompany[$company_id] ?? collect();
        $companyStudents = $companyStudents
            ->whereIn('platoon', $currentGroup)
            ->whereNotIn('id', $usedStudentIds)
            ->values();

        // Apply Gender Restrictions
        if (!empty($area->beat_exception_ids)) {
            $exceptions = json_decode($area->beat_exception_ids, true);
            if (in_array(1, $exceptions)) {
                $companyStudents = $companyStudents->where('gender', 'F')->values();
            } elseif (in_array(2, $exceptions)) {
                $companyStudents = $companyStudents->where('gender', 'M')->values();
            } elseif (in_array(3, $exceptions)) {
                $femaleStudents = $companyStudents->where('gender', 'F')->values();
                $maleStudents = $companyStudents->where('gender', 'M')->values();

                // Calculate the count for each gender
                $femaleCount = $femaleStudents->count();
                $maleCount = $maleStudents->count();

                // Ensure the number of female students is either greater than or less than the number of male students
                if ($femaleCount !== $maleCount) {
                    // Combine both collections without adjusting
                    $companyStudents = $femaleStudents->merge($maleStudents)->values();
                } else {
                    // Adjust to ensure femaleCount is not equal to maleCount
                    $femaleStudents = $femaleStudents->take($femaleCount + 2);
                    $companyStudents = $maleStudents->merge($femaleStudents)->values();
                }
            }
        } else {
            // Prioritize females during the day and males at night but allow both if necessary
            if ($startAt === '06:00' || $startAt === '12:00') {
                $preferredStudents = $companyStudents->where('gender', 'F');
            } else {
                $preferredStudents = $companyStudents->where('gender', 'M');
            }

            if ($preferredStudents->isNotEmpty()) {
                $companyStudents = $preferredStudents->values();
            }
        }

        // Sort students by beat_round (ascending) and id (ascending)
        $companyStudents = $companyStudents->sort(function ($a, $b) {
            if ($a->beat_round == $b->beat_round) {
                return $a->id <=> $b->id;
            }
            return $a->beat_round <=> $b->beat_round;
        })->values();

        // Group students by platoon
        $studentsByPlatoonInGroup = $companyStudents->groupBy('platoon');
        $platoonsInGroup = $currentGroup->toArray();
        $numPlatoons = count($platoonsInGroup);

        if ($numPlatoons > 0 && $requiredStudents > 0) {
            // Calculate students per platoon
            $studentsPerPlatoon = intdiv($requiredStudents, $numPlatoons);
            $remainingStudents = $requiredStudents % $numPlatoons;

            // dd($platoonsInGroup);
            // Shuffle platoons for fair distribution of remaining students
            shuffle($platoonsInGroup);

            foreach ($platoonsInGroup as $platoon) {
                $studentsNeeded = $studentsPerPlatoon;
                if ($remainingStudents > 0) {
                    $studentsNeeded += 1;
                    $remainingStudents -= 1;
                }

                $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? collect();
                $platoonStudents = $platoonStudents->whereNotIn('id', $usedStudentIds)->values();
                $availableStudents = $platoonStudents->count();

                // Assign as many students as possible, up to the number needed
                $studentsToAssign = min($studentsNeeded, $availableStudents);
                $selectedStudents = $platoonStudents->take($studentsToAssign)->pluck('id')->toArray();

                $assignedStudentIds = array_merge($assignedStudentIds, $selectedStudents);
                $usedStudentIds = array_merge($usedStudentIds, $selectedStudents);
            }

            // Fill any remaining slots with available students
            $unfilledSpots = $requiredStudents - count($assignedStudentIds);
            if ($unfilledSpots > 0) {
                $remainingStudents = $companyStudents->whereNotIn('id', $usedStudentIds)->pluck('id')->toArray();
                $additionalStudents = array_slice($remainingStudents, 0, $unfilledSpots);
                $assignedStudentIds = array_merge($assignedStudentIds, $additionalStudents);
                $usedStudentIds = array_merge($usedStudentIds, $additionalStudents);
            }



            // Increment beat_round for assigned students
            Student::whereIn('id', $assignedStudentIds)->increment('beat_round');
        }

        if (!empty($assignedStudentIds)) {
            $beats[] = [
                'beatType_id'   => ($beatType === 'guards') ? 1 : 2,
                'guardArea_id'  => ($beatType === 'guards') ? $area->id : null,
                'patrolArea_id' => ($beatType === 'patrols') ? $area->id : null,
                'student_ids'   => json_encode($assignedStudentIds),
                'date'          => $date,
                'start_at'      => $startAt,
                'end_at'        => $endAt,
                'status'        => true,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ];
        }
    }

    return $beats;
}

public function fillBeats(Request $request)
{
    $date = $request->input('date', Carbon::today()->toDateString());

    if (Beat::where('date', $date)->exists()) {
        return response()->json(['message' => 'Beats already generated for ' . $date], 200);
    }

    // Fetch active students (only those eligible for beats)
    $activeStudents = Student::where('beat_status', 1)
        ->where('session_programme_id', 1)
        ->orderBy('beat_round')
        ->orderBy('id')
        ->get();

    // Group students by company and platoon
    $studentsByCompany = $activeStudents->groupBy('company_id');
    $studentsByPlatoon = $activeStudents->groupBy('platoon');

    // Fetch guard and patrol areas with proper time filters
    $guardAreas = $this->filterAreasByTimeExceptions(GuardArea::all());
    $patrolAreas = $this->filterAreasByTimeExceptions(PatrolArea::all());

    // Track used student IDs to prevent duplication across all roles
    $usedStudentIds = [];

    // Generate beats for guards
    $guardBeats = $this->generateBeats($guardAreas, $studentsByCompany, $studentsByPlatoon, 'guards', $date, $usedStudentIds);

    // Generate beats for patrols
    $patrolBeats = $this->generateBeats($patrolAreas, $studentsByCompany, $studentsByPlatoon, 'patrols', $date, $usedStudentIds);

    // Assign reserves for each company
    $reserveStudents = [];
    foreach ($studentsByCompany as $companyId => $students) {
        $reserveStudents = array_merge($reserveStudents, $this->assignReserves($companyId, $date, $usedStudentIds));
    }

    // Assign Leaders on Duty for each company
    $leadersOnDuty = [];
    foreach ($studentsByCompany as $companyId => $students) {
        $leadersOnDuty = array_merge($leadersOnDuty, $this->assignLeadersOnDuty($companyId, $date, $usedStudentIds));
    }

    // Save everything to the database in a single transaction
    DB::transaction(function () use ($guardBeats, $patrolBeats, $reserveStudents, $leadersOnDuty) {
        foreach (array_merge($guardBeats, $patrolBeats) as $beatData) {
            $beat = Beat::create($beatData);
            $beat->students()->attach(json_decode($beatData['student_ids']));
        }

        // Save reserve students
        foreach ($reserveStudents as $reserve) {
            BeatReserve::create($reserve);
        }

        // Save leaders on duty
        foreach ($leadersOnDuty as $leader) {
            BeatLeaderOnDuty::create($leader);
        }
    });

    return response()->json(['message' => 'Beats generated successfully for ' . $date], 200);
}



function filterAreasByTimeExceptions($areas)
{
    $filteredAreas = [];
    $timeRanges = [
        1 => ['start' => '06:00', 'end' => '12:00'],
        2 => ['start' => '12:00', 'end' => '18:00'],
        3 => ['start' => '18:00', 'end' => '00:00'],
        4 => ['start' => '00:00', 'end' => '06:00']
    ];

    foreach ($areas as $area) {
        $exceptions = json_decode($area->beat_time_exception_ids);

        if (empty($exceptions)) {
            // No time exceptions, area is guarded 24hrs
            foreach ($timeRanges as $range) {
                $filteredAreas[] = [
                    'area' => $area,
                    'start_at' => $range['start'],
                    'end_at' => $range['end']
                ];
            }
        } else {
            // Area has time exceptions, filter based on exceptions
            foreach ($exceptions as $exception) {
                if (isset($timeRanges[$exception])) {
                    $filteredAreas[] = [
                        'area' => $area,
                        'start_at' => $timeRanges[$exception]['start'],
                        'end_at' => $timeRanges[$exception]['end']
                    ];
                }
            }
        }
    }

    return $filteredAreas;
}


/**
 * Function to Assign Leaders on Duty
 */

 function assignLeadersOnDuty($companyId, $date, &$usedStudentIds)
{
    // Check if leaders on duty have already been assigned for the given date
    $existingLeadersOnDuty = BeatLeaderOnDuty::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->exists();

    if ($existingLeadersOnDuty) {
        // Leaders on duty already assigned for this date, return an empty array
        return [];
    }

    // Fetch already assigned student IDs for this company (Guard, Patrol, Reserve)
    $assignedStudentIds = Beat::where('date', $date)
        ->whereHas('guardArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->orWhereHas('patrolArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->pluck('student_ids')
        ->map(fn($ids) => json_decode($ids, true))
        ->flatten()
        ->toArray();

    $assignedReserveIds = BeatReserve::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->pluck('student_id')
        ->toArray();

    $alreadyAssigned = array_merge($assignedStudentIds, $assignedReserveIds, $usedStudentIds);

    // Fetch eligible students
    $eligibleStudents = Student::where('beat_status', 3)
        ->where('company_id', $companyId)
        ->whereNotIn('id', $alreadyAssigned) // Avoid duplication
        ->orderBy('beat_leader_round', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    if ($eligibleStudents->isEmpty()) {
        return []; // Ensure an array is returned
    }

    // Select one male and one female leader
    $maleLeader = $eligibleStudents->where('gender', 'M')->first();
    $femaleLeader = $eligibleStudents->where('gender', 'F')->first();

    $leaders = collect([$maleLeader, $femaleLeader])->filter()->map(function ($leader) use ($companyId, $date, &$usedStudentIds) {
        // Mark leader as used
        $usedStudentIds[] = $leader->id;

        return [
            'student_id' => $leader->id,
            'company_id' => $companyId,
            'beat_date' => $date
        ];
    })->toArray();

    // Update beat_leader_round count for selected leaders
    foreach ($leaders as $leader) {
        Student::where('id', $leader['student_id'])->increment('beat_leader_round');
    }

    return $leaders; // Always return an array
}


/**
 * Function to Assign Reserves (6 Males, 4 Females, One Per Platoon)
 */

 function assignReserves($companyId, $date, &$usedStudentIds)
{
    // Check if reserves have already been assigned for the given date
    $existingReserves = BeatReserve::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->exists();

    if ($existingReserves) {
        // Reserves already assigned for this date, return an empty array
        return [];
    }

    // Fetch already assigned student IDs for this company (Guard, Patrol, Leaders)
    $assignedStudentIds = Beat::where('date', $date)
        ->whereHas('guardArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->orWhereHas('patrolArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->pluck('student_ids')
        ->map(fn($ids) => json_decode($ids, true))
        ->flatten()
        ->toArray();

    $assignedLeaderIds = BeatLeaderOnDuty::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->pluck('student_id')
        ->toArray();

    $alreadyAssigned = array_merge($assignedStudentIds, $assignedLeaderIds, $usedStudentIds);

    // Fetch eligible students (ordered by beat_round and id)
    $eligibleStudents = Student::where('beat_status', 1)
        ->where('company_id', $companyId)
        ->whereNotIn('id', $alreadyAssigned) // Avoid duplication
        ->orderBy('beat_round', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    if ($eligibleStudents->isEmpty()) {
        return []; // Ensure an array is returned
    }

    // Dynamically determine platoon groups A & B
    $totalPlatoons = $eligibleStudents->groupBy('platoon')->keys()->sort()->values();
$mid = floor($totalPlatoons->count() / 2);
$groupA = $totalPlatoons->slice(0, $mid)->values();
$groupB = $totalPlatoons->slice($mid)->values();
$currentGroup = (Carbon::parse($date)->day % 2 === 1) ? $groupA : $groupB;

// Group students by platoon in the current group
$studentsByPlatoonInGroup = $eligibleStudents->whereIn('platoon', $currentGroup)->groupBy('platoon');
$platoonsInGroup = $currentGroup->toArray();

$reserves = collect();
$maleReservesNeeded = 6;
$femaleReservesNeeded = 4;

// Ensure each platoon in the current group provides at least one male and one female if needed
foreach ($platoonsInGroup as $platoon) {
    if ($maleReservesNeeded == 0 && $femaleReservesNeeded == 0) {
        break;
    }

    $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? collect();
    
    // Select one male if needed and available
    if ($maleReservesNeeded > 0) {
        $platoonMales = $platoonStudents->where('gender', 'M')->take(1);
        foreach ($platoonMales as $male) {
            if ($maleReservesNeeded > 0 && $reserves->count() < 10) {
                $reserves->push($male);
                $maleReservesNeeded--;
                break;
            }
        }
    }
    
    // Select one female if needed and available
    if ($femaleReservesNeeded > 0) {
        $platoonFemales = $platoonStudents->where('gender', 'F')->take(1);
        foreach ($platoonFemales as $female) {
            if ($femaleReservesNeeded > 0 && $reserves->count() < 10) {
                $reserves->push($female);
                $femaleReservesNeeded--;
                break;
            }
        }
    }
}

// Ensure total reserves are 6 males and 4 females
$remainingReservesNeeded = 10 - $reserves->count();

// Select additional male and female reserves as needed from different platoons
$additionalMaleReserves = $eligibleStudents->where('gender', 'M')
    ->whereIn('platoon', $currentGroup)
    ->whereNotIn('id', $reserves->pluck('id'))
    ->take($maleReservesNeeded);

$additionalFemaleReserves = $eligibleStudents->where('gender', 'F')
    ->whereIn('platoon', $currentGroup)
    ->whereNotIn('id', $reserves->pluck('id'))
    ->take($femaleReservesNeeded);

$additionalReserves = $additionalMaleReserves->merge($additionalFemaleReserves)
    ->take($remainingReservesNeeded)
    ->values();

    
    $reserves = $reserves->merge($additionalReserves)->map(function ($student) use ($companyId, $date, &$usedStudentIds) {
        // Mark student as used
        $usedStudentIds[] = $student->id;

        return [
            'student_id' => $student->id,
            'company_id' => $companyId,
            'beat_date' => $date
        ];
    })->toArray();

    // Update beat_status for selected reserves (set to 2)
    Student::whereIn('id', collect($reserves)->pluck('student_id'))->update(['beat_status' => 2]);

    return $reserves; // Always return an array
}


//Beat Report
public function showReport(Request $request)
{
    $companies = Company::all();
    foreach($companies as $company)
    return view('beats.beat_report', ['report' => $this->beatHistory(), 'companies' => $companies]);
}

public function downloadHistoryPdf(){
    $report = $this->beatHistory()[0];
    return view('beats.historyPdf', compact('report'));
    $pdf = Pdf::loadView('beats.historyPdf', compact('report'));
    return $pdf->download("history.pdf");
}

public function showReport2(Request $request)
{
    $startDate = $request->input('start_date', null);
    $endDate = $request->input('end_date', null);
    $dateFilter = $request->input('date_filter', null);
    $report = $this->generateBeatReport($startDate, $endDate, $dateFilter);
    $companies = Company::all();
    
    return view('beats.beat_report', ['report' => $report, 'companies' => $companies]);
}

private function beatHistory(){
    $companies = Company::all();
    $report = [];
    foreach($companies as $company){
        $students = $company->students->where('session_programme_id', 1);
        $totalStudents = count($students);
        $totalEligibleStudents = count($students->where('beat_status', 1));
        $totalIneligibleStudents = count($students->where('beat_status','!=', 1));
        $eligibleStudentsPercent = round((($totalStudents-$totalIneligibleStudents )/$totalStudents) *100, 2);
        $InEligibleStudentsPercent = round((($totalIneligibleStudents )/$totalStudents) *100, 2);

        $guardAreas = count($company->guardAreas);
        $patrolAreas = count($company->patrolAreas);
        $current_round = $company->beatRound[0]->current_round;
        $attained_current_round = count($students->where('beat_round',$current_round)->values());
        $NotAttained_current_round = count($students->where('beat_round','<',$current_round)->values());
        $exceededAttained_current_round = count($students->where('beat_round','>',$current_round)->values());

        $ICTStudents = $students->where('beat_exclusion_vitengo_id',1)->values();
        $ujenziStudents = $students->where('beat_exclusion_vitengo_id',2)->values();
        $hospitalStudents = $students->where('beat_exclusion_vitengo_id',3)->values();
        $emergencyStudents = $students->whereNotNull('beat_emergency')->where('beat_status', 0)->values();
        

        $company = [
            'company_id' => $company->id,
            'company_name' =>$company->name,
            'data'=>[
                'totalStudents' => $totalStudents,
                'totalIneligibleStudents' => $totalIneligibleStudents,
                'totalEligibleStudents' => $totalEligibleStudents,
                'eligibleStudentsPercent' => $eligibleStudentsPercent,
                'InEligibleStudentsPercent' => $InEligibleStudentsPercent,
                'guardAreas'=> $guardAreas,
                'patrolAreas'=> $patrolAreas,
                'current_round' => $current_round,
                'attained_current_round'=> $attained_current_round,
                'NotAttained_current_round' => $NotAttained_current_round,
                'exceededAttained_current_round'=>$exceededAttained_current_round,
                'vitengo' => [
                    [
                    'name' => 'ICT',
                    'students' => $ICTStudents
                    ],
                    [
                        'name' => 'UJENZI',
                        'students' => $ujenziStudents
                    ],
                    [
                        'name' => 'HOSPITAL',
                        'students' => $hospitalStudents
                    ],

                ],
                'emergencyStudents' => $emergencyStudents
            ]
            ];
            array_push($report, $company);
    }
    return $report;
}


public function downloadReport(Request $request)
{
    $startDate = $request->input('start_date', null);
    $endDate = $request->input('end_date', null);
    $dateFilter = $request->input('date_filter', null);
    $report = $this->generateBeatReport($startDate, $endDate, $dateFilter);

    $pdf = PDF::loadView('beats.beat_report_pdf', ['report' => $report]);
    return $pdf->download('beat_report.pdf');
}

private function generateBeatReport($companyId = null, $startDate = null, $endDate = null, $dateFilter = null)
{
    $companies = is_null($companyId) ? Company::all() : Company::where('id', $companyId)->get();

    $beatsQuery = DB::table('beats');

    if ($startDate && $endDate) {
        $beatsQuery->whereBetween('created_at', [$startDate, $endDate]);
    } elseif ($dateFilter === 'weekly') {
        $beatsQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    } elseif ($dateFilter === 'monthly') {
        $beatsQuery->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
    }

    $beats = $beatsQuery->pluck('student_ids');

    $studentsQuery = DB::table('students')->where('session_programme_id', 1);

    // Total number of students per company
    $studentsPerCompany = $studentsQuery
        ->select('company_id', DB::raw('count(*) as total_students'))
        ->groupBy('company_id')
        ->get();

    // Total number of eligible students for beat (status 1 or 3)
    $eligibleStudents = clone $studentsQuery;
    $eligibleStudents = $eligibleStudents->whereIn('beat_status', [1, 3])
        ->select('company_id', DB::raw('count(*) as total_eligible_students'))
        ->groupBy('company_id')
        ->get();

    // Total number of students not eligible for a beat
    $ineligibleStudents = DB::table('students')
        ->where('session_programme_id', 1)
        ->where(function ($query) {
            $query->where('beat_status', 0)
                  ->orWhereNotNull('beat_exclusion_vitengo_id')
                  ->orWhereNotNull('beat_emergency');
        })
        ->select(
            'company_id',
            DB::raw('COUNT(*) as total_ineligible_students'),
            DB::raw('GROUP_CONCAT(first_name, " ", last_name, " ", beat_exclusion_vitengo_id) as student_names'),
            DB::raw('GROUP_CONCAT(beat_exclusion_vitengo_id) as exclusion_vitengo_ids'),
            DB::raw('GROUP_CONCAT(beat_emergency) as beat_emergencies')
        )
        ->groupBy('company_id')
        ->get();
    // Categorize ineligible students
    $vitengoCategories = [];
    $emergencyCategories = [];
    $stringReasons = [];

    foreach ($ineligibleStudents as $student) {
        
        $studentNames = explode(',', $student->student_names);
        $exclusionVitengoIds = explode(',', $student->exclusion_vitengo_ids);
        $beatEmergencies = explode(',', $student->beat_emergencies);

        foreach ($studentNames as $index => $name) {
            $vitengoId = $exclusionVitengoIds[$index] ?? null;
            if ($vitengoId) {
                $vitengo = DB::table('vitengos')->where('id', $vitengoId)->first();
                if ($vitengo) {
                    $vitengoCategories[$student->company_id][$vitengo->name][] = $name;
                } else {
                    $vitengoCategories[$student->company_id]['Unknown Vitengo'][] = $name;
                }
            }

            $emergency = $beatEmergencies[$index] ?? null;
            if ($emergency) {
                if (is_numeric($emergency)) {
                    $patient = DB::table('patients')->where('id', $emergency)->first();
                    $emergencyCategories[$student->company_id][] = [
                        'name' => $name,
                        'reason' => $patient ? $patient->reason : 'Unknown',
                    ];
                } else {
                    $stringReasons[$student->company_id][] = [
                        'name' => $name,
                        'reason' => $emergency,
                    ];
                }
            }
        }
    }

    // Calculate the percentage of eligible and ineligible students
    $percentages = $companies->map(function ($company) use ($studentsPerCompany, $eligibleStudents, $ineligibleStudents) {
        $totalStudents = $studentsPerCompany->firstWhere('company_id', $company->id)->total_students ?? 0;
        $totalEligible = $eligibleStudents->firstWhere('company_id', $company->id)->total_eligible_students ?? 0;
        $totalIneligible = $ineligibleStudents->firstWhere('company_id', $company->id)->total_ineligible_students ?? 0;

        return [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'total_students' => $totalStudents,
            'total_eligible' => $totalEligible,
            'total_ineligible' => $totalIneligible,
            'percent_eligible' => $totalStudents ? ($totalEligible / $totalStudents) * 100 : 0,
            'percent_ineligible' => $totalStudents ? ($totalIneligible / $totalStudents) * 100 : 0,
        ];
    });

    // Count total guard areas and patrol areas separately
    $guardAreasPerCompany = DB::table('guard_areas')
        ->select('company_id', DB::raw('count(*) as total_guard_areas'))
        ->groupBy('company_id')
        ->get();

    $patrolAreasPerCompany = DB::table('patrol_areas')
        ->select('company_id', DB::raw('count(*) as total_patrol_areas'))
        ->groupBy('company_id')
        ->get();

    // Total number of students required to attend the beat per day (sum of number_of_guards)
    $studentsRequiredPerDay = DB::table('guard_areas')
        ->select('company_id', DB::raw('sum(number_of_guards) as total_required_per_day'))
        ->groupBy('company_id')
        ->get()
        ->merge(
            DB::table('patrol_areas')
                ->select('company_id', DB::raw('sum(number_of_guards) as total_required_per_day'))
                ->groupBy('company_id')
                ->get()
        );

    // Show current round status
    $currentRoundStatus = DB::table('beat_rounds')
        ->select('company_id', 'current_round')
        ->get();

    // Calculate student round attendance
    $roundAttendance = DB::table('students')
    ->where('session_programme_id', 1)
    ->select('students.company_id', 
        DB::raw('count(case when beat_round = beat_rounds.current_round then 1 end) as attained_current_round'),
        DB::raw('count(case when beat_round > beat_rounds.current_round then 1 end) as exceeded_current_round'),
        DB::raw('count(case when beat_round < beat_rounds.current_round then 1 end) as not_attained_current_round'))
    ->leftJoin('beat_rounds', 'students.company_id', '=', 'beat_rounds.company_id')
    ->groupBy('students.company_id', 'beat_rounds.current_round')
    ->get();

    // Compile the report data
    $report = [
        'companies' => $percentages,
        'guard_areas' => $guardAreasPerCompany,
        'patrol_areas' => $patrolAreasPerCompany,
        'students_required_per_day' => $studentsRequiredPerDay,
        'current_round_status' => $currentRoundStatus,
        'round_attendance' => $roundAttendance,
        'vitengo_categories' => $vitengoCategories,
        'emergency_categories' => $emergencyCategories,
        'string_reasons' => $stringReasons,
    ];

    return $report;
}



    public function showBeat(Beat $beat)
    {
        $students = Student::whereIn('id', $beat->student_ids)->get();

        return view('beats.show', compact('beat', 'students'));
    }

     /**
     * Remove a beat.
     */
    public function destroy($id)
    {
        $beat = Beat::findOrFail($id);
        $beat->delete();
        return redirect()->route('beats.index')->with('success', 'Beat deleted successfully!');
    }
}