@extends('layouts.main')

@section('content')
<div class="container">
    <h5><center>Pending Patient Approvals (Receptionist)</center></h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($patients->isEmpty())
        <p>No pending requests.</p>
    @else
    <table class="table table-striped">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Platoon</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patients as $patient)
                <tr>
                    <td>{{ $patient->student->first_name ?? '-' }}</td>
                    <td>{{ $patient->student->last_name ?? '-' }}</td>
                    <td>{{ $patient->student->platoon ?? '-' }}</td>
                    <td>{{ $patient->status }}</td>
                    <td>
                        <form action="{{ route('patients.approve', $patient->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve Patient</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
