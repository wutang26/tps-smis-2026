@extends('layouts.main')

@section('content')
<div class="col-sm-12">
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Admitted Patients</h5>
            <a href="{{ route('doctor.page') }}" class="btn btn-secondary btn-sm">← Back</a>
        </div>

        <div class="card-body">
            {{-- FILTERS --}}
            <form method="GET" action="{{ route('doctor.admitted') }}" class="row mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="company_id" class="form-select">
                        <option value="">Filter by Company</option>
                        <option value="HQ" {{ request('company_id') == 'HQ' ? 'selected' : '' }}>HQ</option>
                        <option value="A" {{ request('company_id') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ request('company_id') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ request('company_id') == 'C' ? 'selected' : '' }}>C</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-primary btn-sm">Apply Filters</button>
                    <a href="{{ route('doctor.admitted') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            {{-- PATIENT TABLE --}}
            @if($admittedPatients->isEmpty())
                <p class="text-center">No admitted patients found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Platoon</th>
                                <th>Company</th>
                                <th>Admitted Type</th>
                                <!-- <th>Excuse Type</th> -->
                                <th>Status</th>
                                <th>Admitted Date</th>
                                <th>Follow Up</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admittedPatients as $patient)
                                <tr>
                                    <td>{{ $patient->student->first_name ?? '-' }}</td>
                                    <td>{{ $patient->student->last_name ?? '-' }}</td>
                                    <td>{{ $patient->platoon ?? '-' }}</td>
                                    <td>{{ $patient->student->company->name ?? '-' }}</td>
                                    <td>{{ $patient->admitted_type ?? '-' }}</td>
                                    <!-- <td>{{ $patient->excuseType->name ?? '-' }}</td> -->
                                    <td>{{ ucfirst($patient->status) }}</td>
                                    <td>{{ $patient->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if(!$patient->is_discharged)
                                            <form action="{{ route('doctor.discharge', $patient->id) }}" method="POST" class="discharge-form">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Discharge</button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Discharged</span><br>
                                            <small>{{ \Carbon\Carbon::parse($patient->discharged_date)->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
 <!-- Pagination Links -->
 <div class="d-flex justify-content-center mt-4">
        {{  $admittedPatients->links('pagination::bootstrap-4') }} 
    </div> 
            @endif
        </div>
    </div>
</div>

{{-- SWEET ALERT --}}

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.discharge-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will discharge the patient!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, discharge'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
