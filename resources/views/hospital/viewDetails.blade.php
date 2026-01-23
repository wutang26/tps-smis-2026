@extends('layouts.main')

@section('content')
<div class="col-sm-12">

    <!-- Close Button -->
    <div class="d-flex justify-content-end">
        <a href="{{ url()->previous() }}" class="text-danger fs-3" style="text-decoration: none;">&times;</a>
    </div>

    <!-- Statistics Header -->
    <div class="card mb-4">
        <center><h3>Patients - {{ ucfirst($timeframe) }} View</h3></center>
    </div>

    <!-- Download Report Section -->
    <div class="card p-3 mb-4">
        <h5><strong>Generate Report</strong></h5>
        <p>Download a PDF report for the selected timeframe.</p>
        
        <!-- Minimized Button -->
        <div class="d-flex">
        <a href="{{ route('statistics.download', ['timeframe' => $timeframe, 'company_id' => $company_id, 'platoon' => $platoon]) }}"
        class="btn btn-danger btn-sm">
                Download PDF
            </a>
        </div>
    </div>

    <!-- Patient Table Section -->
    <div class="card p-3">
        <h5><strong>Patient List</strong></h5>
        
        @if($patients->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Platoon</th>
                            <th>Status</th>
                            <th>Excuse Type</th>
                            <th>Days of Rest</th>
                            <th>Date Attended</th>
                            <th>End Date of Rest</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <td>{{ optional($patient->student)->first_name ?? '-' }}</td>
                                <td>{{ optional($patient->student)->last_name ?? '-' }}</td>
                                <td>{{ $patient->company->name ?? '-' }} -{{ $patient->platoon ?? '-' }}</td>
                                <td>{{ $patient->status ?? '-' }}</td>
                                <td>{{ optional($patient->excuseType)->excuseName ?? '-' }}</td>
                                <td>{{ $patient->rest_days ?? '-' }}</td>
                                <td>{{ $patient->created_at ?? '-' }}</td>
                                <td>
                                   
                                   @if(optional($patient->excuseType)->excuseName === 'Admitted')
                                   {{ $patient->released_at ?? 'Not yet discharged' }}
                                   @elseif (!empty($patient->rest_days) && !empty($patient->created_at))
                                  {{ \Carbon\Carbon::parse($patient->created_at)->addDays($patient->rest_days)->format('Y-m-d') }}
                                  @else
                                    -
                                   @endif


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-danger">No patients found for this timeframe.</p>
        @endif
    </div>

</div>
@endsection


