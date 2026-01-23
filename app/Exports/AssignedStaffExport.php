<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AssignedStaffExport implements FromView
{
    protected $task;
    protected $regionMap;
    protected $selectedRegion;

    public function __construct($task, $regionMap, $selectedRegion)
    {
        $this->task = $task;
        $this->regionMap = $regionMap;
        $this->selectedRegion = $selectedRegion;
    }

    public function view(): View
    {
        $filteredStaff = $this->selectedRegion
            ? $this->task->staff->filter(fn($member) => $member->pivot->region_id == $this->selectedRegion)
            : collect();

        $grouped = $filteredStaff->groupBy(fn($member) => $this->regionMap[$member->pivot->region_id] ?? 'Unknown Region');

        return view('exports.assigned_staff', [
            'task' => $this->task,
            'regionMap' => $this->regionMap,
            'grouped' => $grouped,
            'selectedRegion' => $this->selectedRegion,
        ]);
    }
}
