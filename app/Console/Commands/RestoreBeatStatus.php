<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Patient;

class RestoreBeatStatus extends Command
{
    protected $signature = 'students:restore-beat';
    protected $description = 'Restore student beat_status when rest days end';

    public function handle()
    {
        $today = now();

        $patients = Patient::where('status', 'sick')
            ->where('end_date', '<=', $today)
            ->whereHas('student', function ($query) {
                $query->where('beat_status', 0);
            })
            ->get();

        foreach ($patients as $patient) {
            $student = $patient->student;
            $student->beat_status = 1; // or previous stored value if tracked
            $student->save();

            $patient->status = 'recovered';
            $patient->save();

            $this->info("Restored beat_status for: " . $student->first_name);
        }
    }
}
