@extends('layouts.main')

@section('content')
<div class="col-sm-12">

    @include('layouts.sweet_alerts.index')
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Student Details Table -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Fetch Students Details here ...</h5>
        </div>
        <!-- Search Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('leave-requests.search') }}" method="GET"
                    class="d-flex justify-content-between mb-3">
                    <div class="d-flex">
                        <select class="form-select me-2" name="company_id">
                            @if(auth()->user()->hasRole('Sir Major'))
                            <option value="{{ $assignedCompany->id ?? '' }}">{{ $assignedCompany->name ?? 'N/A' }}
                            </option>
                            @else
                            <option value="" disabled>Select Company</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                        <select class="form-select me-2" name="platoon">
                            <option value="">Select Platoon</option>
                            @for ($i = 1; $i <= 14; $i++) <option value="{{ $i }}"
                                {{ request('platoon') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                        </select>
                        <input type="text" class="form-control me-2" name="fullname" placeholder="Enter Name (optional)"
                            value="{{ request('fullname') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

            </div>
        </div>
        <!-- Display Student Details -->
        @if(request()->has('company_id') || request()->has('platoon') || request()->has('fullname') ||
        request()->has('student_id'))
        @if (isset($studentDetails))
        @if($studentDetails->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Platoon</th>
                        <th>Company</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentDetails as $student)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $student->first_name ?? 'N/A' }}</td>
                        <td>{{ $student->middle_name ?? 'N/A' }}</td>
                        <td>{{ $student->last_name ?? 'N/A' }}</td>
                        <td>{{ $student->platoon ?? 'N/A' }}</td>
                        <td>{{ $student->company->name ?? 'N/A' }}</td>
                        <td>
                            <!-- Button to open Modal -->
                            <button @if($student->hasActiveLeaveRecord()) disabled @endif class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#studentModal{{ $student->id }}">
                                Enter Student Leave Details
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="studentModal{{ $student->id }}" tabindex="-1"
                                aria-labelledby="modalLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Form Start -->
                                        <form action="{{ route('leave-requests.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <!-- Hidden Inputs -->
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <!-- <input type="hidden" name="staff_id" value="{{ auth()->user()->id }}"> -->
                                            <input type="hidden" name="company_id" value="{{ $student->company_id }}">
                                            <input type="hidden" name="platoon" value="{{ $student->platoon }}">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel{{ $student->id }}">
                                                    Enter Leave Details for {{ $student->first_name }}
                                                    {{ $student->last_name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">

                                                    <!-- Reason -->
                                                    <div class="col-12">
                                                        <label for="reason" class="form-label">📝 Reason</label>
                                                        <textarea class="form-control rounded-3" id="reason"
                                                            name="reason" rows="3"
                                                            placeholder="Explain the reason for your leave"
                                                            required></textarea>
                                                    </div>

                                                    <!-- Phone Number -->
                                                    <div class="col-md-6">
                                                        <label for="phone_number" class="form-label">📞 Phone
                                                            Number</label>
                                                        <input type="text" class="form-control rounded-3"
                                                            id="phone_number" name="phone_number"
                                                            placeholder="Enter student phone number">
                                                    </div>

                                                    <!-- Location -->
                                                    <div class="col-md-6">
                                                        <label for="location" class="form-label">📍 Location</label>
                                                        <input type="text" class="form-control rounded-3" id="location"
                                                            name="location" placeholder="Enter leave location" required>
                                                    </div>


                                                    <!-- Start Date -->
                                                    <!-- <div class="col-md-6">
                        <label for="start_date" class="form-label">📅 Start Date</label>
                        <input type="date" class="form-control rounded-3" id="start_date" name="start_date" required>
                    </div>  -->

                                                    <!-- End Date -->
                                                    <!-- <div class="col-md-6">
                        <label for="end_date" class="form-label">📅 End Date</label>
                        <input type="date" class="form-control rounded-3" id="end_date" name="end_date" required>
                    </div>  -->

                                                    <!-- Attachment -->
                                                    <div class="col-12">
                                                        <label for="attachments" class="form-label">📎 Attachment
                                                            (Optional)</label>
                                                        <input type="file" class="form-control rounded-3"
                                                            id="attachments" name="attachments">
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="col-12 text-center mt-4">
                                                        <button type="submit"
                                                            class="btn btn-success btn-lg rounded-3 px-5">
                                                            <i class="bi bi-send-fill"></i> Submit Request
                                                        </button>
                                                    </div>
                                                </div>
                                        </form>
                                        <!-- Form End -->
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $studentDetails->links('pagination::bootstrap-5') !!}
        @else
        <p class="mt-3 text-danger">{{ $message }}</p>
        @endif
        @endif              
    @endif
    </div>
    @if (isset($leaves))

    @if($leaves->isEmpty())
    <div class="alert alert-warning text-center">No leave requests found.</div>
    @else
    <table class="table table-bordered table-striped">
        <thead class="">
            <tr>
                <th>S/N</th>
                <th>Student Name</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $request)
            <tr>
                <td>{{$loop->iteration}}.</td>
                <td>{{ $request->student->first_name }} {{ $request->student->last_name }}</td>
                <td>{{ $request->reason }}</td>
                <td>{{$request->status}}</td>
                @can('leave-delete')
                <td class="d-flex gap-2">
                    @if($request->status == 'pending')
                    <!-- Reject Button (Triggers Modal) -->

                    <form id="deleteForm{{ $request->id }}" action="{{route('leave-requests.delete', $request->id)}}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                            onclick="confirmDelete('deleteForm{{ $request->id }}','Leave')"
                            type="button">Delete</button>
                    </form>
                    @include('layouts.sweet_alerts.confirm_delete')
                    @elseif($request->status == 'approved')
                    <a href="{{ route('leave-requests.single.pdf', $request->id) }}" class="btn btn-success">Print</a>
                    @elseif($request->status == 'rejected')
                    <a href="{{ route('leave-requests.rejected.pdf', $request->id) }}" class="btn btn-danger">
                        Print
                    </a>
                    @else
                    <button disabled class="btn btn-success">Print</button>
                    @endif
                </td>


                @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $leaves->links('pagination::bootstrap-5') !!}
    @endif
    @endif
    @endsection