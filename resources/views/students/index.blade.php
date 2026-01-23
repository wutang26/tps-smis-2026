@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb" style="margin-right: 25px;">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">List</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
@include('layouts.sweet_alerts.index')
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    
<div class="row flex-nowrap overflow-auto align-items-center g-2 justify-content-between">
    <!-- Left: Upload / Update -->
    <div class="d-flex col-auto gap-2">
        @can('student-create')
        <a href="{{ route('uploadStudents') }}" class="btn btn-sm btn-primary">Upload students</a>
        @endcan
        @can('student-edit')
        <a href="{{ route('updateStudents') }}" class="btn btn-sm btn-secondary">Update students</a>
        @endcan
    </div>

    <!-- Center: Search / Filter -->
    <div class="col-auto mx-auto">
        <form class="d-flex flex-nowrap gap-2 overflow-auto" action="{{ route('students.search') }}" method="GET" style="white-space: nowrap;">
            @csrf
            @method("POST")
            <input type="text" name="name" value="{{ request('name') }}" class="form-control form-control-sm flex-shrink-0" style="width: 120px;" placeholder="Name">
            <select id="companies"
            name="company_id"
            class="form-select form-select-sm flex-shrink-0"
            style="width: 140px;" onchange="this.form.submit()">
        <option value="" {{ !request('company_id') ? 'selected' : '' }}>Company</option>
        @foreach ($companies as $company)
            <option value="{{ $company->id }}"
                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach
    </select>

    <select id="platoons"
            name="platoon"
            class="form-select form-select-sm flex-shrink-0"
            style="width: 110px;" onchange="this.form.submit()">
        <option value="">Platoon</option>
    </select>
        </form>
    </div>

    <!-- Right: Create / Sheet -->
    <div class="d-flex col-auto gap-2 justify-content-end">
        @can('student-create')
            @if(request()->filled('platoon') && request()->filled('company_id'))
            <a class="btn btn-success btn-sm flex-shrink-0" href="{{ route('students.generatePdf', [request('platoon'),request('company_id')]) }}">
                <i class="bi bi-download"></i> Sheet
            </a>
            @endif
            <form action="{{ route('students.update.passwords') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">
                    Reset Passwords
                </button>
            </form>
            <a class="btn btn-success btn-sm flex-shrink-0" href="{{ url('students/create') }}">
                <i class="fa fa-plus"></i> Create students
            </a>
        @endcan
    </div>
</div>

<br>
<center><span>Waliohakikiwa: {{ $approvedCount }}</span><center>
</div>


<div class="card-body">
    @if ($students->isEmpty())
    <h3>No student available for provided criteria.</h3>
    @else
    <div class="table-outer">
        <div class="table-responsive">
            <table class="table table-striped truncate m-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Force Number</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Platoon</th>
                        <th>Phone</th>
                        <th>Home Region</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $i = 0;?>
                    @foreach ($students as $key => $student)

                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$student->force_number ?? ''}}</td>
                        <td>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}</td>
                        <td>{{$student->company->name ?? ''}}</td>
                        <td>{{$student->platoon}}</td>
                        <td>{{$student->phone}}</td>
                        <td>{{$student->home_region}}</td>
                        <td class="d-flex gap-2">
                            @can('student-list')
                            <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">
                                Show</a>
                            @endcan
                            @can('student-edit')
                            <!-- <a class="btn btn-primary btn-sm" href="{{ route('students.edit', $student->id) }}">Edit</a> -->
                            @if($student->status != 'approved')
                            <button type="button" class="btn btn-sm btn-warning">
                                Not Verified
                            </button>
                            @else
                            <button type="button" class="btn btn-sm btn-success">
                                ✅ &nbsp;Verified
                            </button>
                            @endif
                            @endcan
                            <!-- Trigger Button -->
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#dismissStudentModal{{ $student->id }}">
                            🛑Dismiss
                            </button>
                        </td>
                        <!-- Modal -->
                        <div class="modal fade" id="dismissStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="dismissStudentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="margin-top: -12%";>
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-danger">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="dismissStudentModalLabel">Confirm Dismissal</h5>
                                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form method="POST" action="{{ route('students.dismiss', $student->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="mb-3">
                                    <label for="reason_id" class="form-label">Dismissal Reason</label>
                                    <select name="reason_id" id="reason_id_{{ $student->id }}" class="form-select reason-select" required>
                                        <option value="">-- Select Reason --</option>
                                         @foreach ($terminationReasons as $category => $reasons)
                                                <optgroup label="{{ ucfirst($category) }}">
                                                @foreach ($reasons as $reason)
                                                    <option value="{{ $reason->id }}" data-code="{{ strtolower($reason->category) }}">{{ $reason->reason }}</option>
                                                @endforeach
                                                </optgroup>
                                            @endforeach
                                    </select>
                                    </div>
                                    <div class="mb-3 d-none" id="customReasonWrapper_{{ $student->id }}">
                                        <label for="custom_reason_{{ $student->id }}" class="form-label">Specify Reason</label>
                                        <textarea type="text" name="custom_reason" id="custom_reason_{{ $student->id }}" class="form-control" placeholder="Enter reason here"> </textarea>
                                    </div>
                                    @php
                                        $today = \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp

                                    <div class="mb-3">
                                        <label for="dismissed_at" class="form-label">Dismissal Date</label>
                                        <input type="date" name="dismissed_at" id="dismissed_at" class="form-control" required max="{{ $today }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Confirm Dismissal</button>
                                </div>
                            </form>
                            </div>
                        </div>
                        </div>

                        @can('beat-edit')
                        <td>
                            @if($student->beat_status == '1')
                            <form action="{{ route('students.deactivate_beat_status', $student->id) }}" method="POST"
                                id="toggleForm{{ $student->id }}">
                                @csrf
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle{{ $student->id }}"
                                        name="status{{ $student->id }}" @if($student->beat_status == '1') checked
                                    @endif>
                                </div>
                                <button type="submit" style="display: none;">Submit</button>
                            </form>

                            @else
                            <form action="{{ route('students.activate_beat_status', $student->id) }}" method="POST"
                                id="toggleForm{{ $student->id }}" class="d-flex gap-2">
                                @csrf
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle{{ $student->id }}"
                                        name="status{{ $student->id }}">
                                </div>
                                <button type="submit" style="display: none;">Submit</button>
                            </form>
                            @endif
                        </td>
                        @endcan()
                        <script>
                        // Listen for changes to the toggle
                        document.getElementById('statusToggle{{ $student->id }}').addEventListener('change',
                            function() {
                                // Automatically submit the form when toggle is changed
                                document.getElementById('toggleForm{{ $student->id }}').submit();
                            });
                        </script>


                        <div class="modal fade" id="createNewContact{{$student->id}}" tabindex="-1"
                            aria-labelledby="createNewContactLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header flex-column">
                                        <div class="text-center">
                                            <h4 class="text-danger">Delete Student</h4>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h5>You are about to delete {{$student->first_name}} {{$student->middle_name}}
                                            {{$student->last_name}}.
                                        </h5>
                                        <p>Please confirm to delete.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <form method="POST" action="{{url('students/' . $student->id . '/delete')}}"
                                            style="display:inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-danger btn-sm">Confirm</i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{!! $students->appends(request()->query())->links('pagination::bootstrap-5') !!}


@endsection


@section('scripts')
<script>
    document.querySelectorAll('.reason-select').forEach(select => {
    select.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const code = selectedOption.getAttribute('data-code');
        const studentId = this.id.split('_').pop(); // Extract student ID from select ID

        const wrapper = document.getElementById(`customReasonWrapper_${studentId}`);
        if (code === 'hiari') {
        wrapper.classList.remove('d-none');
        } else {
        wrapper.classList.add('d-none');
        wrapper.querySelector('input').value = '';
        }
    });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const companiesSelect = document.getElementById('companies');
    const platoonsSelect = document.getElementById('platoons');
    const selectedCompany = companiesSelect.value;
    const selectedPlatoon = "{{ request('platoon') }}"; // Keeps platoon selected after reload

    // Function to load platoons for a given company
    function loadPlatoons(companyId, preselect = null) {
        platoonsSelect.innerHTML = '<option value="">Platoon</option>';

        if (companyId) {
            fetch(`/tps-smis/platoons/${companyId}`)
                .then(response => response.json())
                .then(platoons => {
                    platoons.forEach(platoon => {
                        const option = document.createElement('option');
                        option.value = platoon.name;
                        option.text = platoon.name;
                        platoonsSelect.appendChild(option);
                    });

                    // Preselect platoon if exists
                    if (preselect) {
                        platoonsSelect.value = preselect;
                    }
                })
                .catch(error => console.error('Error fetching platoons:', error));
        }
    }

    // Load platoons on page load if a company is already selected
    if (selectedCompany) {
        loadPlatoons(selectedCompany, selectedPlatoon);
    }

    // Reload platoons when company changes
    companiesSelect.addEventListener('change', function () {
        loadPlatoons(this.value);
    });
});
</script>
@endsection