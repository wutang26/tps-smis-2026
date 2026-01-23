@extends('layouts.main')

@section('content')
<div class="container">
    <h5><center>Received Leave Requests (OC Panel)</center></h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($leaveRequests->isEmpty())
        <p>No leave requests found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Student Name</th>
                    <!-- <th>From Date</th>
                    <th>To Date</th> -->
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaveRequests as $request)
                    <tr>
                        <td>{{$loop->iteration}}.</td>
                        <td>{{ $request->student->first_name }} {{ $request->student->last_name }}</td>
                        <!-- <td>{{ $request->from_date }}</td>
                        <td>{{ $request->to_date }}</td> -->
                        <td>{{ $request->reason }}</td>
                        <td>
                            <form method="POST" action="{{ route('oc.leave-requests.forward', $request->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Forward to Chief Instructor</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
