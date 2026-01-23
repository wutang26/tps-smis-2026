@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    </li>
                    <li class="breadcrumb-item active"><a href="#" id="home">Attendance Requests</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')
@if ($requests->isEmpty())
    <div class="alert alert-info">
        No attendance requests found.
    </div>
@else
    <div class="table-responsive">

                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Company</th>
                                    <th>Requested Date</th>
                                    <th>Type</th>
                                    <th>Requested at</th>
                                    <th>Requester</th>
                                    <th>Approved/Rejected By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr></tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $request->company->description}}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->date)->format('d F, Y') }}</td>
                                    <td>{{ $request->type->name }}</td>
                                    <td>{{ $request->created_at->format('h:i A, jS F Y') }}</td>
                                    <td>{{ $request->requester->name}}</td>
                                    <td>{{ $request->approvedBy?->name}}</td>
                                    <td>{{ ucfirst($request->status)}}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $request->id ?? ''}}">
                                                More
                                            </button>
                                    </td>

                                    <div class="modal fade" id="statusModal{{  $request->id ?? '' }}" tabindex="-1"
                                            aria-labelledby="statusModalLabel{{  $request->id ?? '' }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-info" id="statusModalLabel{{  $request->id ?? ''}}">
                                                        Requested by {{ $request->requester->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>
                                                            <div class="mb-2">Request Reason</div>
                                                            <small> {{ $request->reason }}</small><br>
                                                            <p>Status: <strong >{{ ucfirst($request->status) }}</strong></p>
                                                        </div>
                                                    </div>
                                                    @if($request->status != 'closed')
                                                    <div class="modal-footer">
                                                        <div class="d-flex gap-2">
                                                        <form action="{{ route('attendance.request.update-status') }}" method="POST">
                                                            @csrf
                                                            <input type="text" name="attendanceRequestId" value="{{ $request->id }}" hidden>
                                                            <input type="text" name="status" value="approved" hidden>
                                                            <button @if($request->status == 'approved' || $request->status == 'rejected') disabled @endif class="btn btn-sm btn-primary">Approve</button>
                                                        </form>
                                                        <form action="{{ route('attendance.request.update-status') }}" method="POST">
                                                            @csrf
                                                            <input type="text" name="attendanceRequestId" value="{{ $request->id }}" hidden>
                                                            <input type="text" name="status" value="rejected" hidden>
                                                            <button @if($request->status == 'approved' || $request->status == 'rejected') disabled @endif class="btn btn-sm btn-danger">Reject</button>
                                                        </form>
                                                        <form action="{{ route('attendance.request.update-status') }}" method="POST">
                                                            @csrf
                                                            <input type="text" name="attendanceRequestId" value="{{ $request->id }}" hidden>
                                                            <input type="text" name="status" value="closed" hidden>
                                                            <button @if($request->status == 'rejected') disabled @endif class="btn btn-sm btn-danger">Close</button>
                                                        </form>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $requests->appends(request()->query())->links('pagination::bootstrap-5') !!}
                    @endif
                    </div>
@endsection
