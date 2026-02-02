@extends('layouts.main')

@section('scrumb')
<!-- Breadcrumb -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                <li class="breadcrumb-item active"><a href="#">Guards</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')

<style>
/* Container for page content */
.container-page {
    max-width: 1200px;
    margin: auto;
    padding: 15px;
}

/* Centered selected date line */
.selected-date {
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
    color: #1f2937;
}

/* Compact date picker aligned right */
.filter-form {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
    gap: 10px;
}

.filter-form input[type="date"] {
    width: 200px; /* compact */
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.filter-form button {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    cursor: pointer;
}

.filter-form button:hover {
    background-color: #2563eb;
}

/* Table headers above tables */
h3 {
    color: blue;
    text-align: left;
    margin-top: 40px; /* ensures spacing above table */
    margin-bottom: 10px;
}

/* Tables */
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
.assigned { background-color: #e0f7ff; }
.skipped { background-color: #ffe0e0; }
.eligible-tomorrow { background-color: #e0ffe0; }

/* Status labels */
.label { padding: 3px 6px; border-radius: 4px; color: #fff; font-weight: bold; }
.label-assigned { background-color: #2196F3; }
.label-skipped { background-color: #F44336; }
.label-eligible { background-color: #4CAF50; }
.label-safari { background-color: #FF9800; }
.label-kitengo { background-color: #9C27B0; }

.next-btn {
    padding: 6px 14px;
    border-radius: 4px;
    background-color: #10b981; /* green */
    color: white;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.next-btn:hover {
    background-color: #059669;
}


</style>

<div class="container-page">

    <!-- Centered Selected Date & Platoon -->
   <!-- Header: Selected Date left, Date Picker right -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="selected-date" style=" color: #2196F3;  margin-left: -10px; font-size: 22px;">
            Selected Date: {{ $date }} & Platoon Group: {{ \Carbon\Carbon::parse($date)->day % 2 === 1 ? 'Group A' : 'Group B' }}
        </div>

        <form action="{{ route('beats.test_beats') }}" method="GET" class="filter-form" style="display: flex; gap: 10px;">
            <input type="date" name="date" value="{{ $date }}" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; width: 200px;"  min="2026-01-16">
            <button type="submit" style="padding: 6px 12px; border-radius: 4px; border: none; background-color: #3b82f6; color: white; font-weight: 600; cursor: pointer;">Filter</button>
        </form>
    </div>
    <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
     <a href="{{ route('beats.guards.skipped', ['date' => $date]) }}" class="next-btn">
        Remaining Student→
    </a>
    </div>

<h3>Total Assigned Students:  {{ $assignedStudents->count() }}</h3><br><br>

    <!-- Assigned Students -->

<div class="table-card" style="overflow-x:auto;">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Company</th>
            <th>Platoon</th>
            <th>Beat Round</th>
             <th>Beat Status</th>
            <th>Assigned Date</th>
            <th>Status</th>
            <th>Reason / Notes</th>
            <th>Rest Days Remaining</th>
            <th>Next Eligible Date</th>
        </tr>
    </thead>
    <tbody>
        
       @foreach($assignedStudents as $student)
<tr class="assigned">
    <td>{{ $student->id }}</td>
    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
    <td>{{ $student->company_id }}</td>
    <td>{{ $student->platoon }}</td>
    <td>{{ $student->beat_round }}</td>
    <td>{{ $student->beat_status}}</td>
    <td>{{ $student->last_assigned_at }}</td>
    <td>
        @if($student->beat_status == 4)
            <span class="label label-safari">Safari</span>
        @elseif($student->beat_status == 0 && $student->beat_round == 0)
            <span class="label label-kitengo">Kitengo</span>
        @else
            <span class="label label-assigned">Assigned</span>
        @endif
    </td>
     <td>{{ $student->reason_skipped }}</td>
    <td>{{ $student->rest_days_remaining }}</td>
    <td>{{ $student->next_eligible_date }}</td>
</tr>
@endforeach


    </tbody>
</table>
</div>
@endsection
