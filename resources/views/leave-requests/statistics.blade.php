@extends('layouts.main')

@section('content')
@include('layouts.sweet_alerts.index')
<div class="container mx-auto p-6">
    <div class="d-flex justify-content-between align-items-center mb-4">
<a href="{{ route('leave-requests.chief-instructor') }}" class="btn btn-primary">
    <i class="bi bi-arrow-left"></i> Back
</a>

    </div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <center>
            <h2 class="text-3xl font-bold text-gray-800">Approved Leave Requests Details</h2>
        </center>

    </div>

    <!-- Approved Requests Table -->
    <div class="bg-white shadow-md rounded p-6 overflow-x-auto">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>S/N</th>
                    <th> Student Name</th>
                    <th>Company</th>
                    <!-- <th> Reason</th> -->
                    <th> Start date</th>
                    <th> End date</th>
                    <!-- <th>Approved At</th> -->
                    <th>Status</th>
                    <th style="width:200px">Action</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($approvedRequests as $request)
                <tr>
                    <td>{{$loop->iteration}}.</td>
                    <td>
                        {{ $request->student->first_name ?? '' }} {{ $request->student->last_name ?? '' }}
                    </td>
                    <td>{{$request->student->company->name}} {{$request->student->platoon}}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                    </td>

                    <td>

                        @if (empty($request->return_date) &&
                        \Carbon\Carbon::parse($request->end_date)->lt(\Carbon\Carbon::now()))
                        <span class="txt-danger">Late</span>
                        @elseif (empty($request->return_date) &&
                        \Carbon\Carbon::parse($request->end_date)->gt(\Carbon\Carbon::now()))
                        <span class="">On Leave</span>
                        @elseif (!empty($request->return_date))
                        <span class="">Returned</span>
                        @endif

                    </td>

                    <td class="d-flex  gap-2">
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                            data-bs-target="#statusModal{{ $request->id ?? ''}}">More</button>
                            @if(empty($request->return_date))
                        <form id="confirmFormId{{$request->id}}" action="{{route('leave_request.return',['leaveId' => $request->id])}}" method="post">
                            @csrf
                            <button type="button" onclick="confirmAction('confirmFormId{{$request->id}}', 'Return Leave','Leave ', 'Return')" class="btn btn-sm btn-warning">Return</button>
                        </form>
                        @endif

                        <div class="modal fade" id="statusModal{{  $request->id ?? '' }}" tabindex="-1"
                            aria-labelledby="approveModal{{ $request->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-info" id="statusModalLabel{{  $request->id ?? ''}}">
                                            Leave details for {{ $request->student->first_name ?? '' }}
                                            {{ $request->student->last_name ?? '' }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h3>Reason</h3><br><br>
                                        <p>
                                            {{ $request->reason }}
                                        </p>
                                        <p><small>Approved at:
                                            {{ $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->format('d M Y H:i') : '' }}
                                            </small></p>
                                    </div>

                                    <div class="modal-footer d-flex justify-content-end">
                                        <button class="btn btn-sm btn-success">
                                            <a href="{{ route('leave-requests.single.pdf', $request->id) }}">
                                                Download
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        No approved leave requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {!! $approvedRequests->links('pagination::bootstrap-5') !!}
    </div>
</div>

@endsection