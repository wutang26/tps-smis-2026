@extends('layouts.main')

@section('content')

<style>
/* Container */
.container-page {
    max-width: 1200px;
    margin: auto;
    padding: 15px;
}

/* Header row */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* Selected date */
.selected-date {
    font-weight: bold;
    color: #1f2937;
}

/* Back button */
.next-btn {
    padding: 6px 14px;
    border-radius: 4px;
    background-color: #10b981;
    color: white;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.next-btn:hover {
    background-color: #059669;
}

/* Section title */
h3 {
    color: blue;
    margin-top: 30px;
    margin-bottom: 10px;
}

/* Table */
table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 30px;
    background-color: #fff;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f4f4f4;
    font-weight: 600;
}

/* Row colors */
.skipped { background-color: #ffe0e0; }
.eligible-tomorrow { background-color: #e0ffe0; }

/* Status labels */
.label {
    padding: 3px 6px;
    border-radius: 4px;
    color: #fff;
    font-weight: bold;
}
.selected-date {
    display: flex;
    align-items: center;
}

.date {
    margin-left: 60px; /* adjust spacing */
}

.label-eligible { background-color: #4CAF50; }
.label-skipped { background-color: #F44336; }
.label-safari { background-color: #FF9800; }
.label-kitengo { background-color: #9C27B0; }
</style>

<div class="container-page">

    <!-- Header -->
            <div class="page-header">
            <div class="selected-date">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <span class="date" style=" color: #2196F3;  margin-left: -10px; font-size: 22px;">
            Selected Date: {{ $date }} & Platoon Group: {{ \Carbon\Carbon::parse($date)->day % 2 === 1 ? 'Group A' : 'Group B' }}
        </span>

        </div>

        <a href="{{ route('beats.test_beats', ['date' => $date]) }}" class="next-btn">
            ← Back to Assigned
        </a>
    </div>

    <!-- Table -->

<h3>Remaining Students:  {{ $ineligibleStudents->count() }}</h3><br><br>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Platoon</th>
                    <th>Last Beat Date</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Rest Days Remaining</th>
                    <th>Next Eligible Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ineligibleStudents as $student)
                <tr class="{{ $student->rest_days_remaining === 0 || $student->eligible_tomorrow ? 'eligible-tomorrow' : 'skipped' }}">
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td>{{ $student->company_id }}</td>
                    <td>{{ $student->platoon }}</td>
                    <td>{{ $student->last_assigned_at }}</td>
                    <td>
                        @if($student->rest_days_remaining === 0)
                            <span class="label label-eligible">Eligible</span>
                        @elseif($student->eligible_tomorrow)
                            <span class="label label-eligible">Eligible Tomorrow</span>
                        @elseif($student->reason_skipped === 'Safari')
                            <span class="label label-safari">Safari</span>
                        @elseif($student->reason_skipped === 'Kitengo')
                            <span class="label label-kitengo">Kitengo</span>
                        @else
                            <span class="label label-skipped">Skipped</span>
                        @endif
                    </td>
                    <td>{{ $student->reason_skipped }}</td>
                    <td>{{ $student->rest_days_remaining }}</td>
                    <td>{{ $student->next_eligible_date ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
