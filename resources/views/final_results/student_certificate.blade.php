@extends('layouts.main')
@section('style')
<style>
    /* Remove Bootstrap pill/tab backgrounds */
.nav-link {
    background: transparent !important;
    border: none !important;
    color: #555;
    position: relative;
    font-weight: 500;
    padding-bottom: 8px;
}

/* Hover */
.nav-link:hover {
    color: #000;
}

/* Active underline */
.nav-link.active {
    color: #0d6efd;
}

.nav-link.active::after {
    content: "";
    position: absolute;
    height: 3px;
    width: 100%;
    left: 0;
    bottom: 0;
    background: #0d6efd;
    border-radius: 2px;
}

/* Make tabs scrollable if they overflow */
.nav.nav-tabs {
    overflow-x: auto;
    white-space: nowrap;
    flex-wrap: nowrap;
}

</style>
@endsection
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home </a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Print Certificates</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection

@section('content')

    <div class="row">
        @session('success')
            <div class="alert alert-success" role="alert">
                {{ $value }}
            </div>
        @endsession

        <ul class="nav nav-tabs d-flex flex-nowrap" id="companyTabs" role="tablist">
    @php
        $foundActiveTab = false;
    @endphp
    @foreach($companies as $company)
        @php
            $hasStudents = $company->students->isNotEmpty();
            $isActive = !$foundActiveTab && $hasStudents;
        @endphp

        <li class="nav-item" role="presentation" style="flex: 0 0 auto;">
            <button class="nav-link {{ $isActive ? 'active' : '' }}"
                id="tab-{{ $company->id }}" 
                data-bs-toggle="tab"
                data-bs-target="#company-{{ $company->id }}" 
                type="button" role="tab"
                aria-controls="company-{{ $company->id }}" 
                aria-selected="{{ $isActive ? 'true' : 'false' }}">
                {{ $company->description }}
            </button>
        </li>

        @php
            if ($isActive) $foundActiveTab = true;
        @endphp
    @endforeach
</ul>


        <div class="tab-content" id="companyTabContent">
            @php
                $foundActiveTab = false;
            @endphp

            @foreach($companies as $company)
                @php
                    $hasStudents = $company->students->isNotEmpty();
                    $isActive = !$foundActiveTab && $hasStudents;
                @endphp

                <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="company-{{ $company->id }}" role="tabpanel"
                    aria-labelledby="tab-{{ $company->id }}">

                    <div class="card my-3">
                        <div class="col-12 col-md-3">
                            <form action="{{ route('students.search_certificate', $company->id) }}" method="GET">
                                @csrf

                                <div class="d-flex gap-2">
                                    <label class=" mb-0">Filter</label>
                                    <div >
                                        <input type="text" name="search" value="{{ $search ?? '' }}"
                                            placeholder="Search by name or force number" class="form-control"
                                            style="max-width: 300px; margin-right:10px;">
                                    </div>
                                    <div >
                                        <select onchange="this.form.submit()" class="form-select" name="platoon" >
                                            <option value="" selected disabled>Select Platoon</option>
                                            @for ($i = 1; $i < 15; $i++)
                                                <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                 <a href="{{ route('students.search_certificate', $company->id) }}" class="btn btn-secondary">Reset</a> 
                                </div>
                            </form>
                        </div>


                        <form action="{{ in_array($selectedSessionId, [4, 6]) ? route('final.generateTranscript') : route('final.generateCertificate') }}" method="POST" class="form-inline mb-4">
                            @csrf
                            <div class="card-header">
                                <i>Choose student(s) to print Certificate or Transcripts</i>
                                <button type="submit" class="btn btn-secondary" style="float:right">
                                    {{ in_array($selectedSessionId, [4, 6]) ? 'Print Transcript(s)' : 'Print Certificate(s)' }}
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="table-outer">
                                    <div class="table-responsive">
                                        <table class="table table-striped truncate m-0">
                                            <thead>
                                                <tr>
                                                    <th><input class="form-check-input select-all" type="checkbox" id="selectAll{{ $company->id }}"></th>
                                                    <th>No</th>
                                                    <th>Force Number</th>
                                                    <th>Name</th>
                                                    <th>Company</th>
                                                    <th>Platoon</th>
                                                    <th>Phone</th>
                                                    <th>Home Region</th>
                                                    <th width="280px">Certificate Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($company->students as $i => $student)
                                                    <tr>
                                                        <td>
                                                            <input class="form-check-input student-checkbox" type="checkbox"
                                                                name="selected_students[]" value="{{ $student->id }}">
                                                        </td>
                                                        <td>{{ $i + 1 }}</td>
                                                        <td>{{ $student->force_number ?? '' }}</td>
                                                        <td>{{ $student->first_name }} {{ $student->middle_name }}
                                                            {{ $student->last_name }}</td>
                                                        <td>{{ $student->company->name ?? '' }}</td>
                                                        <td>{{ $student->platoon }}</td>
                                                        <td>{{ $student->phone }}</td>
                                                        <td>{{ $student->home_region }}</td>
                                                        <td>
                                                            <a class="btn btn-sm {{ $student->transcript_printed ? 'btn-success' : 'btn-warning' }}"
                                                                href="#">
                                                                {{ $student->transcript_printed ? 'Printed' : 'Not Printed' }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    if ($isActive)
                        $foundActiveTab = true;
                @endphp
            @endforeach
        </div>


        <!-- {!! $students->links('pagination::bootstrap-5') !!} -->

        @if($students->isEmpty())
            <p>No Student found for the selected criterias.</p>
        @endif

@endsection


@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle "Select All" toggle
        document.querySelectorAll('.select-all').forEach(selectAll => {
            selectAll.addEventListener('click', function () {
                const currentTabPane = this.closest('.tab-pane');
                const checkboxes = currentTabPane.querySelectorAll('.student-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });

        // Handle individual checkbox changes
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.addEventListener('click', function () {
                const currentTabPane = this.closest('.tab-pane');
                const checkboxes = currentTabPane.querySelectorAll('.student-checkbox');
                const selectAll = currentTabPane.querySelector('.select-all');

                if (!this.checked) {
                    // If one is unchecked, uncheck the "Select All"
                    if (selectAll) selectAll.checked = false;
                } else {
                    // If all are checked, check the "Select All"
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    if (selectAll) selectAll.checked = allChecked;
                }
            });
        });
    });
</script>

@endsection