<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Platoon extends Model
{
    protected $fillable = [
        'company_id',
        'name',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function attendences()
    {
        return $this->hasMany(Attendence::class, 'platoon_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'platoon', 'name');
    }

    public function platoon_students($companyId)
    {
        return $this->students()->where('company_id', $companyId)->get();
    }

    public function lockUp()
    {
        return $this->hasManyThrough(MPS::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }

    private function sick()
    {
        return $this->hasManyThrough(Patient::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }

    public function leaves()
    {
        return $this->hasManyThrough(LeaveRequest::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }

    public function today_attendence($attendanceType_id = null, $date = null)
    {
        $selectedSessionId = session('selected_session', 1); // fallback to 1
        if (! $date) {
            $date = \Carbon\Carbon::today();
        }
        $query = $this->attendences()
            ->where('session_programme_id', $selectedSessionId)
            ->whereDate('date', \Carbon\Carbon::parse($date));

        if ($attendanceType_id) {
            $query->where('attendenceType_id', $attendanceType_id);
        }

        return $query->get(); // return actual data, not query builder
    }

    public function today_sick()
    {
        return $this->sick();
    }

    public function today_admitted($date = null)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        $_date = $date == null ? Carbon::today() : Carbon::parse($date)->format('Y-m-d');

        return $this->sick()
            ->where('session_programme_id', $selectedSessionId)
            ->where('excuse_type_id', 3) // Admitted
            ->where(function ($query) use ($_date) {
                $query->whereNull('released_at')
                    ->orWhereDate('released_at', '>=', $_date);
            });
    }

    public function todayEd($date = null)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        $_date = $date == null ? Carbon::today() : Carbon::parse($date)->format('Y-m-d');

        return $this->sick()
            ->where('session_programme_id', $selectedSessionId)
            ->where('excuse_type_id', 1) // ED
            ->where(function ($query) use ($_date) {
                $query->whereNull('released_at')
                    ->orWhereDate('released_at', '<=', $_date);
            });
        // ->whereRaw("DATE_ADD(patients.created_at, INTERVAL rest_days DAY) >= ?", [$today]);
    }
}
