@extends('layouts.main')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Rejected Leave Requests</h5>
        <a href="{{ route('leave-requests.chief-instructor') }}" class="btn btn-secondary">
            Back to Leave Requests
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($leaveRequests->isEmpty())
        <div class="alert alert-warning text-center">No rejected leave requests found.</div>
    @else
        <table class="table table-bordered table-striped">
        <thead class="table-dark">
    <tr>
        <th>Student Name</th>
        <th>Reason</th>
        <th>Rejection Reason</th>
        <th>Rejected At</th>
        <th>Action</th> 
    </tr>
</thead>
<tbody>
    @foreach($leaveRequests as $request)
        <tr>
            <td>{{ $request->student->first_name }} {{ $request->student->last_name }}</td>
            <td>{{ $request->reason }}</td>
            <td class="text-danger fw-bold">
                {{ $request->rejection_reason ?? 'N/A' }}
            </td>
            <td>{{ \Carbon\Carbon::parse($request->rejected_at)->format('d M, Y h:i A') }}</td>
            <td>
                <a href="{{ route('leave-requests.rejected.pdf', $request->id) }}" class="btn btn-sm btn-danger">
                    Download PDF
                </a>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
        {!! $leaveRequests->links('pagination::bootstrap-5') !!}
    @endif

</div>
@endsection
