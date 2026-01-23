@extends('layouts.main')

@section('content')
<div class="col-sm-12">

    <!-- Statistics Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Statistics</h5>
                  
        </div>
        <div class="card-body">
            <div class="row">
    <div class="col-4 col-md-4">
        <h6>Daily </h6>
        <p>{{ $dailyCount }} </p>
        <a href="{{ route('hospital.viewDetails', ['timeframe' => 'daily', 'company_id' => request('company_id'), 'platoon' => request('platoon')]) }}"
            class="btn btn-info btn-sm">
            View  
        </a>
    </div>
    <div class="col-4 col-md-4">
        <h6>Weekly </h6>
        <p>{{ $weeklyCount }} </p>
        <a href="{{ route('hospital.viewDetails', ['timeframe' => 'weekly', 'company_id' => request('company_id'), 'platoon' => request('platoon')]) }}"
            class="btn btn-info btn-sm">
            View  
        </a>
    </div>
    <div class="col-4 col-md-4">
        <h6>Monthly </h6>
        <p>{{ $monthlyCount }} </p>
        <a href="{{ route('hospital.viewDetails', ['timeframe' => 'monthly', 'company_id' => request('company_id'), 'platoon' => request('platoon')]) }}"
            class="btn btn-info btn-sm">
            View  
        </a>
    </div>
</div>

        </div>
    </div>

    <!-- Patient Details Table -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Patient Details</h5>
        </div>

        <!-- Search Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('hospital.index') }}" method="GET" class="d-flex justify-content-between mb-3">
                    <div class="d-flex">
                        <select class="form-select me-2" name="company_id">
                            <option value="" disabled>Select Company</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ request('company_id') == $company->id ? 'selected' : '' }}   >
                                {{ $company->name }}
                            </option>
                            @endforeach
                        </select>
                        <select class="form-select me-2" name="platoon">
                            <option value="">Select Platoon</option>
                            @for ($i = 1; $i <= 14; $i++) <option value="{{ $i }}"
                                {{ request('platoon') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                        </select>
                        <input type="text" class="form-control me-2" name="name" placeholder="Enter Name (optional)"
                            value="{{ request('name') }}">


                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

            </div>
        </div>
        <!-- Display Student Details -->
        @if(request()->has('company_id') || request()->has('platoon') || request()->has('fullname') ||
        request()->has('student_id'))
        @if($students->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Platoon</th>
                        <th>Company</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($isCompanySelected)
                        
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->first_name ?? 'N/A' }}</td>
                        <td>{{ $student->middle_name ?? 'N/A' }}</td>
                        <td>{{ $student->last_name ?? 'N/A' }}</td>
                        <td>{{ $student->platoon ?? 'N/A' }}</td>
                        <td>{{ $student->company->name ?? 'N/A' }}</td>
                        <td>
                            <button class="btn btn-primary"
                                onclick="sendForApproval({{ $student->id }}, '{{ $student->first_name }}', '{{ $student->last_name }}')">
                                Send
                            </button>

                            <script>
                            function sendForApproval(studentId, firstName, lastName) {
                                Swal.fire({
                                    title: `Send for Approval: ${firstName}  ${lastName}`,
                                    text: "Are you sure you want to send this student for Receptionist approval?",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Yes, send it!"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Create and submit a hidden form
                                        let form = document.createElement("form");
                                        form.method = "POST";
                                        form.action = "{{ route('students.sendToReceptionist') }}";
                                        form.style.display = "none";

                                        let csrfInput = document.createElement("input");
                                        csrfInput.type = "hidden";
                                        csrfInput.name = "_token";
                                        csrfInput.value = "{{ csrf_token() }}";

                                        let studentInput = document.createElement("input");
                                        studentInput.type = "hidden";
                                        studentInput.name = "student_id";
                                        studentInput.value = studentId;

                                        form.appendChild(csrfInput);
                                        form.appendChild(studentInput);
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                });
                            }
                            </script>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {!! $students->links('pagination::bootstrap-5') !!}
        @else
        <p class="mt-3 text-danger">{{ $message }}</p>
        @endif
        @endif
    </div>
    @endsection