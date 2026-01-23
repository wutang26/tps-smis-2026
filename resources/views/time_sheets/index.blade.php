@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">Time Sheets</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')


    @php
        $i = 0;
        use Carbon\Carbon;
    @endphp
            <form action="{{ route('timesheets.filter') }}" method="POST" class="d-flex flex-nowrap align-items-center gap-2 overflow-auto mb-2">
                @csrf
                @method('POST')

                <!-- Start Date -->
                <label class="d-flex align-items-center m-0">Start</label>
                <input type="date" name="start_date" max="{{ Carbon::today()->format('Y-m-d') }}" 
                    @if(isset($start_date)) value="{{ Carbon::parse($start_date)->format('Y-m-d') }}" @endif
                    class="form-control form-control-sm flex-shrink-0" style="width: 130px;">

                <!-- End Date -->
                <label class="d-flex align-items-center m-0">End</label>
                <input type="date" name="end_date" max="{{ Carbon::today()->format('Y-m-d') }}" 
                    @if(isset($end_date)) value="{{ Carbon::parse($end_date)->format('Y-m-d') }}" @endif
                    class="form-control form-control-sm flex-shrink-0" style="width: 130px;">

                <!-- Filter Button -->
                <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">Filter</button>

                <!-- Push New Time Sheet Button to the end -->
                <a href="{{ route('timesheets.create') }}" class="btn btn-success btn-sm flex-shrink-0 ms-auto">New Time Sheet</a>
            </form>

            @if (count($timesheets) > 0)
                <div class="table-outer">
                    <div class="table-responsive">

                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Staff</th>
                                    <th>Time(hours)</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Approved/Rejected By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timesheets as $timesheet)
                                    <tr>
                                        <td>{{ ++$i}}.</td>
                                        <td>{{ $timesheet->user->name}}</td>
                                        <td>{{ $timesheet->hours }}</td>
                                        <td>{{  Carbon::parse($timesheet->date)->format('d F, Y') }}</td>
                                        <td>{{ $timesheet->status }}</td>
                                        <td>{{ $timesheet->approvedBy->name?? '' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $timesheet->id ?? ''}}">
                                                More
                                            </button>

                                            @can('view-timesheet', $timesheet)
                                              <button @if($timesheet->status == 'approved' || $timesheet->status == 'rejected') disabled @endif class="btn btn-sm btn-warning">   <a 
                                                    href="{{ route('timesheets.edit', $timesheet->id) }}"> Edit</a></button>
                                            @endcan

                                            <form id="deleteForm{{ $timesheet->id }}" action="{{ route('timesheets.destroy', $timesheet->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button @if($timesheet->status == 'approved' || $timesheet->status == 'rejected') disabled @endif class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('deleteForm{{ $timesheet->id }}', 'Time sheet of {{ $timesheet->user->name}}')">Delete</button>
                                            </form>
                                        </td>

                                        <div class="modal fade" id="statusModal{{  $timesheet->id ?? '' }}" tabindex="-1"
                                            aria-labelledby="statusModalLabel{{  $timesheet->id ?? '' }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-info" id="statusModalLabel{{  $timesheet->id ?? ''}}">
                                                        Task Accomplished by {{ $timesheet->user->name }}  
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <ol class="mb-4">
                                                        @for($i = 0; $i<count(json_decode($timesheet->tasks)); ++$i)
                                                            <li>{{json_decode($timesheet->tasks)[$i]}}</li>
                                                        @endfor
                                                    </ol>
                                                        <div>
                                                            <div class="mb-2 text-info">Description</div>
                                                            <p> {{ $timesheet->description }}</p>
                                                        </div>
                                                       

                                                    </div>
                                                    @can('viewAny', $timesheet)
                                                    <div class="modal-footer">
                                                        <div class="d-flex gap-2">

                                                        <form action="{{ route('timesheets.approve', $timesheet->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button @if($timesheet->status == 'approved' || $timesheet->status == 'rejected') disabled @endif class="btn btn-sm btn-primary">Approve</button>
                                                        </form>
                                                        <form action="{{ route('timesheets.reject', $timesheet->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button @if($timesheet->status == 'approved' || $timesheet->status == 'rejected') disabled @endif class="btn btn-sm btn-danger">Reject</button>
                                                        </form>                                                            
                                                        </div>
                                                    </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
            @else
                No time Sheets available.
            @endif
        </div>
    </div>
@endsection