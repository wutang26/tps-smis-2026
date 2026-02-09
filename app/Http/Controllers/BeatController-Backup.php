<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\BeatLeaderOnDuty;
use App\Models\BeatReserve;
use App\Models\BeatRound;
use App\Models\Company;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
            ->with([
                'guardAreas.beats' => function ($query) use ($date) {
                    $query->where('date', $date);
                },
                'patrolAreas.beats' => function ($query) use ($date) {
                    $query->where('date', $date);
                },
            ])
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

    public function edit($beat_id)
    {
        $beat = Beat::find($beat_id);
        $beats = Beat::where('id', $beat_id)->get();
        $stud = Student::whereIn('id', json_decode($beat->student_ids))->get();
        $eligible_students = Student::where('company_id', 2)->whereIn('platoon', [1, 2, 3, 4, 5, 6, 7,8,9])->where('beat_round', '<', 15)->where('beat_status', 1)->where('gender', 'M')->get();

       
        return view('beats.edit', compact('beat', 'beats', 'eligible_students', 'stud'));
    }

    public function createExchange(Beat $beat)
    {
        if ($beat->guardArea) {
            $company = $beat->guardArea->company;
            $beatsToExchange = $company->guardBeats()->where('date', $beat->date)->get();
            $beatsToExchange = $beatsToExchange->merge($company->patrolBeats()->where('date', $beat->date)->get());
        } elseif ($beat->patrolArea) {
            $company = $beat->patrolArea->company;
            $beatsToExchange = $company->patrolBeats()->where('date', $beat->date)->get();
            $beatsToExchange = $beatsToExchange->merge($company->guardBeats()->where('date', $beat->date)->get());
        }
        $beat_students = Student::whereIn('id', json_decode($beat->student_ids))->orderBy('platoon')->get();
        // $beatsToExchange = Beat::where('date', $beat->created_at->format('Y-m-d'))->get();
        $beatsToExchange = $beatsToExchange->map(function ($_beat) use ($beat) {
            // Decode the JSON-encoded student_ids to an array
            $studentIds = json_decode($_beat->student_ids);
            // Fetch the students associated with these IDs (assuming you have a Student model)
            $students = Student::whereIn('id', $studentIds)
                ->whereNotIn('id', json_decode($beat->student_ids))->get();

            // Return an array with students and the specific beat they belong to
            return $students->map(function ($student) use ($_beat) {
                return [
                    'student' => $student,
                    'beat' => $_beat,
                ];
            });
        })->flatten(1) // Flatten the array so each entry is a single student-beat pair
            ->sortBy(function ($item) {
                // Sort by the 'platoon' of each student in ascending order
                return $item['student']->platoon;
            });

        return view('beats.exchange', compact('beat', 'beatsToExchange', 'beat_students'));
    }

    public function update(Request $request, $beat_id)
    {
        $replace_student = array_map('intval', $request->input('replace_students'));
        $student = $request->input('students');
        $student = array_map('intval', $student);

        $beat = Beat::where('id', $beat_id)->first();
        $assignedStudentIds = json_decode($beat->student_ids);

        // $array = array_diff($assignedStudentIds, $replace_student);

        $newArray = array_map(function ($value) use ($replace_student, $student) {
            $key = array_search($value, $replace_student);

            return ($key !== false) ? $student[$key] : $value;
        }, $assignedStudentIds);

        if (! empty($replace_student)) {
            Student::whereIn('id', $replace_student)
                ->where('beat_round', '>', 0) // Prevent negative values
                ->decrement('beat_round');
        }

        if ($beat) {
            $beat->update([
                'student_ids' => json_encode($newArray),
            ]);

            if (! empty($student)) {
                Student::whereIn('id', $student)->increment('beat_round');
                Student::whereIn('id', $student)->update(['beat_status' => 14]);
            }
        }

        return redirect()->route('beats.byDate')->with('success', 'Beat updated successfully.');
    }

    public function exchange(Request $request, Beat $beat)
    {
        // Retrieve current and exchange student IDs from the request
        $exchange_students = $request->input('exchange_students'); // Array of exchange student objects
        $current_students = $request->input('current_students'); // Array of current student IDs to replace

        // Ensure the current_students and exchange_students have the same length
        if (count($current_students) !== count($exchange_students)) {
            return response()->json(['error' => 'The number of current students must match the number of exchange students.'], 400);
        }

        // Get the current student_ids on the beat (assuming this is a JSON string and needs to be decoded)
        $student_ids = json_decode($beat->student_ids); // Assuming this is an array or collection

        // Loop through the current students and swap their values with the exchange students at the same index
        foreach ($current_students as $index => $current_student_id) {
            // Find the index of the current student ID in the student_ids array
            $key = array_search($current_student_id, $student_ids);

            // If the current student ID is found, replace it with the corresponding exchange student ID
            if ($key !== false) {
                // Decode the exchange student object to get the student's ID and the Beat ID
                $exchange_student = json_decode($exchange_students[$index]);

                // Retrieve the old exchange student ID
                $old_exchange_student = $exchange_student->student->id;

                // Find the Beat object for the exchange student (the "exchange_beat")
                $exchange_beat = Beat::find($exchange_student->beat->id);

                // Update the exchange_students array with the current student ID at the corresponding index
                $exchange_students[$index] = $student_ids[$key];

                // Now replace the current student ID in student_ids with the old exchange student ID
                $student_ids[$key] = $old_exchange_student;

                // If the exchange_beat exists, update its student_ids
                if ($exchange_beat) {
                    // Decode the current student_ids of the exchange_beat
                    $exchange_student_ids = json_decode($exchange_beat->student_ids);

                    // Find and replace the student ID in the exchange_beat with the current student ID
                    $exchange_key = array_search($old_exchange_student, $exchange_student_ids);

                    if ($exchange_key !== false) {
                        // Replace the old exchange student ID with the current student ID
                        $exchange_student_ids[$exchange_key] = json_decode($current_student_id);

                        // Update the exchange_beat's student_ids field with the modified list (encoded as JSON)
                        $exchange_beat->student_ids = json_encode($exchange_student_ids);
                        $exchange_beat->save();  // Save the changes to the exchange_beat
                    }
                }
            }
        }

        // Update the original Beat's student_ids with the new student_ids array (encoded as JSON)
        $beat->student_ids = json_encode($student_ids);

        // Save the updated Beat model
        $beat_saved = $beat->save();  // Save the changes to the original beat

        return redirect()->route('beats.byDate', ['date' => $beat->date])->with('success', 'Students beat exchanged successfully.');
    }

    public function exchange1(Request $request, Beat $beat)
    {
        $exchange_students = $request->input('exchange_students');
        $current_students = $request->input('current_students');
        // change current student
        foreach ($beat->students as $student) {
            for ($i = 0; $i < count($exchange_students); $i++) {
                $exchange_student = json_decode($exchange_students[$i])->student;
                // return $exchange_student->id;

            }
            for ($i = 0; $i < count($current_students); $i++) {
                if ($student->id == $current_students[$i]) {
                    $student->id = $current_students[$i];
                }
            }

        }

        return json_decode($exchange_students[0])->student->id;
    }

    // public function generatePDF(Request $request, $companyId)
    // {
    //     $date = $request->input('date', Carbon::today()->toDateString());

    //     $company = Company::where('id', $companyId)
    //         ->where(function ($query) use ($date) {
    //             $query->whereHas('guardAreas.beats', function ($query) use ($date) {
    //                 $query->where('date', $date);
    //             })
    //                 ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
    //                     $query->where('date', $date);
    //                 });
    //         })
    //         ->with([
    //             'guardAreas.beats' => function ($query) use ($date) {
    //                 $query->where('date', $date);
    //             },
    //             'patrolAreas.beats' => function ($query) use ($date) {
    //                 $query->where('date', $date);
    //             },
    //         ])
    //         ->firstOrFail();

    //     $summary = [];
    //     $totalPlatoonCount = [];

    //     foreach ($company->guardAreas as $area) {
    //         foreach ($area->beats as $beat) {
    //             $this->updateSummary($summary, $totalPlatoonCount, $beat);
    //         }
    //     }

    //     foreach ($company->patrolAreas as $area) {
    //         foreach ($area->beats as $beat) {
    //             $this->updateSummary($summary, $totalPlatoonCount, $beat);
    //         }
    //     }

    //     // return view('beats_summary', compact('company', 'date', 'summary', 'totalPlatoonCount'));

    //     //     // 🟢 Step 4: Generate PDF and pass summary + total platoon count
    //     $pdf = Pdf::loadView('beats.pdf', compact('company', 'date', 'summary', 'totalPlatoonCount'))
    //         ->setPaper('a4', 'landscape');

    //     return $pdf->stream('beats_'.$company->name.'_'.$date.'.pdf');
    // }


    public function generatePDF(Request $request, $companyId)
{
    $date = $request->input('date', Carbon::today()->toDateString());

    //  Fetch company + beats (unchanged logic)
    $company = Company::where('id', $companyId)
        ->where(function ($query) use ($date) {
            $query->whereHas('guardAreas.beats', function ($query) use ($date) {
                $query->where('date', $date);
            })
            ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
                $query->where('date', $date);
            });
        })
        ->with([
            'guardAreas.beats' => fn ($q) => $q->where('date', $date),
            'patrolAreas.beats' => fn ($q) => $q->where('date', $date),
        ])
        ->firstOrFail();

    //  Assign leaders ONCE (CRITICAL)
    $this->assignLeadersOnDuty($companyId, $date);

    //  Fetch leaders for PDF
    $leader = BeatLeaderOnDuty::with('student')
        ->where('company_id', $companyId)
        ->whereDate('beat_date', $date)
        ->get();

        $leader = BeatLeaderOnDuty::with('student')
    ->where('company_id', $companyId)
    ->whereDate('beat_date', $date)
    ->get();


    //  Build summary (unchanged)
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

    //  PASS $leader TO THE VIEW (THIS WAS MISSING)
    $pdf = Pdf::loadView(
        'beats.pdf',
        compact('company', 'date', 'summary', 'totalPlatoonCount', 'leader')
    )->setPaper('a4', 'landscape');

    return $pdf->stream('beats_'.$company->name.'_'.$date.'.pdf');
}

    /**
     * Updates the summary of assigned students per platoon per time slot.
     */
    private function updateSummary(&$summary, &$totalPlatoonCount, $beat)
    {
        $timeSlot = $beat->start_at.' - '.$beat->end_at;
        $studentIds = json_decode($beat->student_ids, true);
        $students = Student::whereIn('id', $studentIds)->get();

        if (! isset($summary[$timeSlot])) {
            $summary[$timeSlot] = [];
        }

        foreach ($students as $student) {
            $platoon = $student->platoon;

            if (! isset($summary[$timeSlot][$platoon])) {
                $summary[$timeSlot][$platoon] = 0;
            }

            $summary[$timeSlot][$platoon]++;

            if (! isset($totalPlatoonCount[$platoon])) {
                $totalPlatoonCount[$platoon] = 0;
            }

            $totalPlatoonCount[$platoon]++;
        }
    }

// //Generate Beats Function Starts Here
//     public function generateBeats($areas, $studentsByCompany, $studentsByPlatoon, $beatType, $date, &$usedStudentIds)
//     {
       
//                      // Allow script to run longer for large data
//             ini_set('max_execution_time', 300);
//             set_time_limit(300);

        

//             $cooldownDate = Carbon::parse($date)->subDays(4); //rest period of 4 days
//              $beats = [];

//         // // Dynamically determine platoon groups for two groups A & B
//             // $totalPlatoons = $studentsByPlatoon->keys()->sort()->values();

//             // $mid = floor($totalPlatoons->count() / 2);
//             // $groupA = $totalPlatoons->slice(0, $mid)->values();
//             // $groupB = $totalPlatoons->slice($mid)->values();

//             // $currentGroup = Carbon::parse($date)->day % 2 === 1
//             //     ? $groupA
//             //     : $groupB;

//         // Dynamically determine platoon group (single group for now)
//             // $totalPlatoons = $studentsByPlatoon->keys()->sort()->values();

//             // $groupA = $totalPlatoons;

//             // // Single group system
//             // $currentGroup = $groupA;



//         $groupBx = [10, 11, 12,13,14,15,16,17,18];

//         // Convert array into a Laravel Collection
//         $currentGroup = collect($groupBx);
//         // dd($currentGroup);

//         // Fetch guard and patrol areas with proper time filters
//         // $_guardAreas = $this->filterAreasByTimeExceptions(GuardArea::all());
//         // $_patrolAreas = $this->filterAreasByTimeExceptions(PatrolArea::all());
//         // $number_of_guards = 0;
//         /*foreach ($_guardAreas as $_guardArea) {
//             if ($company->id == $_guardArea['area']['company_id']) {
//                 $number_of_guards += $_guardArea['area']['number_of_guards'];
//             }
//         }

//         foreach ($_patrolAreas as $_patrolArea) {
//             if ($company->id == $_patrolArea['area']['company_id']) {
//                 $number_of_guards += $_patrolArea['area']['number_of_guards'];
//             }
//         }*/

//         foreach ($areas as $areaData) {
//             $area = $areaData['area'];
//             $startAt = $areaData['start_at'];
//             $endAt = $areaData['end_at'];
//             $company_id = $area->company_id;
//             $requiredStudents = $area->number_of_guards;
//             $assignedStudentIds = [];

//             // Fetch eligible students
//             $companyStudents = $studentsByCompany[$company_id] ?? collect();

//             $companyStudents = $companyStudents
//                 ->whereIn('platoon', $currentGroup)
//                 ->whereNotIn('id', $usedStudentIds) 
//                 ->sortBy(fn ($s) => $s->last_assigned_at ?? now()->subYears(10))
//                 ->values();
                
//             // $selectedStudents = collect();
//             // $EligibleFemales =   count($companyStudents->where('gender', 'F'));
//             // $EligibleMales =   count($companyStudents->where('gender', 'M'));
//             // $EligibleFemalesPercentage =  $EligibleFemales/($EligibleFemales+$EligibleMales);
//             // $platoonNoFemales = ceil((137 *$EligibleFemalesPercentage)/7);
//             // $platoonNoMales = floor((137 *(1-$EligibleFemalesPercentage))/7);

//             // $total =($platoonNoFemales+ $platoonNoMales) *7;
//             // foreach ($currentGroup as $platoon) {
//             //     $platoonStudents = $companyStudents->where('platoon', $platoon);
//             //     $females = $platoonStudents->where('gender', 'F')->take($platoonNoFemales);
//             //     $males = $platoonStudents->where('gender', 'M')->take($platoonNoMales);

//             //     $selectedStudents = $selectedStudents->merge($females)->merge($males);
//             // }

//             // Apply Gender Restrictions
//             if (! empty($area->beat_exception_ids)) {
//                 $exceptions = json_decode($area->beat_exception_ids, true);
//                 if (in_array(1, $exceptions)) {
//                     $companyStudents = $companyStudents->where('gender', 'F')->values();
//                 } elseif (in_array(2, $exceptions)) {
//                     $companyStudents = $companyStudents->where('gender', 'M')->values();
//                 } elseif (in_array(3, $exceptions)) {
//                     $femaleStudents = $companyStudents->where('gender', 'F')->values();
//                     $maleStudents = $companyStudents->where('gender', 'M')->values();

//                     // Calculate the count for each gender
//                     $femaleCount = $femaleStudents->count();
//                     $maleCount = $maleStudents->count();

//                     // Ensure the number of female students is either greater than or less than the number of male students
//                     if ($femaleCount !== $maleCount) {
//                         // Combine both collections without adjusting
//                         $companyStudents = $femaleStudents->merge($maleStudents)->values();
//                     } else {
//                         // Adjust to ensure femaleCount is not equal to maleCount
//                         $femaleStudents = $femaleStudents->take($femaleCount + 1);
//                         $companyStudents = $maleStudents->merge($femaleStudents)->values();
//                     }
//                 }
//             } else {
//                 // Prioritize females during the day and males at night but allow both if necessary
//                 if ($startAt === '06:00' || $startAt === '12:00') {
//                     $preferredStudents = $companyStudents->where('gender', 'F');
//                 } else {
//                     $preferredStudents = $companyStudents->where('gender', 'M');
//                 }

//                 // Prioritize muislims who fasted ... during morning and mid night
//                 // if ($startAt === '06:00' || $startAt === '00:00') {
//                 //     $preferredStudents = $companyStudents->where('fast_status', 1);
//                 // } else {
//                 //     $preferredStudents = $companyStudents->where('fast_status', 0);
//                 // }

//                 if ($preferredStudents->isNotEmpty()) {
//                     $companyStudents = $preferredStudents->values();
//                 }
//             }

//             // Sort students by beat_round (ascending) and id (ascending)
//             $companyStudents = $companyStudents->sort(function ($a, $b) {
//                 if ($a->beat_round == $b->beat_round) {
//                     return $a->id <=> $b->id;
//                 }
//                 return $a->beat_round <=> $b->beat_round;
//             })->values();

//             // Group students by platoon
//             $studentsByPlatoonInGroup = $companyStudents->groupBy('platoon');
//             $platoonsInGroup = $currentGroup->toArray();
//             $numPlatoons = count($platoonsInGroup);

//             if ($numPlatoons > 0 && $requiredStudents > 0) {
//                 // Calculate students per platoon
//                 $studentsPerPlatoon = intdiv($requiredStudents, $numPlatoons);
//                 $remainingStudents = $requiredStudents % $numPlatoons;

//                 // dd($platoonsInGroup);
//                 // Shuffle platoons for fair distribution of remaining students
//                 // shuffle($platoonsInGroup);

//                 // foreach ($platoonsInGroup as $platoon) {
//                 //     $studentsNeeded = $studentsPerPlatoon;
//                 //     if ($remainingStudents > 0) {
//                 //         $studentsNeeded += 1;
//                 //         $remainingStudents -= 1;
//                 //     }

//                 //     $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? collect();
//                 //     $platoonStudents = $platoonStudents->whereNotIn('id', $usedStudentIds)->values();
//                 //     $availableStudents = $platoonStudents->count();

//                 //     // Assign as many students as possible, up to the number needed
//                 //     $studentsToAssign = min($studentsNeeded, $availableStudents);
//                 //     $selectedStudents = $platoonStudents->take($studentsToAssign)->pluck('id')->toArray();

//                 //     $assignedStudentIds = array_merge($assignedStudentIds, $selectedStudents);
//                 //     $usedStudentIds = array_merge($usedStudentIds, $selectedStudents);
//                 // }

//                 //Shuffle Problem Fixed
//                     // Shuffle platoons for fair distribution
//                     shuffle($platoonsInGroup);

//                     foreach ($platoonsInGroup as $platoon) {
//                         $studentsNeeded = $studentsPerPlatoon;

//                         if ($remainingStudents > 0) {
//                             $studentsNeeded++;
//                             $remainingStudents--;
//                         }

//                         $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? collect();

//                         // Remove already used students
//                         $platoonStudents = $platoonStudents
//                             ->whereNotIn('id', $usedStudentIds)
//                             ->shuffle() // SHUFFLE STUDENTS TOO
//                             ->values();

//                         $availableStudents = $platoonStudents->count();

//                         $studentsToAssign = min($studentsNeeded, $availableStudents);

//                         $selectedStudents = $platoonStudents
//                             ->take($studentsToAssign)
//                             ->pluck('id')
//                             ->toArray();

//                         $assignedStudentIds = array_merge($assignedStudentIds, $selectedStudents);
//                         $usedStudentIds     = array_merge($usedStudentIds, $selectedStudents);
//                     }
                      
//                       //  To Keep A system Memory of Last Assigned Students
//                     Student::whereIn('id', $assignedStudentIds)
//                         ->update(['last_assigned_at' => now()]);

//                 //End of Shuffle fixed 

//                 // Fill any remaining slots with available students
//                 $unfilledSpots = $requiredStudents - count($assignedStudentIds);
//                 if ($unfilledSpots > 0) {
//                     $remainingStudents = $companyStudents->whereNotIn('id', $usedStudentIds)->pluck('id')->toArray();
//                     $additionalStudents = array_slice($remainingStudents, 0, $unfilledSpots);
//                     $assignedStudentIds = array_merge($assignedStudentIds, $additionalStudents);
//                     $usedStudentIds = array_merge($usedStudentIds, $additionalStudents);
//                 }

//                 // Increment beat_round for assigned students
//                 Student::whereIn('id', $assignedStudentIds)->increment('beat_round');
//                 // Student::whereIn('id', $assignedStudentIds)->update(['beat_status' => 14]);
//             }

//             if (! empty($assignedStudentIds)) {
//                 $beats[] = [
//                     'beatType_id' => ($beatType === 'guards') ? 1 : 2,
//                     'guardArea_id' => ($beatType === 'guards') ? $area->id : null,
//                     'patrolArea_id' => ($beatType === 'patrols') ? $area->id : null,
//                     'student_ids' => json_encode($assignedStudentIds),
//                     'date' => $date,
//                     'start_at' => $startAt,
//                     'end_at' => $endAt,
//                     'status' => true,
//                     'created_at' => Carbon::now(),
//                     'updated_at' => Carbon::now(),
//                 ];
//             }
//         }

//         return $beats;
//     }

//TESTING TO REDUC OVERLOADING

public function generateBeats($areas, $studentsByCompany, $studentsByPlatoon, $beatType, $date, &$usedStudentIds)
{
    ini_set('max_execution_time', 300);
    set_time_limit(300);

    $beats = [];
    $currentGroup = collect([10, 11, 12, 13, 14, 15, 16, 17, 18]);

    foreach ($areas as $areaData) {
        $area = $areaData['area'];
        $startAt = $areaData['start_at'];
        $endAt = $areaData['end_at'];
        $company_id = $area->company_id;
        $requiredStudents = $area->number_of_guards;

        $assignedStudentIds = [];

        // -------------------------------
        // OPTIMIZATION: Fetch eligible students ONCE per area
        // Previously filtered multiple times per platoon
        $companyStudents = $studentsByCompany[$company_id] ?? collect();
        $companyStudents = $companyStudents
            ->whereIn('platoon', $currentGroup)
            ->whereNotIn('id', $usedStudentIds)
            ->sortBy(fn($s) => $s->last_assigned_at ?? now()->subYears(10))
            ->values();
        // -------------------------------

        // Gender & exceptions logic remains unchanged
        if (!empty($area->beat_exception_ids)) {
            $exceptions = json_decode($area->beat_exception_ids, true);
            if (in_array(1, $exceptions)) $companyStudents = $companyStudents->where('gender', 'F')->values();
            if (in_array(2, $exceptions)) $companyStudents = $companyStudents->where('gender', 'M')->values();
        } else {
            // Daytime prefers females, night prefers males
            $preferredGender = ($startAt === '06:00' || $startAt === '12:00') ? 'F' : 'M';
            $preferredStudents = $companyStudents->where('gender', $preferredGender);
            if ($preferredStudents->isNotEmpty()) $companyStudents = $preferredStudents->values();
        }

        // Sort by beat_round and id
        $companyStudents = $companyStudents->sortBy(['beat_round', 'id'])->values();

        // -------------------------------
        // OPTIMIZATION: convert to array for faster inner loops
        // Group students by platoon once
        $studentsByPlatoonInGroup = $companyStudents->groupBy('platoon')->toArray();
        // Shuffle platoons once per area
        $platoonsInGroup = $currentGroup->shuffle()->toArray();
        $numPlatoons = count($platoonsInGroup);
        // -------------------------------

        if ($numPlatoons > 0 && $requiredStudents > 0) {
    $studentsPerPlatoon = intdiv($requiredStudents, $numPlatoons);
    $remainingStudents = $requiredStudents % $numPlatoons;

    foreach ($platoonsInGroup as $platoon) {
        $studentsNeeded = $studentsPerPlatoon;
        if ($remainingStudents > 0) {
            $studentsNeeded++;
            $remainingStudents--;
        }

        // -------------------------------
        // OPTIMIZATION: remove used students in memory
        $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? [];
        $platoonStudents = collect($platoonStudents)->pluck('id')->toArray(); // <-- PLUCK IDs
        $platoonStudents = array_values(array_diff($platoonStudents, $usedStudentIds));

        $selectedStudents = array_slice($platoonStudents, 0, $studentsNeeded);

        $assignedStudentIds = array_merge($assignedStudentIds, $selectedStudents);
        $usedStudentIds = array_merge($usedStudentIds, $selectedStudents);
        // -------------------------------
    }

    // Fill remaining slots if not enough students
    if (count($assignedStudentIds) < $requiredStudents) {
        $remaining = array_diff($companyStudents->pluck('id')->toArray(), $usedStudentIds);
        $additional = array_slice($remaining, 0, $requiredStudents - count($assignedStudentIds));
        $assignedStudentIds = array_merge($assignedStudentIds, $additional);
        $usedStudentIds = array_merge($usedStudentIds, $additional);
    }


            // -------------------------------
            // OPTIMIZATION: BULK UPDATE database ONCE per area
            // Previously updated inside platoon loops multiple times
            if (!empty($assignedStudentIds)) {
                Student::whereIn('id', $assignedStudentIds)
                    ->update([
                        'last_assigned_at' => now(),
                        'beat_round' => DB::raw('beat_round + 1'),
                    ]);
            }
            // -------------------------------
        }

        if (!empty($assignedStudentIds)) {
            $beats[] = [
                'beatType_id' => ($beatType === 'guards') ? 1 : 2,
                'guardArea_id' => ($beatType === 'guards') ? $area->id : null,
                'patrolArea_id' => ($beatType === 'patrols') ? $area->id : null,
                'student_ids' => json_encode($assignedStudentIds),
                'date' => $date,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    return $beats;
}

//TESTING TO REDUC OVERLOADING ENDS HERE 

//     // End of Generate Beats Controller Function

    public function fillBeats(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        if (Beat::where('date', $date)->exists()) {
            return redirect()->back()->with('info', 'Beats already generated for '.$date);
            // return response()->json(['message' => 'Beats already generated for ' . $date], 200);
        }

        // Fetch active students (only those eligible for beats)
        $activeStudents = Student::where('beat_status', 1)
            ->where('session_programme_id', 10)
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
            // $leadersOnDuty = array_merge($leadersOnDuty, $this->assignLeadersOnDuty($companyId, $date, $usedStudentIds));
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

        return redirect()->back()->with('success', 'Beats generated successfully for '.$date);
        // return response()->json(['message' => 'Beats generated successfully for ' . $date], 200);
    }

    public function filterAreasByTimeExceptions($areas)
    {
        $filteredAreas = [];
        $timeRanges = [
            1 => ['start' => '06:00', 'end' => '12:00'],
            2 => ['start' => '12:00', 'end' => '18:00'],
            3 => ['start' => '18:00', 'end' => '00:00'],
            4 => ['start' => '00:00', 'end' => '06:00'],
        ];

        foreach ($areas as $area) {
            $exceptions = json_decode($area->beat_time_exception_ids);

            if (empty($exceptions)) {
                // No time exceptions, area is guarded 24hrs
                foreach ($timeRanges as $range) {
                    $filteredAreas[] = [
                        'area' => $area,
                        'start_at' => $range['start'],
                        'end_at' => $range['end'],
                    ];
                }
            } else {
                // Area has time exceptions, filter based on exceptions
                foreach ($exceptions as $exception) {
                    if (isset($timeRanges[$exception])) {
                        $filteredAreas[] = [
                            'area' => $area,
                            'start_at' => $timeRanges[$exception]['start'],
                            'end_at' => $timeRanges[$exception]['end'],
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
    // public function assignLeadersOnDuty($companyId, $date, &$usedStudentIds)
    // {
    //     // Check if leaders on duty have already been assigned for the given date
    //     // $existingLeadersOnDuty = BeatLeaderOnDuty::where('beat_date', $date)
    //     //     ->where('company_id', $companyId)
    //     //     ->exists();

    //       $existingLeadersOnDuty = BeatLeaderOnDuty::where('beat_date', $date)
    //             ->where('company_id', $companyId)
    //                 ->count();

    //             if ($existingLeadersOnDuty >= 2) {
    //                 return [];
    //             }


    //     // Fetch already assigned student IDs for this company (Guard, Patrol, Reserve)
    //     $assignedStudentIds = Beat::where('date', $date)
    //         ->whereHas('guardArea', function ($query) use ($companyId) {
    //             $query->where('company_id', $companyId);
    //         })
    //         ->orWhereHas('patrolArea', function ($query) use ($companyId) {
    //             $query->where('company_id', $companyId);
    //         })
    //         ->pluck('student_ids')
    //         ->map(fn ($ids) => json_decode($ids, true))
    //         ->flatten()
    //         ->toArray();

    //     $assignedReserveIds = BeatReserve::where('beat_date', $date)
    //         ->where('company_id', $companyId)
    //         ->pluck('student_id')
    //         ->toArray();

    //     $alreadyAssigned = array_merge($assignedStudentIds, $assignedReserveIds, $usedStudentIds);

    //     // Fetch eligible students
    //     $eligibleStudents = Student::where('beat_status', 3)
    //         ->where('company_id', $companyId)
    //         ->where('session_programme_id', 10)
    //         ->whereNotIn('id', $alreadyAssigned) // Avoid duplication
    //         ->orderBy('beat_leader_round', 'asc')
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     if ($eligibleStudents->isEmpty()) {
    //         return []; // Ensure an array is returned
    //     }

    //     // Select one male and one female leader
    //     $maleLeader = $eligibleStudents->where('gender', 'M')->first();
    //     $femaleLeader = $eligibleStudents->where('gender', 'F')->first();

    //     $leaders = collect([$maleLeader, $femaleLeader])->filter()->map(function ($leader) use ($companyId, $date, &$usedStudentIds) {
    //         // Mark leader as used
    //         $usedStudentIds[] = $leader->id;

    //         return [
    //             'student_id' => $leader->id,
    //             'company_id' => $companyId,
    //             'beat_date' => $date,
    //         ];
    //     })->toArray();

    //     // Update beat_leader_round count for selected leaders
    //     foreach ($leaders as $leader) {
    //         Student::where('id', $leader['student_id'])->increment('beat_leader_round');
    //     }

    //     return $leaders; // Always return an array
    // }

//Assign Leaders on Duty Modified
public function assignLeadersOnDuty($companyId, $date)
{
    DB::transaction(function () use ($companyId, $date) {

        // Count already assigned leaders
        $existingCount = BeatLeaderOnDuty::where('company_id', $companyId)
            ->where('beat_date', $date)
            ->count();

        if ($existingCount >= 2) {
            return;
        }

        $leadersNeeded = 2 - $existingCount;

        // Collect already assigned students (Guard, Patrol, Reserve, Leaders)
        $assignedStudentIds = Beat::where('date', $date)
            ->where(function ($q) use ($companyId) {
                $q->whereHas('guardArea', fn ($q) => $q->where('company_id', $companyId))
                  ->orWhereHas('patrolArea', fn ($q) => $q->where('company_id', $companyId));
            })
            ->pluck('student_ids')
            ->map(fn ($ids) => json_decode($ids, true))
            ->flatten()
            ->toArray();

        $reserveIds = BeatReserve::where('company_id', $companyId)
            ->where('beat_date', $date)
            ->pluck('student_id')
            ->toArray();

        $leaderIds = BeatLeaderOnDuty::where('company_id', $companyId)
            ->where('beat_date', $date)
            ->pluck('student_id')
            ->toArray();

        $excludeIds = array_unique(array_merge(
            $assignedStudentIds,
            $reserveIds,
            $leaderIds
        ));

        // Get eligible students
        $students = Student::where('beat_status', 3)
            ->where('company_id', $companyId)
            ->where('session_programme_id', 10)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('beat_leader_round')
            ->orderBy('id')
            ->get();

        if ($students->isEmpty()) {
            return;
        }

        $leaders = collect();

        // Prefer 1 male + 1 female
        if ($leadersNeeded > 1) {
            $male   = $students->firstWhere('gender', 'M');
            $female = $students->firstWhere('gender', 'F');

            if ($male)   $leaders->push($male);
            if ($female && $female->id !== optional($male)->id) {
                $leaders->push($female);
            }
        }

        // Fill remaining slots (any gender)
        if ($leaders->count() < $leadersNeeded) {
            $leaders = $leaders->merge(
                $students->whereNotIn('id', $leaders->pluck('id'))
                         ->take($leadersNeeded - $leaders->count())
            );
        }

        // Insert leaders
        foreach ($leaders as $student) {
            BeatLeaderOnDuty::create([
                'student_id' => $student->id,
                'company_id' => $companyId,
                'beat_date'  => $date,
            ]);

            Student::where('id', $student->id)->increment('beat_leader_round');
        }
    });
}



    /**
     * Function to Assign Reserves (6 Males, 4 Females, One Per Platoon)
     */
    public function assignReserves($companyId, $date, &$usedStudentIds)
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
            ->map(fn ($ids) => json_decode($ids, true))
            ->flatten()
            ->toArray();

        $assignedLeaderIds =  BeatLeaderOnDuty::where('beat_date', $date)
            ->where('company_id', $companyId)
            ->pluck('student_id')
            ->toArray();

        $alreadyAssigned = array_merge($assignedStudentIds, $assignedLeaderIds, $usedStudentIds);

        // Fetch eligible students (ordered by beat_round and id)
        $eligibleStudents = Student::where('beat_status', 1)
            ->where('company_id', $companyId)
            ->where('session_programme_id', 10)
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
        // $currentGroup = $groupB;
        // $groupBx = [1, 2, 3, 4, 5, 6, 7];

        // Convert array into a Laravel Collection
        // $currentGroup = collect($groupBx);

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
                'beat_date' => $date,
                'beat_round' => BeatRound::where('company_id', $companyId)->get()[0]->current_round ?? 0,
            ];
        })->toArray();

        // Update beat_status for selected reserves (set to 2)
        Student::whereIn('id', collect($reserves)->pluck('student_id'))->update(['beat_status' => 2]);

        return $reserves; // Always return an array
    }

    // Beat Report
    // public function showReport(Request $request)
    // {
    //     $companies = Company::all();
    //     $report = [];
    //     foreach ($companies as $company) {
    //         array_push($report, $this->beatHistory($company));
    //     }
    //     return view('beats.beat_report', ['report' => $report, 'companies' => $companies]);
    // }
    public function showReport(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 10; // Default session_programme_id if none selected
        }
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId); // Filter students by session
        })->get();
        $report = $companies->map(fn ($company) => $this->beatHistory($company));

        return view('beats.beat_report', compact('report', 'companies'));
    }

    public function downloadHistoryPdf($companyId)
    {
        $company = Company::find($companyId);
        $report = $this->beatHistory($company);
        // return view('beats.historyPdf', compact('report'));
        $pdf = Pdf::loadView('beats.historyPdf', compact('report'));

        return $pdf->download('history.pdf');
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

    private function beatHistory($company)
    {
        // $companies = Company::all();
        // $report = [];
        // foreach($companies as $company){
        $students = $company->students->where('session_programme_id', 10);
        $totalStudents = count($students);
        $totalEligibleStudents = count($students->whereIn('beat_status', [1, 2, 3]));
        $totalIneligibleStudents = count($students->whereNotIn('beat_status', [1, 2, 3]));
        // $eligibleStudentsPercent = round((($totalStudents - $totalIneligibleStudents) / $totalStudents) * 100, 2);
        $eligibleStudentsPercent = ($totalStudents > 0)
    ? round((($totalStudents - $totalIneligibleStudents) / $totalStudents) * 100, 2)
    : 0;
        // $InEligibleStudentsPercent = round((($totalIneligibleStudents) / $totalStudents) * 100, 2);
        $InEligibleStudentsPercent = ($totalStudents > 0)
    ? round(($totalIneligibleStudents / $totalStudents) * 100, 2)
    : 0; // Avoid division by zero
        // dd($InEligibleStudentsPercent);
        $guardAreas = count($company->guardAreas);
        $patrolAreas = count($company->patrolAreas);
        // dd($company->beatRound[0]->current_round);
        $current_round = optional($company->beatRound->first())->current_round;

        $attained_current_round = count($students->whereIn('beat_status', [1, 2, 3])->where('beat_round', $current_round)->values());
        $NotAttained_current_round = count($students->whereIn('beat_status', [1])->where('beat_round', '<', $current_round)->values());
        $exceededAttained_current_round = count($students->whereIn('beat_status', [1])->where('beat_round', '>', $current_round)->values());
        $fastingStudentCount = count($students->where('fast_status', 1)->values());

        $ICTStudents = $students->where('beat_exclusion_vitengo_id', 1)->values();
        $ujenziStudents = $students->where('beat_exclusion_vitengo_id', 2)->values();
        $hospitalStudents = $students->where('beat_exclusion_vitengo_id', 3)->values();
        $emergencyStudents = $students->whereNotNull('beat_emergency')->where('beat_status', 0)->values();
        $reserveStudents = BeatReserve::where('company_id', $company->id)->where('beat_round', $current_round)->get();

        // Fetch guard and patrol areas with proper time filters
        $_guardAreas = $this->filterAreasByTimeExceptions(GuardArea::all());
        $_patrolAreas = $this->filterAreasByTimeExceptions(PatrolArea::all());
        $number_of_guards = 0;
        foreach ($_guardAreas as $_guardArea) {
            if ($company->id == $_guardArea['area']['company_id']) {
                $number_of_guards += $_guardArea['area']['number_of_guards'];
            }
        }

        foreach ($_patrolAreas as $_patrolArea) {
            if ($company->id == $_patrolArea['area']['company_id']) {
                $number_of_guards += $_patrolArea['area']['number_of_guards'];
            }
        }

        if ($number_of_guards > 0) {
            $days_per_round = round(($totalEligibleStudents / $number_of_guards), 0);
        } else {
            // Handle the case where there are no guards
            $days_per_round = 0; // or some other default value or error
        }

        $company = [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'data' => [
                'totalStudents' => $totalStudents,
                'totalIneligibleStudents' => $totalIneligibleStudents,
                'totalEligibleStudents' => $totalEligibleStudents,
                'eligibleStudentsPercent' => $eligibleStudentsPercent,
                'InEligibleStudentsPercent' => $InEligibleStudentsPercent,
                'reserveStudents' => $reserveStudents,
                'guardAreas' => $guardAreas,
                'patrolAreas' => $patrolAreas,
                'current_round' => $current_round,
                'attained_current_round' => $attained_current_round,
                'NotAttained_current_round' => $NotAttained_current_round,
                'exceededAttained_current_round' => $exceededAttained_current_round,
                'fastingStudentCount' => $fastingStudentCount,
                'number_of_guards' => $number_of_guards,
                'days_per_round' => $days_per_round,
                'vitengo' => [
                    [
                        'name' => 'ICT',
                        'students' => $ICTStudents,
                    ],
                    [
                        'name' => 'UJENZI',
                        'students' => $ujenziStudents,
                    ],
                    [
                        'name' => 'HOSPITAL',
                        'students' => $hospitalStudents,
                    ],

                ],
                'emergencyStudents' => $emergencyStudents,
            ],
        ];

        // array_push($report, $company);
        // }
        return $company;
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

        $studentsQuery = DB::table('students')->where('session_programme_id', 10);

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
            ->where('session_programme_id', 10)
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
            ->where('session_programme_id', 10)
            ->select(
                'students.company_id',
                DB::raw('count(case when beat_round = beat_rounds.current_round then 1 end) as attained_current_round'),
                DB::raw('count(case when beat_round > beat_rounds.current_round then 1 end) as exceeded_current_round'),
                DB::raw('count(case when beat_round < beat_rounds.current_round then 1 end) as not_attained_current_round')
            )
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

    public function beatReserves($companyId, $date)
    {
        $reserves = BeatReserve::where('company_id', $companyId)->where('beat_date', $date)->get();
        $company = Company::find($companyId);

        return view('beats.reserves', compact('date', 'reserves', 'company'));
    }

    public function approveReserve($studentId)
    {
        $student = Student::find($studentId);
        $student->beat_status = 1;
        $student->save();

        return redirect()->back()->with('success', 'Reserve released successfully.');
    }

    public function beatReserveReplace(Request $request, $reserveId, $studentId, $date, $beatReserveId)
    {

        $request->validate([
            'replacement_reason' => 'required|string',
        ]);
        $student = Student::find($studentId);
        $reserve = Student::find($reserveId);
        $beat_reserve = BeatReserve::find($beatReserveId);
        $reserve->beat_status = 1;

        // $reserve->increment('beat_round');
        $reserve->update(['beat_status' => 10]);
        $reserve->increment('beat_round');

        // $reserve->update(['beat_status' => 10])->increment('beat_round');
        $beat_reserve->replaced_student_id = $student->id;
        // $beat_reserve->released = 1;
        $beat_reserve->replacement_reason = $request->replacement_reason;
        $student->decrement('beat_round');
        $beat_reserve->save();
        $reserve->save();
        $student->save();

        return redirect()->route('beats.reserves', ['companyId' => $reserve->company_id, 'date' => $date])->with('success', 'Reserve replaced successfully.');
    }

    public function beatReplacementStudent($studentId, $date, $beatReserveId)
    {

        $reserve = Student::find($studentId);
        $company = Company::find($reserve->company_id);

        $patrol_areas = $company->patrolAreas;
        $guard_areas = $company->guardAreas;
        $beat_students = [];
        foreach ($patrol_areas as $area) {
            $beats = $area->beats->where('date', $date);

            if (count($beats) > 0) {
                foreach ($beats as $beat) {
                    $beat_students = array_merge($beat_students, json_decode($beat->student_ids));
                }
            }
        }

        foreach ($guard_areas as $area) {
            $beats = $area->beats->where('date', $date);
            if (count($beats) > 0) {
                foreach ($beats as $beat) {
                    $beat_students = array_merge($beat_students, json_decode($beat->student_ids));
                }
            }
        }
        $students = Student::whereIn('id', $beat_students)->orderBy('first_name')->get();

        return view('beats.reserve_replacement', compact('reserve', 'students', 'company', 'date', 'beatReserveId'));
    }
}
