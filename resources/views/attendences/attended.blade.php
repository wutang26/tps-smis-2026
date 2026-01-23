@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/attendences/">{{  $attendenceType->name  }} Attendences</a></li>
                <li class="breadcrumb-item active"><a href="#">{{ $company->description }}</a></li>
                </li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
@include('layouts.sweet_alerts.index')
@if (count($attendences) == 0)
    <h1>No attendence recorded today.</h1>
@else
<h4>Attendances for {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}.</h4><br>
    <div class="d-flex @if(auth()->user()->hasRole(['CRO','Admin','Super Administrator'])) justify-content-between @else justify-content-end @endif">
@php
    $attendanceCompany = $company->company_attendance($date, $attendenceType->id);
    $attendanceStatus = $attendanceCompany?->status;
    $hoursSinceUpdate = \Carbon\Carbon::parse($attendanceCompany->updated_at)->diffInHours(\Carbon\Carbon::now());
@endphp
@if(auth()->user()->hasRole(['CRO','Admin','Super Administrator']))
<form id="UpdateStatusForm" action="{{ route('attendance.updateCompanyAttendance', [$company->id, $date]) }}" method="POST" style="display:inline;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusInput" value="{{ $attendanceStatus }}" />
    <input type="hidden" name="falsified_reason" id="reasonInput" />

    <div class="dropdown d-inline">
        {{-- Main button with current status style --}}
        @if($attendanceStatus === 'unverified')
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Current status: Unverified">
                <i class="bi bi-hourglass-split"></i> Unverified
            </button>
        @elseif($attendanceStatus === 'verified')
            @if($hoursSinceUpdate < 2)
                <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Current status: Verified (editable)">
                    <i class="bi bi-check2-circle"></i> Verified
                </button>
            @else
                <button class="btn btn-sm btn-success" disabled title="Verified and locked">
                    <i class="bi bi-check2-circle"></i> Verified
                </button>
            @endif
        @elseif($attendanceStatus === 'falsified')
            @if($hoursSinceUpdate < 2)
                <button class="btn btn-sm btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Current status: Falsified (editable)">
                    <i class="bi bi-exclamation-octagon"></i> Falsified
                </button>
            @else
                <button class="btn btn-sm btn-danger" disabled title="Falsified and locked">
                    <i class="bi bi-exclamation-octagon"></i> Falsified
                </button>
            @endif
        @endif

        {{-- Dropdown menu (only if editable) --}}
        @if(
            ($attendanceStatus === 'unverified') ||
            ($attendanceStatus === 'verified' && $hoursSinceUpdate < 2) ||
            ($attendanceStatus === 'falsified' && $hoursSinceUpdate < 2)
        )
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @if($attendanceStatus === 'unverified')
                    <li>
                        <a href="#" class="dropdown-item text-primary" onclick="confirmStatusChange(event, 'verified', 'Verify Company Attendance?')">
                            <i class="bi bi-check"></i> Verify
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item text-danger" onclick="confirmStatusChange(event, 'falsified', 'Falsify Company Attendance?')">
                            <i class="bi bi-exclamation-octagon"></i> Falsify
                        </a>
                    </li>
                @elseif($attendanceStatus === 'verified')
                    <li>
                        <a href="#" class="dropdown-item text-warning" onclick="confirmStatusChange(event, 'unverified', 'Unverify Company Attendance?')">
                            <i class="bi bi-x-circle"></i> Unverify
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item text-danger" onclick="confirmStatusChange(event, 'falsified', 'Falsify Company Attendance?')">
                            <i class="bi bi-exclamation-octagon"></i> Falsify
                        </a>
                    </li>
                @elseif($attendanceStatus === 'falsified')
                    <li>
                        <a href="#" class="dropdown-item text-warning" onclick="confirmStatusChange(event, 'unverified', 'Unfalsify Company Attendance?')">
                            <i class="bi bi-arrow-counterclockwise"></i> Unfalsify
                        </a>
                    </li>
                @endif
            </ul>
        @endif
    </div>
</form>
@endif
<script>
function confirmStatusChange(event, status, message) {
    event.preventDefault();

    if (status === 'falsified') {
        Swal.fire({
            title: 'Falsify Attendance',
            text: message,
            input: 'textarea',
            inputPlaceholder: 'Enter reason for falsifying...',
            inputAttributes: {
                'aria-label': 'Enter reason for falsifying'
            },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value.trim()) {
                    return 'You must provide a reason!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('statusInput').value = status;
                document.getElementById('reasonInput').value = result.value.trim();
                document.getElementById('UpdateStatusForm').submit();
            }
        });
    } else {
        Swal.fire({
            title: 'Confirm',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('statusInput').value = status;
                document.getElementById('UpdateStatusForm').submit();
            }
        });
    }
}
</script>


        <a href="{{ route('attendences.generatePdf',['companyId'=>$company->id,'date'=>$date, 'attendenceTypeId' => $attendenceType->id]) }}" target="_blank">
            <button title="Download report" class="btn btn-sm btn-success"><i class="gap 2 bi bi-download"></i> Report</button>
        </a>

    </div>
    <div class="table-responsive">
        <table class="table table-striped truncate m-0">
            <thead>
                <tr>
                    <th>Platoon</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Sentry</th>
                    <th>Mess</th>
                    <th>Off</th>
                    <th>E.D</th>
                    <th>Admitted</th>
                    <th>Leave</th>
                    <th>Lock Up</th>
                    <th>Kazini</th>
                    <th>ME</th>
                    <th>KE</th>
                    <th>Total</th>
                    <th width="280px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach ($attendences as $key => $attendence)
                    <tr>
                        <td>{{$attendence->platoon->company->name}} - {{$attendence->platoon->name}}</td>
                        <td>{{$attendence->present}}</td>
                        <td>{{$attendence->absent}}</td>
                        <td>{{$attendence->sentry}}</td>
                        <td>{{$attendence->mess}}</td>
                        <td>{{$attendence->off}}</td>
                        <td>{{$attendence->ed}}</td>
                        <td>{{$attendence->adm}}</td>
                        <td>{{$attendence->safari}}</td>
                        <td>{{$attendence->lockUp?? ''}}</td>
                        <td>{{$attendence->kazini?? ''}}</td>
                        <td>{{$attendence->male}}</td>
                        <td>{{$attendence->female}}</td>
                        <td>{{$attendence->total}}</td>
                        <td>
                            <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#MoreAbsent{{$attendence->id}}">More</button>
                                @if ($attendence->created_at->diffInHours(\Carbon\Carbon::now()) < 2 )
                                 <a href="{{ route('attendences.changanua',['attendenceId'=> $attendence->id]) }}"> <button class="btn  btn-info btn-sm" >Mchanganuo</button></a>                               
                                @endif
                            <div class="modal fade" id="MoreAbsent{{$attendence->id}}" tabindex="-1"
                                aria-labelledby="statusModalLabelMore{{$attendence->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabelMore">
                                                Absent Students
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if (count($attendence->absent_students) < 1)
                                                <p>No absent students recorded</p>
                                            @endif
                                            
                                            <ol class="mb-3">
                                                @foreach($attendence->absent_students as $student)
                                                    @if ($student != NULL)
                                                        <li>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}
                                                        </li>
                                                    @endif

                                                @endforeach
                                            </ol>
                                            <span>Recorded by:  <strong class="text-success">{{ $attendence->recordedBy?->name }}</strong></span>
                                        </div>
                                        <!-- <div class="modal-footer">
                                            <a
                                                href="{{url('attendences/list-absent_students/' . $company->id . '/' . $attendence->id.'/'.$date)}}"><button
                                                    class="btn btn-sm btn-primary">Add absents</button></a>
                                        
                                        </div> -->
                                    </div>
                            </div>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection