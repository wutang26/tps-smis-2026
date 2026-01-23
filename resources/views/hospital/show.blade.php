@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Patients</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">View</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')

@if($patients->isEmpty())
<div class="alert alert-warning text-center">No leave patients found.</div>
@else
<h3>Hospital informations for {{$patients[0]->student->force_number}} {{$patients[0]->student->first_name}}
    {{$patients[0]->student->last_name}}</h3><br><br>
<table class="table table-bordered table-striped">
    <thead class="">
        <tr>
            <th>S/N</th>
            <th>Platoon</th>
            <th>Excuse Type</th>
            <th>Rest Days</th>
            <th>Reported At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        @endphp
        @foreach($patients as $patient)
        <tr>
            <td>{{ ++$i }}.</td>
            <td>{{ $patient->company->name }} - {{ $patient->platoon }} </td>
            <td>{{ $patient->excuseType->excuseName }}</td>
            <td>{{ $patient->rest_days }}</td>
            <td>{{ \Carbon\Carbon::parse($patient->created_at)->format('d F,Y') }}</td>
            <td><button class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#ViewModal{{ $patient->id }}">
                    More
                </button></td>
        </tr>
        <div class="modal fade" id="ViewModal{{ $patient->id }}" tabindex="-1"
            aria-labelledby="ViewModalLabel{{ $patient->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ViewModalLabel{{ $patient->id }}">Doctor's Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>{{ $patient->doctor_comment }}</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        @endforeach
    </tbody>
</table>
@endif

@endsection