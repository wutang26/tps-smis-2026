@extends('layouts.main')

@section('content')
<div class="container mt-4">

    <!-- Header Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Received Leave Requests</h5>

        <div class="d-flex gap-2">
            <a href="{{ route('leave-requests.statistics') }}" class="btn btn-primary">
                View Approved Leave Requests
            </a>
            <a href="{{ route('leave-requests.rejected') }}" class="btn btn-danger">
                View Rejected Leave Requests
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Requests Table -->
    @if($leaveRequests->isEmpty())
        <div class="alert alert-warning text-center">No leave requests found.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>S/N</th>
                    <th>Student Name</th>
                    <th>Company</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaveRequests as $request)
                    <tr>
                        <td>{{$loop->iteration}}.</td>
                        <td>{{ $request->student->first_name }} {{ $request->student->last_name }}</td>
                        <td>{{$request->student->company->name}} {{$request->student->platoon}}</td>
                        <td>{{ $request->reason }}</td>
                        <td class="d-flex gap-2">
                            <!-- Approve Button -->
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                                Approve
                            </button>

                            <!-- Reject Button (Triggers Modal) -->
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                Reject
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $leaveRequests->links('pagination::bootstrap-5') !!}
    @endif

    <!-- Modals (Placed Outside Table) -->
    @foreach($leaveRequests as $request)
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('leave-requests.approve', $request->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">
                                Approve Leave for {{ $request->student->first_name }} {{ $request->student->last_name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('leave-requests.reject', $request->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">
                                Reject Leave for {{ $request->student->first_name }} {{ $request->student->last_name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Reason for Rejection</label>
                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Submit Rejection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection
