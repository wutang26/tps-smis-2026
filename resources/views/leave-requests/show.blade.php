@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Leaves</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">View</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')
@if($leaveRequests->isEmpty())
<div class="alert alert-warning text-center">No leave requests found.</div>
@else
<h3>Leaves for {{$leaveRequests[0]->student->force_number}} {{$leaveRequests[0]->student->first_name}}
    {{$leaveRequests[0]->student->last_name}}</h3><br><br>
<table class="table table-bordered table-striped">
    <thead class="">
        <tr>
            <th>S/N</th>
            <th>Platoon</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Return Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        @endphp
        @foreach($leaveRequests as $request)
        <tr>
            <td>{{ ++$i }}.</td>
            <td>{{ $request->company->name }} - {{ $request->platoon }} </td>
            <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d F,Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($request->end_date)->format('d F,Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($request->return_date)->format('d F,Y') }}</td>
            <td><button class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#ViewModal{{ $request->id }}">
                    More
                </button></td>
        </tr>
        <div class="modal fade" id="ViewModal{{ $request->id }}" tabindex="-1"
            aria-labelledby="ViewModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ViewModalLabel{{ $request->id }}">Reason</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>{{ $request->reason }}</p>
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