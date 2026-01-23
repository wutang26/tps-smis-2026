<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Statistics Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header, .footer { text-align: center; }
        .header img { width: 70px; height: 70px; }
        .footer { font-size: 10px; position: fixed; bottom: 10px; width: 100%; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 6px; text-align: center; font-size: 12px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Police Logo">
        <h2>SHULE YA  POLISI TANZANIA -TPS MOSHI</h2>
        <h4>Patient Statistics Report</h4>
        <p><strong>Timeframe:</strong> {{ ucfirst($timeframe) }}</p>
        @if($company_id)
            <p><strong>Company ID:</strong> {{ $company_id }}</p>
        @endif
        @if($platoon)
            <p><strong>Platoon:</strong> {{ $platoon }}</p>
        @endif
        <hr>
    </div>


    <!-- Summary: Students with ≥ 5 Excuse Types -->
    @if($frequentExcuses->isNotEmpty())
        <h3>Summary: Students with atleast 5 Excuse Types</h3>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Platoon</th>
                    <th>Total Excuses</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($frequentExcuses as $student)
                    <tr>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->platoon ?? '-' }}</td>
                        <td>{{ $student->excuse_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
    @endif

<!-- ✅ Summary Section -->
<h2>Summary</h2>
<h2>Total Patients Present:<strong>{{ $totalPatientsPresent }}</strong></h2>


     <!-- ✅ Total Patients Summary -->
     <h3>Patient Details</h3>

<table>
    <thead>
        <tr>
            <th>Total Patients</th>
            <th>Excuse Duty (ED)</th>
            <th>Light Duty (LD)</th>
            <th>Admitted (Referral & Internal)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>{{ $totalPatientsPresent }}</strong></td>
            <td><strong>{{ $excuseDutyCount }}</strong></td>
            <td><strong>{{ $lightDutyCount }}</strong></td>
            <td><strong>{{ $admittedCount }}</strong></td>
        </tr>
    </tbody>
</table>
<hr>


    @if($patients->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Platoon</th>
                    <th>Status</th>
                    <th>Excuse Type</th>
                    <th>Days of Rest</th>
                    <th>Date Admitted</th>
                    <th>End Date of Rest</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($patients as $patient)
                    <tr>
                        <td>{{ optional($patient->student)->first_name ?? '-' }}</td>
                        <td>{{ optional($patient->student)->last_name ?? '-' }}</td>
                        <td>{{ $patient->platoon ?? '-' }}</td>
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
    @else
        <p>No patients found for this timeframe.</p>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

</body>
</html>
