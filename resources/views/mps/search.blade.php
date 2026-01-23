@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/mps/">Lock Up</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Search</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<!-- Success Message -->
@include('layouts.sweet_alerts.index')
<div class="card-body">
<form action="{{ url('/mps/search') }}" method="POST" class="d-flex justify-content-between mb-3">
    @csrf
    <div class="d-flex">
        <!-- Company Dropdown -->
        <select class="form-select me-2" name="company_id" id="companies" required>
            <option value="">Select Company</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>

        <!-- Platoon Dropdown (populated by JS) -->
        <select class="form-select me-2" name="platoon" id="platoons" required>
            <option value="">Select Platoon</option>
        </select>

        <!-- Name Input -->
        <input type="text" name="name" value="{{ request('name') }}" class="form-control me-2" placeholder="name (optional)">
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
</form>

    @if(isset($students))
    <?php $i = 0; ?>
            <div class="table-outer">
                <div class="table-responsive">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Platoon</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ ++$i }}.</td>
                                    <td>{{$student->force_number?? ''}} {{$student->rank}} {{$student->first_name}} {{$student->last_name}}</td>
                                    <td>{{$student->company->name}}</td>
                                    <td>{{$student->platoon}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">View</a>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $student->id ?? ''}}">
                                            Enter Details
                                        </button>
                                        <div class="modal fade" id="statusModal{{  $student->id ?? '' }}" tabindex="-1"
                                            aria-labelledby="statusModalLabel{{  $student->id ?? '' }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusModalLabel{{  $student->id ?? ''}}">Enter
                                                            Student Details for {{ $student->first_name }} {{ $student->last_name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{url('/mps/store/' . $student->id)}}" method="POST">
                                                            @csrf

                                                            @method('POST')

                                                            <div class="mb-3">
                                                                <label for="excuseType" class="form-label">Arrested At</label>
                                                                <input class="form-control" type="datetime-local" required
                                                                    name="arrested_at">
                                                            </div>
                                                            @error('arrested_at')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror

                                                            

                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Description</label>
                                                                <textarea class="form-control" id="" name="description" rows="3"
                                                                    required></textarea>
                                                            </div>
                                                            @error('description')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div style="display: flex; justify-content: flex-end; margin-right: 2px;">
                                                                <button type="submit" class="btn btn-primary">Save</button>
                                                            </div>
                                                            
                                                            
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
        </div>
    @else
        <h4>Please seearch the student.</h4>
    @endif
</div>
<script>
        const selectedCompanyId = "{{ request('company_id') }}";
    const selectedPlatoonId = "{{ request('platoon') }}";
    const companySelect = document.getElementById('companies');
    const platoonsSelect = document.getElementById('platoons');

    function fetchPlatoons(companyId, preselectId = null) {
        platoonsSelect.innerHTML = '<option value="">Loading...</option>';
        const url = '/tps-smis/platoons/' + companyId;

        fetch(url)
            .then(response => response.json())
            .then(platoons => {
                platoonsSelect.innerHTML = '<option value="">Select a platoon</option>';
                platoons.forEach(platoon => {
                    const option = document.createElement('option');
                    option.value = platoon.name;
                    option.text = platoon.name;

                    // Auto-select if it matches the previously selected platoon
                    if (platoon.id == preselectId) {
                        option.selected = true;
                    }

                    platoonsSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching platoons:', error);
                platoonsSelect.innerHTML = '<option value="">Error loading platoons</option>';
            });
    }

    // On company change
    companySelect.addEventListener('change', function () {
        const companyId = this.value;
        if (companyId) {
            fetchPlatoons(companyId);
        } else {
            platoonsSelect.innerHTML = '<option value="">Select a platoon</option>';
        }
    });

    // On page load, fetch if company is preselected
    document.addEventListener('DOMContentLoaded', function () {
        if (selectedCompanyId) {
            companySelect.value = selectedCompanyId;
            fetchPlatoons(selectedCompanyId, selectedPlatoonId);
        }
    });
</script>

@endsection