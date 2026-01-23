@extends('layouts.main')

@section('style')
    <style>
        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            /* Adjust the spacing between elements as needed */
        }

        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Adjust the spacing between elements as needed */
        }
    </style>
@endsection

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                    <li class="breadcrumb-item active"><a href="#">Guards by Specific Date</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->
@endsection

@section('content')
    <div class="container">
        @include('layouts.sweet_alerts.index')
        @php
            $i = 1;
            $j = 1;
        @endphp
        <h2>Beats for {{ $date }}</h2>

        <div class="row form-container g-2 align-items-center">
            @can('beat-create')
                <div class="col-12 col-md-3">
                    <form id="generateBeatsForm" method="POST" action="{{ route('beats.fillBeats') }}" class="d-flex gap-2">
                        @csrf
                        <input type="date" name="date" min="{{ Carbon\Carbon::today()->format('Y-m-d') }}" class="form-control"required>
                        <button type="submit" class="btn btn-primary flex-grow-1">Generate</button>
                    </form>
                </div>
            @endcan

            <div class="col-12 col-md-3 ms-md-auto">
                <form action="{{ route('beats.byDate') }}" method="GET" class="d-flex gap-2">
                    <input type="date" name="date" class="form-control" value="{{ $date }}">
                    <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
                </form>
            </div>
        </div>


        <div id="responseMessage" class="mt-4"></div>

        <ul class="nav nav-tabs" id="companyTabs" role="tablist">
            @foreach($companies as $company)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($loop->first) active @endif" id="tab-{{ $company->id }}" data-bs-toggle="tab"
                        data-bs-target="#company-{{ $company->id }}" type="button" role="tab"
                        aria-controls="company-{{ $company->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $company->description }}
                    </button>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" id="companyTabContent">
            @foreach($companies as $company)
                <div class="tab-pane fade @if($loop->first) show active @endif" id="company-{{ $company->id }}" role="tabpanel"
                    aria-labelledby="tab-{{ $company->id }}">
                    <div class="card my-3">
                        <div class="card-header">
                            <div class="d-flex gap-2 justify-content-between">
                                <div>
                                    <h3>Guard Areas</h3><br><br>
                                    <!-- <a href="{{ route('beats.generatePDF', $company->id) }}" class="btn btn-secondary"> PDF</a> -->
                                    <form action="{{ route('beats.generatePDF', $company->id) }}" method="GET"
                                        class="form-inline mb-4" target="_blank">

                                        <input type="hidden" name="date"
                                            value="{{ $date ?? \Carbon\Carbon::today()->toDateString() }}">
                                        <button type="submit" class="btn btn-secondary">Download PDF</button>
                                    </form>
                                </div>
                                <a href="{{ route('beats.reserves', ['companyId' => $company->id, 'date' => $date]) }}"><button
                                        style="height: 30px;" class="btn btn-sm btn-success">Reserves</button></a>
                            </div>

                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Area</th>
                                        <th>Students</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->guardAreas as $area)
                                        @foreach($area->beats as $beat)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $beat->beatType->name }}</td>
                                                <td>
                                                    @if($beat->guardArea_id)
                                                        {{ $beat->guardArea->name ?? '' }}
                                                    @elseif($beat->patrolArea_id)
                                                        {{ $beat->patrolArea->start_area ?? '' }} - {{ $beat->patrolArea->end_area ?? '' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                                                        $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                                                    @endphp
                                                    @foreach($students as $student)
                                                        {{ $student->first_name }} {{ $student->last_name }} (PLT {{ $student->platoon }})
                                                        (Gender: {{ $student->gender }})<br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $beat->date }}</td>
                                                <td>{{ $beat->start_at }}</td>
                                                <td>{{ $beat->end_at }}</td>
                                                <td class="d-flex gap-2">
                                                    <a href="{{route('beats.create-exchange', $beat)}}"><button
                                                            class="btn btn-secondary btn-sm">Exchange</button></a>
                                                    <a href="{{route('beats.edit', $beat->id)}}"><button
                                                            class="btn btn-primary btn-sm">Edit</button></a>
                                                    <!-- <form action="{{ route('beats.destroy', $beat->id) }}" method="POST">
                                                                                                                                                                        @csrf @method('DELETE')
                                                                                                                                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                                                                                                                                    </form> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card my-3">
                        <div class="card-header">
                            <h3>Patrol Areas</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Area</th>
                                        <th>Students</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->patrolAreas as $area)
                                        @foreach($area->beats as $beat)
                                            <tr>
                                                <td>{{ $j++ }}</td>
                                                <td>{{ $beat->beatType->name }}</td>
                                                <td>
                                                    @if($beat->guardArea_id)
                                                        {{ $beat->guardArea->name ?? '' }}
                                                    @elseif($beat->patrolArea_id)
                                                        {{ $beat->patrolArea->start_area ?? '' }} - {{ $beat->patrolArea->end_area ?? '' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                                                        $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                                                    @endphp
                                                    @foreach($students as $student)
                                                        {{ $student->first_name }} {{ $student->last_name }} (PLT {{ $student->platoon }})
                                                        (Gender: {{ $student->gender }})<br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $beat->date }}</td>
                                                <td>{{ $beat->start_at }}</td>
                                                <td>{{ $beat->end_at }}</td>
                                                <td class="d-flex gap-2">
                                                    <a href="{{route('beats.create-exchange', $beat)}}"><button
                                                            class="btn btn-secondary btn-sm">Exchange</button></a>
                                                    <a href="{{route('beats.edit', $beat->id)}}"><button
                                                            class="btn btn-primary btn-sm">Edit</button></a>
                                                    <!-- <form action="{{ route('beats.destroy', $beat->id) }}" method="POST">
                                                                                                                                                                        @csrf @method('DELETE')
                                                                                                                                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                                                                                                                                    </form> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($companies->isEmpty())
                <p>No beats found for the selected date.</p>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('generateBeatsForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let date = formData.get('date');
                let responseMessage = document.getElementById('responseMessage');

                fetch("{{ route('beats.fillBeats') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        date: date
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        responseMessage.innerHTML = `<div class="alert alert-info">${data.message}</div>`;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        responseMessage.innerHTML = `<div class="alert alert-danger">An error occurred while generating beats.</div>`;
                    });
            });
        </script>
    @endpush
@endsection