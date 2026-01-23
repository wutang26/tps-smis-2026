<!DOCTYPE html>
<html>
<head>
    <title>Guard & Patrol for {{ $company->name }} on {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color:black !important;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            margin-top: 6mm;
            margin-bottom: -20mm;
            margin-left: -5mm;
            margin-right: 15mm;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px; /* Reduce font size */
        }
        .table th, .table td {
            border: 1px solid black;
            padding: 2px 5px; /* Reduce padding */
            text-align: left;
            white-space: nowrap; /* Prevent text wrapping */
        }
        .table th {
            background-color: #f2f2f2;
            font-size: 16px;
        }
        .page-break {
            page-break-after: always;
        }

        .table td.platoon {
            text-align: center; /* Center platoon numbers */
            font-weight: bold;
        }

        .table-container {
            width: 100%;
            overflow-x: auto; /* Enable scrolling if needed */
        }

        @media print {
            .table {
                font-size: 12px; /* Further reduce for printing */
            }
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            width: 200px;
            height: 200px;
            opacity: 0.2;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .content {
            position: relative;
            z-index: 1;
        }
        
        body {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
        }
        .highlight {
            background-color: #e6f7ff;
        }

    </style>
</head>
<body>
    
<div class="watermark">
        <img src="{{ asset('resources/assets/images/logo.png') }}" alt="Watermark">
</div>
    <div class="container" style="margin-top:-60px;">
        <div class="header">
            <div style="text-align: center;">
                <img src="{{ asset('resources/assets/images/logo.png') }}"  style="height:60 !important; width:50" alt="Police Logoz">
            </div>

            @php
                $date = Carbon\Carbon::parse("$date")
            @endphp
            <!-- <h1><b>TANZANIA POLICE SCHOOL-MOSHI</b></h1> -->
            <h1 style="margin-top:-5px;">RATIBA YA MALINDO  {{ strtoupper($company->description) }}</h1>
            <!-- <h2 style="margin-top:-5px;">TAREHE {{ $date->format('d/m/Y')}}</h2> -->
            <h2 style="margin-top:-5px;">TAREHE 22/01/2026</h2>
             
        </div>

        @php
            $guardtimeSlots = [
                ['start_at' => '06:00:00', 'end_at' => '12:00:00'],
                ['start_at' => '12:00:00', 'end_at' => '18:00:00'],
                ['start_at' => '18:00:00', 'end_at' => '00:00:00'],
                ['start_at' => '00:00:00', 'end_at' => '06:00:00']
            ];

            $patroltimeSlots = [
                ['start_at' => '18:00:00', 'end_at' => '00:00:00'],
                ['start_at' => '00:00:00', 'end_at' => '06:00:00']
            ];

        @endphp

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ENEO LA LINDO</th>
                @foreach($guardtimeSlots as $slot)
                    <th>{{ $slot['start_at'] }} - {{ $slot['end_at'] }}</th>
                    <th>PLT</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($company->guardAreas as $area)
                @php
                    $maxStudents = 0;
                    $studentsBySlot = [];

                    foreach ($guardtimeSlots as $slot) {
                        $beat = $area->beats->firstWhere('start_at', $slot['start_at']);
                        $students = collect();

                        if ($beat && !empty($beat->student_ids)) {
                            $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                            if (is_array($studentIds) && count($studentIds) > 0) {
                                $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                            }
                        }

                        $studentsBySlot[$slot['start_at']] = $students;
                        $maxStudents = max($maxStudents, $students->count());
                    }
                @endphp

                @for ($i = 0; $i < $maxStudents; $i++)
                    <tr>
                        @if ($i === 0)
                            <td rowspan="{{ $maxStudents }}">{{ $area->name }}</td>
                        @endif
                        
                        @foreach($guardtimeSlots as $slot)
                            @php
                                $students = $studentsBySlot[$slot['start_at']] ?? collect();
                                $student = $students->get($i);
                                $prefix = $student ? ($student->gender === 'F' ? 'WRC' : 'RC') : ''; 
                                $platoon = $student ? str_pad($student->platoon, 2, '0', STR_PAD_LEFT) : '-';
                            @endphp

                            <!-- <td>{{ $student ? "{$student->force_number} {$prefix}  {$student->first_name}" : '-' }}</td> -->
                            <td>{{ $student ? "{$prefix}  {$student->first_name} {$student->last_name}" : '-' }}</td>
                            <td class="platoon">{{ $student ? $platoon : '-' }}</td>
                        @endforeach
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>



<div class="page-break"></div>
 <br>
 <br>

<div class="table-container" style="margin-top:-30px;"> 
    <!-- <center><h1>RATIBA YA DORIA {{ strtoupper($company->description) }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAREHE {{ $date->format('d/m/Y')}}</h1></center> -->
    <center><h1>RATIBA YA DORIA {{ strtoupper($company->description) }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAREHE 22/01/2026</h1></center>
    <table class="table">
        <thead>
            <tr>
                <th>ENEO LA DORIA</th>
                @foreach($patroltimeSlots as $slot)
                    <th>{{ $slot['start_at'] }} - {{ $slot['end_at'] }}</th>
                    <th>PLT</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($company->patrolAreas as $area)
                @php
                    $maxStudents = 0;
                    $studentsBySlot = [];

                    foreach ($patroltimeSlots as $slot) {
                        $beat = $area->beats->firstWhere('start_at', $slot['start_at']);
                        $students = collect();

                        if ($beat && !empty($beat->student_ids)) {
                            $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                            if (is_array($studentIds) && count($studentIds) > 0) {
                                $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                            }
                        }

                        $studentsBySlot[$slot['start_at']] = $students;
                        $maxStudents = max($maxStudents, $students->count());
                    }
                @endphp

                @for ($i = 0; $i < $maxStudents; $i++)
                    <tr>
                        @if ($i === 0)
                            <td rowspan="{{ $maxStudents }}">{{ $area->start_area }} - {{ $area->end_area }}</td>
                        @endif
                        
                        @foreach($patroltimeSlots as $slot)
                            @php
                                $students = $studentsBySlot[$slot['start_at']] ?? collect();
                                $student = $students->get($i);
                                $prefix = $student ? ($student->gender === 'F' ? 'WRC' : 'RC') : '';
                                $platoon = $student ? str_pad($student->platoon, 2, '0', STR_PAD_LEFT) : '-';
                            @endphp

                            <!-- <td>{{ $student ? "{$student->force_number} {$prefix} {$student->first_name}" : '-' }}</td> -->
                            <td>{{ $student ? "{$prefix} {$student->first_name} {$student->last_name}" : '-' }}</td>
                            <td class="platoon">{{ $student ? $platoon : '-' }}</td>
                        @endforeach
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>

 <!-- RESERVE SECTION -->
 <h3 style="text-align: center; margin-top: 10px;">RESERVE</h3>
    <table class="table">
        <tr>
            @php
                $reserves = \App\Models\BeatReserve::where('beat_date', $date)->where('company_id', $company->id)->get();
                $leader = \App\Models\BeatLeaderOnDuty::where('beat_date', $date)->where('company_id', $company->id)->get();
                $reserveCount = $reserves->count();                 
            @endphp

            @foreach($reserves as $index => $reserve)
                @php
                    $prefix = $reserve->student->gender === 'F' ? 'WRC' : 'RC';
                    $platoon = str_pad($reserve->student->platoon, 2, '0', STR_PAD_LEFT);
                @endphp

                @if ($index % 2 === 0)
                    </tr><tr>
                @endif

                <td width="10%" style="text-align:right">{{ $index + 1 }}</td>
                <td width="40%">{{ $reserve->student->force_number }} {{ $prefix }} {{ $reserve->student->first_name }} {{ $reserve->student->last_name }} - PLT {{ $platoon }}</td>
                
            @endforeach
        </tr>

    </table>
    <!-- LEADERS ON DUTY --> <br><br></br><br><br></br><br><br></br>
    <h3 style="margin-top: 5px; margin-right:50%;">LEADERS ON DUTY:</h3>
<!-- 
    @dump($leader->count()) -->

       <!-- <ul> 
            <li style="list-style-type: none; font-size: 16px; ">WRC DAINESS BENSON MWANDAMBO-A17</li> 
              <li style="list-style-type: none; font-size: 16px; ">RC SHARIFU HEMED  PLT 02 </li> 
        </ul>  -->
     
            @if($leader->isEmpty())
            <p>No leaders assigned On Duty.</p>
        @else
            @foreach($leader as $duty)
                @php
                    $prefix = $duty->student->gender === 'F' ? 'WRC' : ' RC';
                    $platoon = str_pad($duty->student->platoon, 2, '0', STR_PAD_LEFT);
                @endphp
                <span>{{ $duty->student->force_number }} {{ $prefix }} {{ $duty->student->first_name }} {{ $duty->student->last_name }} - PLT {{ $platoon }}</span><br>
            @endforeach
        @endif

</div>


<div class="page-break"></div>

<!-- <h1>Beats Summary for {{ $date->format('d/m/Y')}}</h1> -->
<h1>Beats Summary for 22/01/2026</h1>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Platoon</th>
                <th colspan="{{ count($summary) }}">Time Slots</th>
                <th rowspan="2">Total</th>
            </tr>
                <tr>
                    @foreach(array_keys($summary) as $timeSlot)
                        @php
                            list($startTime, $endTime) = explode(' - ', $timeSlot);
                        @endphp
                        <th>{{ \Carbon\Carbon::parse($startTime)->format('H:i') }} - {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}</th>
                    @endforeach

                </tr>
        </thead>
        <tbody>
            @php
                ksort($totalPlatoonCount);
            @endphp
            @foreach($totalPlatoonCount as $platoon => $total)
                <tr>
                    <td class="center"><strong>{{ $platoon }}</strong></td>
                    @foreach($summary as $timeSlot => $platoons)
                        <td>{{ $platoons[$platoon] ?? 0 }}</td>
                    @endforeach
                    <td class="highlight">{{ $total }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                @foreach($summary as $timeSlot => $platoons)
                    <th class="highlight">{{ array_sum($platoons) }}</th>
                @endforeach
                <th class="highlight">{{ array_sum($totalPlatoonCount) }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- <h1>Beats Summary for {{ $date->format('d/m/Y') }}</h1>
<table>
    <thead>
        <tr>
            <th rowspan="3">Platoon</th>
            <th colspan="{{ count($summary) * 2 }}">Time Slots</th>
            <th rowspan="3">Total</th>
        </tr>
        <tr>
            @foreach(array_keys($summary) as $timeSlot)
                @php
                    list($startTime, $endTime) = explode(' - ', $timeSlot);
                @endphp
                <th colspan="2">{{ \Carbon\Carbon::parse($startTime)->format('H:i') }} - {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach(array_keys($summary) as $timeSlot)
                <th>ME</th>
                <th>KE</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($totalPlatoonCount as $platoon => $totals)
            <tr>
                <td class="center"><strong>{{ htmlspecialchars((string)$platoon) }}</strong></td>
                @foreach($summary as $timeSlot => $platoons)
                    <td>{{ htmlspecialchars((string)($platoons[$platoon]['M'] ?? 0)) }}</td>
                    <td>{{ htmlspecialchars((string)($platoons[$platoon]['F'] ?? 0)) }}</td>
                @endforeach
                <td class="highlight">{{ htmlspecialchars((string)$totals) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            @foreach($summary as $timeSlot => $platoons)
                <th class="highlight">{{ htmlspecialchars((string)array_sum(array_column($platoons, 'M'))) }}</th>
                <th class="highlight">{{ htmlspecialchars((string)array_sum(array_column($platoons, 'F'))) }}</th>
            @endforeach
            <th class="highlight">{{ htmlspecialchars((string)array_sum(array_column($totalPlatoonCount, 'total'))) }}</th>
        </tr>
    </tfoot>
</table> -->



</body>
</html>  
