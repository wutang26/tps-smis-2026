<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\BeatType;
use App\Models\Company;
use App\Models\Area;
use App\Models\PatrolArea;
use App\Models\Student;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class BeatController extends Controller
{

    protected $round = 1;
    protected $start_at;
    protected $end_at;
    protected $area_id;

    protected $patrolArea_id;
    protected $beatType_id;
    protected $company_id;

    public function __construct()
    {

        $this->middleware('permission:beat-list|beat-create|beat-edit|beat-delete', ['only' => ['index', 'view']]);
        $this->middleware('permission:beat-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:beat-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:beat-delete', ['only' => ['destroy']]);

        $companyBeat = Beat::orderBy('beats.id', 'desc')
            ->leftJoin('students', 'students.id', 'beats.student_id')
            ->leftJoin('companies', 'companies.name', 'students.company')
            ->where('companies.id', $this->company_id)
            ->select('beats.*')
            ->get();
        if (count($companyBeat) > 1) {
            $this->round = $companyBeat[0]->round;
        } else {
            $this->round = 1;
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::all();
        $companies = Company::all();
        return view('beats.index', compact('areas', 'companies'));
    }

    public function list_guards($area_id)
    {
        $beats = Area::find($area_id)->beats()->whereDate('date', Carbon::today())->where('beatType_id', 1)->get();
        return view('beats.list_guards', compact('beats'));
    }

    public function list_patrol($patrolArea_id)
    {
        $patrolArea = PatrolArea::find($patrolArea_id);
        $beats = $patrolArea->beats()
            ->whereDate('date', Carbon::today())->where('beatType_id', 2)->get();
        return view('beats.list_patrol', compact('patrolArea', 'beats'));
    }

    public function list_patrol_guards($patrolArea_id)
    {
        $beatType = BeatType::find(2);

        $todayBeats = $beatType->beats()
            ->where('patrolArea_id', $patrolArea_id)
            ->whereDate('date', Carbon::today())
            ->get();
        $tomorowBeats = $beatType->beats()
            ->where('patrolArea_id', $patrolArea_id)
            ->whereDate('date', Carbon::tomorrow())
            ->get();
        return view('beats.show_patrol', compact('todayBeats', 'tomorowBeats'));

    }

    public function list_patrol_areas()
    {
        $patrol_areas = PatrolArea::all();
        return view('beats.list_patrol_areas', compact('patrol_areas'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($area_id)
    {
        $area = Area::find($area_id);
        if (!$area) {

        }
        $companies = Company::all();

        return view('beats.search_students', compact('area', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($session_programme_id, $area_id, $patrolArea_id, $beatType_id, $company_id, $start_at, $end_at)
    {
        // $area = Area::find($area_id);
        // $students_ids = $request->input('student_ids');
        // for ($i = 0; $i < count($students_ids); ++$i) {
        //     Beat::create([
        //         'area_id' => $area->id,
        //         'student_id' => $students_ids[$i],
        //         'assigned_by' => Auth::user()->id,
        //         'start_at' => $request->start_at,
        //         'end_at' => $request->end_at
        //     ]);

        // }
        // $area->is_assigned = true;
        // $area->save();
        // return $students_ids[0];

        $this->beatType_id = $beatType_id;
        $this->company_id = $company_id;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->area_id = $area_id;
        $this->patrolArea_id = $patrolArea_id;

        $platoon = 1;
        $company = Company::find($company_id);
        $students = $company->students()->orderBy('id')->get();
        //return $students;
        if ($beatType_id == 2) {
            $area = PatrolArea::find($patrolArea_id);
        } else {
            $area = Area::find($area_id);
        }

        if ($session_programme_id == 1)
            $number_of_students = $area->number_of_guards;
        else
            $number_of_students = 1;
        /**
         * Get Platoon students.
         */
        $beat = Beat::orderBy('id', 'desc');

        if (count($beat->get()) > 0) {
            $this->round = $beat->get()[0]->round;
            /**
             * Get students that do not appear on the beat table
             */
            $beat_students = $company->students()->leftJoin('beats', 'students.id', '=', 'beats.student_id')
                ->orderBy('platoon')
                ->where('session_programme_id', $session_programme_id)
                ->where('gender', 'M')
                ->where('students.vitengo_id', NULL)
                ->where('beats.student_id', NULL)
                ->select('students.*');
            if ($beat_students->get()->isNotEmpty()) {
                if (count($beat_students->get()) >= $number_of_students) {
                    return $this->store_beat($company, $area_id, $beatType_id, $beat_students->take($number_of_students)->get());
                } else {
                    $this->store_beat($company, $area_id, $beatType_id, $beat_students->get());
                    if ($platoon == 14) {
                        $platoon = 1;
                    } else {
                        ++$platoon;
                    }
                    $this->store_beat($company, $area_id, $beatType_id, $this->get_platoon_students($company, $platoon, $number_of_students - count($beat_students->get())));
                }
            } else {
                $not_attended = Beat::where('status', 0)->get();
                if ($not_attended->isNotEmpty()) {
                    $this->store_beat($company, $area_id, $beatType_id, $this->get_platoon_students($company, $platoon, $number_of_students - count($not_attended)));
                } else {
                    $lastBeat = Beat::orderBy('id', 'desc')->get()[0];
                    $index = $students->search(function ($student) use ($lastBeat) {
                        return $student->id == $lastBeat->student_id; // Compare directly with student_id
                    });
                    if (count($students->slice($index + 1)->values()) < $number_of_students) {
                        $extra = $number_of_students - count($students->slice($index + 1)->values());
                        $this->store_beat($company, $area_id, $beatType_id, $students->slice($index + 1)->values());
                        $this->store_beat($company, $area_id, $beatType_id, $students->slice(0, $extra)->values());
                    } else {
                        $this->store_beat($company, $area_id, $beatType_id, $students->slice($index + 1, $number_of_students)->values());
                    }
                    return $students->slice($index + 1, $number_of_students)->values();
                }
            }

        } else {

            $this->store_beat($company, $area_id, $beatType_id, $this->get_platoon_students($company, $platoon, $number_of_students));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show_guards_beats($area_id)
    {
        $beatType = BeatType::find(1);

        $todayBeats = $beatType->beats()
            ->where('area_id', $area_id)
            ->whereDate('date', Carbon::today())
            ->get();
        $tomorowBeats = $beatType->beats()
            ->where('area_id', $area_id)
            ->whereDate('date', Carbon::tomorrow())
            ->get();
        return view('beats.show', compact('todayBeats', 'tomorowBeats'));
    }

    public function show_patrol_beats($beatType_id)
    {
        $beatType = BeatType::find($beatType_id);

        $todayBeats = $beatType->beats()
            ->whereDate('date', Carbon::today())
            ->get();
        $tomorowBeats = $beatType->beats()
            ->whereDate('date', Carbon::tomorrow())
            ->get();
        return view('beats.show_patrol', compact('todayBeats', 'tomorowBeats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beat $beat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beat $beat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beat $beat)
    {
        //
    }

    public function search(Request $request, $area_id)
    {
        $area = Area::find($area_id);
        $validatedData = $request->validate([
            'company' => 'required',
            'platoon' => 'required',
        ]);
        $companies = Company::all();
        $students = Student::where('company', $request->company)->where('platoon', $request->platoon)->get();
        //if($students && $request->name){
        // $students = $students->where('last_name','LIKE', "%{$request->name}%")
        // ->orWhere('first_name','LIKE', "%{$request->name}%")->get();
        // }
        return view('beats.search_students', compact('area', 'companies', 'students'));
    }

    public function update_area(Request $request, $area_id)
    {
        $area = Area::findOrFail($area_id);
        $request->validate([
            'name' => 'required',
            'company' => 'required',
            'number_of_guards' => 'required'
        ]);

        $area->update([
            'name' => $request->name,
            'company_id' => $request->company,
            'number_of_guards' => $request->number_of_guards
        ]);

        return redirect()->back()->with('success', 'Area updated Successfully.');

    }

    public function store_beat($company, $area_id, $beatType_id, $students)
    {
        $last_student = $company->students()->orderBy('platoon')->orderBy('id', 'desc')->get()[0];
        /**
         *  Assign students to a beat
         */
        foreach ($students as $student) {
            $beat_student = Beat::where('student_id', $student->id)->where('round', $this->round)->get();
            if(count($beat_student)> 0){
                ++$this->round;
            }
            /**
             * Needs modifications to check if student is the last one
             */

            // if ($last_student->id == $student->id) {
            //     $this->round += 1;
            // }
             Beat::create([
                'beatType_id' => $beatType_id,
                'area_id' => $this->area_id,
                'patrolArea_id' => $this->patrolArea_id,
                'student_id' => $student->id,
                'round' => $this->round,
                'date' => Carbon::tomorrow()->format('d-m-Y'),
                'start_at' => Carbon::createFromTime($this->start_at, 00, 0)->format('H:i:s'),
                'end_at' => Carbon::createFromTime($this->end_at, 00, 0)->format('H:i:s')
            ]);
        }
    }

    public function get_platoon_students($company, $platoon, $count)
    {
        $platoon_students = new \Illuminate\Database\Eloquent\Collection();
        do {
            $students_to_push = $company->students()->where('gender', 'M')->where('platoon', $platoon)->take($count)->get();
            if ($students_to_push->isNotEmpty())
                foreach ($students_to_push as $student_to_push) {

                    $platoon_students->push($student_to_push);
                    --$count;
                }
            ++$platoon;
        } while (count($platoon_students) < $count);
        return $platoon_students;
    }

    public function approve_presence(Request $request)
    {
        $beat_ids = $request->input('beat_ids');
        if (count($beat_ids) < 1) {
            return redirect()->back()->with('error', 'Please select at least one guard.');
        }
        $todayBeats = Beat::whereDate('date', Carbon::today())->select('id')
            ->get();
        $todayBeats = $todayBeats->pluck('id')->toArray();
        $beatType = Beat::find($beat_ids[0])->beatType;
        $absent = array_values(array_diff($todayBeats, $beat_ids));
        //Update present
        foreach ($beat_ids as $beat_id) {
            $beat = Beat::find($beat_id);
            if ($beat) {
                $beat->update([
                    'status' => 1
                ]);
            }
        }

        //update absenties
        foreach ($absent as $beat_id) {
            $beat = Beat::find($beat_id);
            if ($beat) {
                $beat->update([
                    'status' => 0
                ]);
            }
        }
        if ($beatType->id == 2) {
            return redirect('/beats/show_patrol_areas')->with('success', "Successfully.");
        }
        return redirect('/beats')->with('success', "Successfully.");
    }

    public function companies($beatType_id){
        $companies = Company::all();
        return view('beats.companies', compact('companies','beatType_id'));
    }

    public function get_companies_area($company_id){
        $company = Company::find($company_id);
        $areas = $company->areas;
        $companies = Company::all();
        return view('beats.list_guards_areas', compact('areas', 'companies','company'));
    }

    public function get_companies_patrol_area($company_id){
        $company = Company::find($company_id);
        $patrol_areas = $company->patrol_areas;
        $companies = Company::all();
        return view('beats.list_patrol_areas', compact('patrol_areas', 'companies', 'company'));
    }

    public function generateTodayPdf($company_id,$beatType_id,$day)
    {
        if($beatType_id == "all"){
            $beatTypes = BeatType::all();
        }
        else{
            $beatTypes = BeatType::find($beatType_id);
        }
        $company = Company::find($company_id);
        //return view('beats.downloadPdf', compact('company','beatType_id', 'day'));
        $pdf = PDF::loadView('beats.downloadPdf',compact('company', 'beatType_id', 'day'));
        return $pdf->stream("beats.pdf");
        
    }
    public function test(){
        //$company = Company::find(1);
        return $this->store(1, 1, NULL, 1, 1, "18", "00");
    }
}
