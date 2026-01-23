<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
namespace App\Http\Controllers;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF library
use App\Models\Activity;
use App\Models\Venue;
use App\Models\Instructor;

class TimetableController extends Controller
{

    public function index(Request $request)
{
    $selectedCompany = $request->input('company', '');
    $companies = ['HQ', 'A', 'B', 'C'];
    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    // ✅ Ensure time slots are fetched from DB and sorted
    $timeSlots = Timetable::distinct()
        ->orderByRaw("STR_TO_DATE(time_slot, '%h:%i %p')")
        ->pluck('time_slot')
        ->toArray();

    // Fetch timetable from DB
    $query = Timetable::query();
    if (!empty($selectedCompany)) {
        $query->where('company', $selectedCompany);
    }

    $timetableEntries = $query->orderByRaw("STR_TO_DATE(time_slot, '%h:%i %p')")->get()->map(function ($entry) {
        if ($entry->activity == 'Drill') {
            $entry->venue = 'Uwanja wa Damu';
        }
        return $entry;
    });

    // Define predefined breaks and fatigue times
    $predefinedEntries = collect([
        ['day' => 'Monday', 'time_slot' => '10:00 AM - 11:00 AM', 'activity' => 'Tea Break', 'venue' => 'Mess',  'company' => 'ALL'],
        ['day' => 'Monday', 'time_slot' => '1:00 PM - 2:00 PM', 'activity' => 'Lunch Break', 'venue' => 'Mess', 'company' => 'ALL'],
        ['day' => 'Monday', 'time_slot' => '4:00 PM - 6:00 PM', 'activity' => 'Fatique', 'venue' => 'Small Square', 'instructor' => 'N/A', 'company' => 'ALL'],
        ['day' => 'Tuesday', 'time_slot' => '10:00 AM - 11:00 AM', 'activity' => 'Tea Break', 'venue' => 'Mess', 'company' => 'ALL'],
        ['day' => 'Tuesday', 'time_slot' => '1:00 PM - 2:00 PM', 'activity' => 'Lunch Break', 'venue' => 'Mess', 'company' => 'ALL'],
        ['day' => 'Tuesday', 'time_slot' => '4:00 PM - 6:00 PM', 'activity' => 'Fatique', 'venue' => 'Small Square', 'instructor' => 'N/A', 'company' => 'ALL'],
    ]);

    // Convert predefined entries to Eloquent-style models
    $predefinedEntries = $predefinedEntries->map(fn($entry) => new Timetable($entry));

    // Merge and sort all timetable entries
    $timetable = $timetableEntries->merge($predefinedEntries)->sortBy(fn($entry) => strtotime(explode(' - ', $entry->time_slot)[0]));

    return view('timetable.index', compact('timetable', 'daysOfWeek', 'timeSlots', 'selectedCompany', 'companies'));
}

    


    public function edit(Timetable $timetable)
    {
        return view('timetable.edit', compact('timetable'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'company' => 'required|in:HQ,A,B,C',
            'day' => 'required',
            'time_slot' => 'required',
            'activity' => 'required',
        ]);

        $timetable->update($request->all());

        return redirect()->route('timetable.index')->with('success', 'Timetable updated successfully!');
    }





public function create()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('timetable.index')->with('error', 'Unauthorized Access.');
    }
    $companies = ['HQ', 'A', 'B', 'C'];
    $venues = Venue::all();
    $activities = Activity::all();
    $instructors = Instructor::all();

    return view('timetable.create', compact('companies', 'venues', 'activities', 'instructors'));
}



    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()->route('timetable.index')->with('success', 'Timetable entry deleted!');
    }

    public function exportPDF(Request $request)
    {
        $selectedCompany = $request->input('company', '');
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = [
            '08:00 AM - 10:00 AM',
            '10:00 AM - 11:00 AM',  // Tea Break
            '11:00 AM - 1:00 PM',
            '1:00 PM - 2:00 PM',    // Lunch Break
            '2:00 PM - 4:00 PM',
            '4:00 PM - 6:00 PM'     // Fatigue
        ];
    
        // Fetch the timetable exactly as displayed on the webpage
        $query = Timetable::query();
        if (!empty($selectedCompany)) {
            $query->where('company', $selectedCompany);
        }
        $timetables = $query->orderByRaw("STR_TO_DATE(time_slot, '%h:%i %p')")->get();
    
        // Initialize structured timetable with correct mapping
        $structuredTimetable = [];
        foreach ($timeSlots as $slot) {
            foreach ($daysOfWeek as $day) {
                $structuredTimetable[$slot][$day] = null; // Ensure all time slots exist
            }
        }
    
        // Populate structuredTimetable with actual data
        foreach ($timetables as $entry) {
            $structuredTimetable[$entry->time_slot][$entry->day] = $entry;
        }
    
        // Ensure predefined activities (Tea Break, Lunch, Fatigue) are present
        $predefinedEntries = [
            '10:00 AM - 11:00 AM' => ['activity' => 'Tea Break', 'venue' => 'Mess'],
            '1:00 PM - 2:00 PM'   => ['activity' => 'Lunch Break', 'venue' => 'Mess'],
            '4:00 PM - 6:00 PM'   => ['activity' => 'Fatique', 'venue' => 'Small Square', 'instructor' => 'N/A']
        ];
    
        foreach ($predefinedEntries as $slot => $details) {
            foreach ($daysOfWeek as $day) {
                if (!isset($structuredTimetable[$slot][$day])) {
                    $structuredTimetable[$slot][$day] = (object) $details;
                }
            }
        }
    
        // Generate PDF in landscape mode
        $pdf = Pdf::loadView('timetable.pdf', compact('structuredTimetable', 'daysOfWeek', 'timeSlots', 'selectedCompany'))
                  ->setPaper('a4', 'landscape'); // Landscape format
    
        return $pdf->download("timetable_{$selectedCompany}.pdf");
    }
    

    public function show($id)
    {
        $timetable = Timetable::findOrFail($id); // Fetch timetable by ID
        return view('timetable.show', compact('timetable'));
    }

    public function generateTimetable()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('timetable.index')->with('error', 'Unauthorized Access.');
    }

    $companies = ['HQ', 'A', 'B', 'C'];
    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    // Define structured time slots
    $timeSlots = [
        '08:00 AM - 10:00 AM', // First session
        '10:00 AM - 11:00 AM', // Tea Break
        '11:00 AM - 1:00 PM',  // Second session
        '1:00 PM - 2:00 PM',   // Lunch Break
        '2:00 PM - 4:00 PM',   // Third session
        '4:00 PM - 6:00 PM'    // Fatigue
    ];

    // Fetch all activities, venues, and instructors
    $allActivities = Activity::pluck('name')->shuffle()->toArray(); // Shuffle for variety
    $venues = Venue::pluck('name')->toArray();
    $instructors = Instructor::pluck('name')->toArray();

    if (empty($allActivities) || empty($venues) || empty($instructors)) {
        return redirect()->route('timetable.index')->with('error', 'Please add activities, venues, and instructors before generating the timetable.');
    }

    // Clear existing timetable
    Timetable::truncate();

    // Dictionary to track assigned activities per time slot
    $assignedActivities = [];

    foreach ($daysOfWeek as $day) {
        foreach ($timeSlots as $slot) {
            $assignedActivities[$day][$slot] = []; // Initialize empty activity tracking
        }
    }

    foreach ($companies as $company) {
        $activityIndex = 0;
        $remainingActivities = [];

        foreach ($daysOfWeek as $day) {
            $activityCount = 0;

            foreach ($timeSlots as $slot) {
                $activity = '';
                $venue = '';
                $instructor = '';

                // Predefined breaks (same for all companies)
                if ($slot === '10:00 AM - 11:00 AM') {
                    $activity = 'Tea Break';
                    $venue = 'Mess';
                   
                } elseif ($slot === '1:00 PM - 2:00 PM') {
                    $activity = 'Lunch Break';
                    $venue = 'Mess';
                    
                } elseif ($slot === '4:00 PM - 6:00 PM') {
                    $activity = 'Fatique';
                    $venue = 'Small Square';
                    $instructor = 'Sir Major';
                } else {
                    // Ensure each company has at least 3 activities per day
                    if (!empty($remainingActivities) && $activityCount < 3) {
                        $activity = array_shift($remainingActivities);
                    } elseif ($activityCount < 3) {
                        // Find a unique activity for this time slot
                        do {
                            if ($activityIndex >= count($allActivities)) {
                                shuffle($allActivities); // Reshuffle if we run out of activities
                                $activityIndex = 0;
                            }
                            $activity = $allActivities[$activityIndex];
                            $activityIndex++;
                        } while (in_array($activity, $assignedActivities[$day][$slot])); // Prevent duplicate activity

                        // Mark this activity as assigned in this time slot
                        $assignedActivities[$day][$slot][] = $activity;
                    } else {
                        // Store extra activity to move to the next day
                        $remainingActivities[] = $allActivities[$activityIndex];
                        $activityIndex++;
                        continue;
                    }

                    $venue = $venues[array_rand($venues)];
                    $instructor = $instructors[array_rand($instructors)];

                    // Ensure "Drill" is always in "Uwanja wa Damu"
                    if ($activity === 'Drill') {
                        $venue = 'Uwanja wa Damu';
                    }

                    $activityCount++;
                }

                Timetable::updateOrCreate(
                    [
                        'company' => $company,
                        'day' => $day,
                        'time_slot' => $slot
                    ],
                    [
                        'activity' => $activity,
                        'venue' => $venue,
                        'instructor' => $instructor
                    ]
                );
            }
        }
    }

    return redirect()->route('timetable.index')->with('success', 'Unique timetables generated successfully.');
}

public function store(Request $request)
{
    $request->validate([
        'company' => 'required|in:HQ,A,B,C',
        'day' => 'required',
        'time_slot' => 'required',
        'activity' => 'nullable|string',
        'new_activity' => 'nullable|string',
        'venue' => 'nullable|string',
        'new_venue' => 'nullable|string',
        'instructor' => 'nullable|string',
        'new_instructor' => 'nullable|string',
    ]);

    // Check if a new activity was entered
    if (!empty($request->new_activity)) {
        $activity = Activity::firstOrCreate(['name' => $request->new_activity]);
        $activityName = $activity->name;
    } else {
        $activityName = $request->activity;
    }

    // Check if a new venue was entered
    if (!empty($request->new_venue)) {
        $venue = Venue::firstOrCreate(['name' => $request->new_venue]);
        $venueName = $venue->name;
    } else {
        $venueName = $request->venue;
    }

    // Remove instructor for Tea Break & Lunch Break
    if ($request->time_slot === '10:00 AM - 11:00 AM' || $request->time_slot === '1:00 PM - 2:00 PM') {
        $instructorName = 'N/A';
    } else {
        // Check if a new instructor was entered
        if (!empty($request->new_instructor)) {
            $instructor = Instructor::firstOrCreate(['name' => $request->new_instructor]);
            $instructorName = $instructor->name;
        } else {
            $instructorName = $request->instructor;
        }
    }

    // Store new timetable entry
    Timetable::create([
        'company' => $request->company,
        'day' => $request->day,
        'time_slot' => $request->time_slot,
        'activity' => $activityName,
        'venue' => $venueName,
        'instructor' => $instructorName,
    ]);

    return redirect()->route('timetable.index')->with('success', 'Timetable entry created successfully.');
}

    
}
