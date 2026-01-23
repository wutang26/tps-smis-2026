@extends('layouts.main')

@section('content')

<div class="container">
    <h2>Patient Details</h2>
    <p><strong>First Name:</strong> {{ $patient->student->first_name }}</p>
    <p><strong>Last Name:</strong> {{ $patient->student->last_name }}</p>
    <p><strong>Platoon:</strong> {{ $patient->platoon }}</p>
    <p><strong>Excuse Type:</strong> {{ $patient->excuse_type ?? '-' }}</p>
    <p><strong>Days of Rest:</strong> {{ $patient->rest_days ?? '-' }}</p>
    
    <p><strong>End Date of Rest:</strong>
    @if(optional($patient->excuseType)->excuseName === 'Admitted')
        {{ $patient->released_at ?? 'Not yet discharged' }}
    @elseif ($patient->rest_days && $patient->created_at)
        {{ \Carbon\Carbon::parse($patient->created_at)->addDays($patient->rest_days)->format('Y-m-d') }}
    @else
        -
    @endif
</p>

</div>
@endsection

