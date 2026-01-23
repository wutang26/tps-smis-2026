<?php

namespace App\Services;

use App\Models\Attendence;
use App\Models\LeaveRequest;
use App\Models\MPS;
use App\Models\Patient;
use App\Models\SessionProgramme;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class GraphDataService
{
    private $selectedSessionId;

    public function getGraphData($start_date = null, $end_date = null)
    {
        $sessionId = session('selected_session') ?: 1;
        $today = Carbon::today();

        // --- Define start/end for daily, weekly, monthly ---
        $dailyStart = $start_date ? Carbon::parse($start_date)->startOfDay() : $today->copy()->subDays(6);
        $dailyEnd = $end_date ? Carbon::parse($end_date)->endOfDay() : $today->copy()->endOfDay();

        $weeklyStart = $start_date ? Carbon::parse($start_date)->startOfWeek() : $today->copy()->subWeeks(4)->startOfWeek();
        $weeklyEnd = $end_date ? Carbon::parse($end_date)->endOfWeek() : $today->copy()->endOfWeek();

        $monthlyStart = $start_date ? Carbon::parse($start_date)->startOfMonth() : $today->copy()->subMonths(2)->startOfMonth();
        $monthlyEnd = $end_date ? Carbon::parse($end_date)->endOfMonth() : $today->copy()->endOfMonth();

        // --- Generate blank data structures ---
        $dailyData = $this->generateEmptyData($dailyStart, $dailyEnd, 'day');
        $weeklyData = $this->generateEmptyData($weeklyStart, $weeklyEnd, 'week');
        $monthlyData = $this->generateEmptyData($monthlyStart, $monthlyEnd, 'month');

        // --- Fetch all attendances for the full range ---
        $minStart = min($dailyStart, $weeklyStart, $monthlyStart);
        $maxEnd = max($dailyEnd, $weeklyEnd, $monthlyEnd);

        $attendances = Attendence::where('session_programme_id', $sessionId)
            ->whereBetween('created_at', [$minStart, $maxEnd])
            ->get();     #rudisha 'date'

        // ---------------- DAILY LOOP ----------------
        foreach ($attendances as $attendance) {
            $date = Carbon::parse($attendance->date);
            $dayKey = $date->format('Y-m-d');

            if (isset($dailyData['keys'][$dayKey])) {
                $companyAttendance = $attendance->platoon->company?->company_attendance($date, 1);

                if ($companyAttendance && $companyAttendance->status != 'verified') {
                    continue; // Only skip if attendance exists AND is not verified
                }

                $i = $dailyData['keys'][$dayKey];
                $dailyData['absents'][$i] += (int) $attendance->absent;
                $dailyData['sick'][$i] = $this->getSickCount($date);
                $dailyData['lockUps'][$i] = $this->getLockUpCount($date);
                $dailyData['leaves'][$i] = $this->getLeaveCount($date);
            }
        }

        // ---------------- WEEKLY LOOP ----------------
        $groupedByWeek = $attendances->groupBy(function ($attendance) {
            return Carbon::parse($attendance->date)->startOfWeek()->format('Y-m-d');
        });

        foreach ($groupedByWeek as $weekKey => $records) {
            if (! isset($weeklyData['keys'][$weekKey])) {
                continue;
            }

            $i = $weeklyData['keys'][$weekKey];
            $weeklyData['absents'][$i] = $records->sum('absent');

            $weekEnd = Carbon::parse($weekKey)->endOfWeek();
            $weeklyData['sick'][$i] = $this->getSickCount($weekEnd);
            $weeklyData['lockUps'][$i] = $this->getLockUpCount($weekEnd);
            $weeklyData['leaves'][$i] = $this->getLeaveCount($weekEnd);
        }

        // ---------------- MONTHLY LOOP ----------------
        foreach ($monthlyData['labels'] as $i => $label) {
            $carbon = Carbon::createFromFormat('F Y', $label);
            $month = $carbon->month;
            $year = $carbon->year;

            $monthlyData['absents'][$i] = Attendence::where('session_programme_id', $sessionId)
                ->whereMonth('created_at', $month)   // rudisha 'date'
                ->whereYear('created_at', $year)   #rudish 'date'
                ->sum('absent');

            $monthlyData['sick'][$i] = $this->getSickCountForMonth($month, $year);
            $monthlyData['lockUps'][$i] = $this->getLockUpCountForMonth($month, $year);
            $monthlyData['leaves'][$i] = $this->getLeaveCountForMonth($month, $year);
        }

        // --- Clean up keys ---
        unset($dailyData['keys'], $weeklyData['keys']);

        // --- Return final structured output ---
        return [
            'dailyData' => $dailyData,
            'weeklyData' => $weeklyData,
            'monthlyData' => $monthlyData,
            'daily' => $dailyData['leaves'],
            'weekly' => $weeklyData['leaves'],
            'monthly' => $monthlyData['leaves'],
        ];
    }

    public function generateEmptyData($start, $end, $type = 'day')
    {
        $data = [
            'labels' => [],
            'keys' => [],
            'absents' => [],
            'sick' => [],
            'lockUps' => [],
            'leaves' => [],
        ];

        if ($type === 'day') {
            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $i => $date) {
                $key = $date->format('Y-m-d');
                $data['labels'][] = $key;
                $data['keys'][$key] = $i;
            }
        } elseif ($type === 'week') {
            $period = CarbonPeriod::create($start, '1 week', $end);
            foreach ($period as $i => $endOfWeek) {
                $key = $endOfWeek->format('Y-m-d');
                $data['labels'][] = 'Week '.$this->getWeekNumber($endOfWeek);
                $data['keys'][$key] = $i;
            }
        } elseif ($type === 'month') {
            $period = CarbonPeriod::create($start, '1 month', $end);
            foreach ($period as $i => $startOfMonth) {
                $key = $startOfMonth->format('F Y');
                $data['labels'][] = $key;
                $data['keys'][$key] = $i;
            }
        }

        // Fill values
        $count = count($data['labels']);
        $data['absents'] = array_fill(0, $count, 0);
        $data['sick'] = array_fill(0, $count, 0);
        $data['lockUps'] = array_fill(0, $count, 0);
        $data['leaves'] = array_fill(0, $count, 0);

        return $data;
    }

    private function getSickCount(Carbon $date): int
    {
        return Patient::whereDate('created_at', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where(function ($q) use ($date) {
                    $q->where('excuse_type_id', 1)
                        ->whereNotNull('rest_days')
                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [$date]);
                })->orWhere(function ($q) use ($date) {
                    $q->where('excuse_type_id', 3)
                        ->where(function ($s) use ($date) {
                            $s->whereNull('released_at')
                                ->orWhereDate('released_at', '>=', $date);
                        });
                });
            })->count();
    }

    private function getLockUpCount(Carbon $date): int
    {
        return MPS::whereDate('created_at', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('released_at')
                    ->orWhereDate('released_at', '>=', $date);
            })->count();
    }

    private function getLeaveCount(Carbon $date, array $companyIds = []): int
    {
        return LeaveRequest::whereNull('rejected_at') // Not rejected
            ->where(function ($query) use ($date) {
                $query->whereNull('return_date') // No return yet
                    ->orWhereDate('return_date', '>=', $date); // Returning in future
            })
            ->whereNotNull('approved_at') // Approved
            ->when(! empty($companyIds), function ($query) use ($companyIds) {
                $query->whereHas('student.platoonRelation', function ($q) use ($companyIds) {
                    $q->whereIn('company_id', $companyIds);
                });
            })
            ->count();
    }

    private function getSickCountForMonth($month, $year): int
    {
        return Patient::whereYear('created_at', $year)
            ->where(function ($query) use ($month) {
                $query->where(function ($q) use ($month) {
                    $q->where('excuse_type_id', 1)
                        ->whereNotNull('rest_days')
                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [$month]);
                })->orWhere(function ($q) use ($month) {
                    $q->where('excuse_type_id', 3)
                        ->where(function ($s) use ($month) {
                            $s->whereNull('released_at')
                                ->orWhereMonth('released_at', '>=', $month);
                        });
                });
            })->count();
    }

    private function getLockUpCountForMonth($month, $year): int
    {
        return MPS::whereYear('arrested_at', $year)
            ->where(function ($q) use ($month) {
                $q->whereNull('released_at')
                    ->orWhereMonth('released_at', '>=', $month);
            })->count();
    }

    private function getLeaveCountForMonth($month, $year): int
    {
        return LeaveRequest::whereYear('created_at', $year)
            ->where(function ($q) use ($month) {
                $q->whereNull('return_date')
                    ->orWhereMonth('return_date', '>=', $month);
            })->count();
    }

    private function getWeekNumber($date)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
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

    public function getWeeklyData(Collection $attendances, int $weeks = 5): array
    {
        $weekKeys = [];
        $weeklyData = [
            'absents' => [],
            'sick' => [],
            'lockUps' => [],
            'leaves' => [],
        ];

        // Initialize week keys and zeroed data arrays
        for ($i = 0; $i < $weeks; $i++) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i)->toDateString();
            $weekKeys[$startOfWeek] = $i;

            $weeklyData['absents'][$i] = 0;
            $weeklyData['sick'][$i] = 0;
            $weeklyData['lockUps'][$i] = 0;
            $weeklyData['leaves'][$i] = 0;
        }

        // Aggregate absents from attendances
        foreach ($attendances as $attendance) {
            $companyAttendance = $attendance->platoon->company?->company_attendance($attendance->date);

            if ($companyAttendance && $companyAttendance->status != 'verified') {
                continue; // Only skip if attendance exists AND is not verified
            }
            $attendanceWeek = Carbon::parse($attendance->date)->endOfWeek()->toDateString();
            if (isset($weekKeys[$attendanceWeek])) {
                $weekIndex = $weekKeys[$attendanceWeek];
                $weeklyData['absents'][$weekIndex] += (int) $attendance->absent;
            }
        }

        // Compute sick, lockUps, leaves for each week
        foreach ($weekKeys as $weekStart => $weekIndex) {
            $weekEnd = Carbon::parse($weekStart)->endOfWeek()->toDateString();

            // Sick
            $weeklyData['sick'][$weekIndex] = Patient::where(function ($query) use ($weekEnd) {
                $query->where(function ($subQuery) use ($weekEnd) {
                    $subQuery->where('excuse_type_id', 1)
                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [$weekEnd]);
                })->orWhere(function ($subQuery) use ($weekEnd) {
                    $subQuery->where('excuse_type_id', 3)
                        ->where(function ($q) use ($weekEnd) {
                            $q->whereNull('released_at')
                                ->orWhereDate('released_at', '>=', $weekEnd);
                        });
                });
            })->count();

            // LockUps
            $weeklyData['lockUps'][$weekIndex] = MPS::whereDate('created_at', '<=', $weekEnd)
                ->where(function ($query) use ($weekEnd) {
                    $query->whereNull('released_at')
                        ->orWhereDate('released_at', '>=', $weekEnd);
                })->count();

            // Leaves
            $weeklyData['leaves'][$weekIndex] = LeaveRequest::whereDate('created_at', '<=', $weekEnd)
                ->where(function ($query) use ($weekEnd) {
                    $query->whereNull('return_date')
                        ->orWhereDate('return_date', '>=', $weekEnd);
                })->count();
        }

        return $weeklyData;
    }
}
