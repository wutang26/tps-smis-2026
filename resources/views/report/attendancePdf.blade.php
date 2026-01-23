<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ public_path('css/main.min.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/custom.css') }}">
    <link rel="stylesheet" href="/tps-smis/resources/assets/fonts/bootstrap/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/main.min.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/custom.css" />
    <title>Attendance Report</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {    
        border: 1px solid #000;
        padding: 2px 2px;

    }

    th {
        background-color: #f2f2f2;
        text-align: left;
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

    .page-break {
        page-break-after: always;
    }


    * {
        pointer-events: none;
        /* Disable all pointer interactions */
    }

    @page {
        size: A4 potrait;
        margin: 0 50px 5px 50px;
        /* top, right, bottom, left */
    }

    body {
        margin: 0;
        padding: 0;
        /* important: don't use padding, it breaks DomPDF layout */
    }

    .top-container {
        margin-top: 0;
        /* no need for extra spacing if @page works */
        text-align: center;
    }

    .top-container h4 {
        margin: 0;
    }

    .top-container img {
        height: 40px;
        width: 30px;
        margin: 4px 0;
    }

    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 1px;
        font-size: 10px;
        /* word-wrap: break-word; */

    }

    th {
        background-color: #f0f0f0;
    }


    /* On screen (optional) */
    table {
        font-size: 11px;
        table-layout: fixed;
        width: 100%;
    }

    th,
    td {
        word-wrap: break-word;
        text-align: center;
    }

    th.vertical {
        height: 70px;
        width: 30px;
        padding: 0;
        vertical-align: bottom;
        text-align: right;
        position: relative;
    }

    th.vertical>div {
        transform: rotate(-90deg);
        transform-origin: center center;
        position: absolute;
        top: 50%;
        left: 50%;
        white-space: nowrap;
        transform: translate(-50%, -50%) rotate(-90deg);
    }

    .tight-header {
        margin-bottom: 4px !important;
        margin-top: 1px !important;
        padding: 0 !important;
    }

    .tight-table {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('resources/assets/images/logo.png') }}" alt="Watermark">
    </div>
    <center>
        <div class="top-container" style="margin-top: 30px; text-align: center;">
            <h4 class="tight-header">TANZANIA POLICE SCHOOL - MOSHI</h4>

            <img src="{{ asset('resources/assets/images/logo.png') }}" style="height: 40px; width: 30px; margin: 0 0;"
                alt="Police Logo">

            <h4> {{ strtoupper($attendenceType-> name. ' state')}} {{\Carbon\Carbon::now()->format('d F, Y')}}  {{ strtoupper($company->description) }}</h4>
        </div>
        @if (count($sessionProgrammeAttendance) === 0)
        <h4 class="tight-header">No attedance recorded for this Company.</h4>
        @endif
        @php
        $grandTotals = [
        'present' => 0,
        'sentry' => 0,
        'absent' => 0,
        'adm' => 0,
        'ed' => 0,
        'safari' => 0,
        'dog_and_horse' => 0,
        'michezo' => 0,
        'ujenzi' => 0,
        'usafi' => 0,
        'off' => 0,
        'course' => 0,
        'mess' => 0,
        'sick' => 0,
        'kazini' => 0,
        'it' => 0,
        'msikitini' => 0,
        'field' => 0,
        'ushoni' => 0,
        'male' => 0,
        'female' => 0,
        'total' => 0,
        ];
        @endphp

        @foreach ($sessionProgrammeAttendance as $session)

        <h4 class="tight-header">{{ $session['session_programme']['programme_name'] }}</h4>
        <table class="table-sm table tight-table">
            <thead>
                <tr>
                    <th class="vertical">
                        <div>PLATOON</div>
                    </th>
                    <th class="vertical">
                        <div>PRESENT</div>
                    </th>
                    <th class="vertical">
                        <div>SENTRY</div>
                    </th>
                    <th class="vertical">
                        <div>ADM</div>
                    </th>
                    <th class="vertical">
                        <div>ED</div>
                    </th>
                    @if ($attendenceType->id != 1 &  $attendenceType->id != 3)
                    
                    <th class="vertical">
                        <div>DOG&HORSE</div>
                    </th>
                    <th class="vertical">
                        <div>MICHEZO</div>
                    </th>
                    <th class="vertical">
                        <div>UJENZI</div>
                    </th>
                    <th class="vertical">
                        <div>USAFI</div>
                    </th>
                    <th class="vertical">
                        <div>OFF</div>
                    </th>
                    <th class="vertical">
                        <div>COURSE</div>
                    </th>
                    <th class="vertical">
                        <div>MESS</div>
                    </th>
                    <th class="vertical">
                        <div>ZAHANATI</div>
                    </th>
                    <th class="vertical">
                        <div>USHONI</div>
                    </th>
                    <th class="vertical">
                        <div>IT</div>
                    </th>
                    <th class="vertical">
                        <div>MSIKITINI</div>
                    </th>
                    @endif
                    <th class="vertical">
                        <div>FIELD</div>
                    </th>
                    <th class="vertical">
                        <div>KAZINI</div>
                    </th>
                    <th class="vertical">
                        <div>ME</div>
                    </th>
                    <th class="vertical">
                        <div>KE</div>
                    </th>
                    <th class="vertical">
                        <div>JUMLA</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                $totals = [
                'present' => 0,
                'sentry' => 0,
                'absent' => 0,
                'adm' => 0,
                'ed' => 0,
                'safari' => 0,
                'dog_and_horse' => 0,
                'michezo' => 0,
                'ujenzi' => 0,
                'usafi' => 0,
                'off' => 0,
                'course' => 0,
                'mess' => 0,
                'sick' => 0,
                'kazini' => 0,
                'it' => 0,
                'msikitini' => 0,
                'field' => 0,
                'ushoni' => 0,
                'male' => 0,
                'female' => 0,
                'total' => 0,
                ];
                @endphp

                @foreach($session['attendances'] as $attendance)
                <tr>
                    <td>{{ $attendance->platoon->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->present === 0 ? '' : $attendance->present }}</td>
                    <td>{{ $attendance->sentry === 0 ? '' : $attendance->sentry }}</td>
                    <td>{{ $attendance->adm === 0 ? '' : $attendance->adm }}</td>
                    <td>{{ $attendance->ed === 0 ? '' : $attendance->ed }}</td>
                    @if ($attendenceType->id != 1 &  $attendenceType->id != 3)
                                        
                    <td>{{ $attendance->dog_and_horse === 0 ? '' : $attendance->dog_and_horse }}</td>
                    <td>{{ $attendance->michezo === 0 ? '' : $attendance->michezo }}</td>
                    <td>{{ $attendance->ujenzi === 0 ? '' : $attendance->ujenzi }}</td>
                    <td>{{ $attendance->usafi === 0 ? '' : $attendance->usafi }}</td>
                    <td>{{ $attendance->off === 0 ? '' : $attendance->off }}</td>
                    <td>{{ $attendance->course === 0 ? '' : $attendance->course }}</td>
                    <td>{{ $attendance->mess === 0 ? '' : $attendance->mess }}</td>
                    <td>{{ $attendance->sick === 0 ? '' : $attendance->sick }}</td>
                    <td>{{ $attendance->ushoni === 0 ? '' : $attendance->ushoni }}</td>
                    <td>{{ $attendance->it === 0 ? '' : $attendance->it }}</td>
                    <td>{{ $attendance->msikitini === 0 ? '' : $attendance->msikitini }}</td>
                    @endif
                    <td>{{ $attendance->field === 0 ? '' : $attendance->field }}</td>
                    <td>{{ $attendance->kazini === 0 ? '' : $attendance->kazini }}</td>
                    <td>{{ $attendance->male === 0 ? '' : $attendance->male }}</td>
                    <td>{{ $attendance->female === 0 ? '' : $attendance->female }}</td>
                    <td>{{ $attendance->total === 0 ? '' : $attendance->total }}</td>
                </tr>
                @php
                // Calculate the totals for each column
                $totals['present'] += $attendance->present;
                $totals['sentry'] += $attendance->sentry;
                $totals['absent'] += $attendance->absent;
                $totals['adm'] += $attendance->adm;
                $totals['ed'] += $attendance->ed;
                $totals['safari'] += $attendance->safari;
                $totals['dog_and_horse'] += $attendance->dog_and_horse ?? 0;
                $totals['michezo'] += $attendance->michezo ?? 0;
                $totals['ujenzi'] += $attendance->ujenzi ?? 0;
                $totals['usafi'] += $attendance->usafi ?? 0;
                $totals['off'] += $attendance->off;
                $totals['course'] += $attendance->course ?? 0;
                $totals['mess'] += $attendance->mess;
                $totals['sick'] += $attendance->sick ?? 0;
                $totals['kazini'] += $attendance->kazini;
                $totals['it'] += $attendance->it ?? 0;
                $totals['msikitini'] += $attendance->msikitini ?? 0;
                $totals['field'] += $attendance->field ?? 0;
                $totals['ushoni'] += $attendance->ushoni ?? 0;
                $totals['male'] += $attendance->male;
                $totals['female'] += $attendance->female;
                $totals['total'] += $attendance->total;

                $grandTotals['present'] += $attendance->present;
                $grandTotals['sentry'] += $attendance->sentry;
                $grandTotals['absent'] += $attendance->absent;
                $grandTotals['adm'] += $attendance->adm;
                $grandTotals['ed'] += $attendance->ed;
                $grandTotals['safari'] += $attendance->safari;
                $grandTotals['dog_and_horse'] += $attendance->dog_and_horse ?? 0;
                $grandTotals['michezo'] += $attendance->michezo ?? 0;
                $grandTotals['ujenzi'] += $attendance->ujenzi ?? 0;
                $grandTotals['usafi'] += $attendance->usafi ?? 0;
                $grandTotals['off'] += $attendance->off;
                $grandTotals['course'] += $attendance->course ?? 0;
                $grandTotals['mess'] += $attendance->mess;
                $grandTotals['sick'] += $attendance->sick ?? 0;
                $grandTotals['kazini'] += $attendance->kazini;
                $grandTotals['it'] += $attendance->it ?? 0;
                $grandTotals['msikitini'] += $attendance->msikitini ?? 0;
                $grandTotals['field'] += $attendance->field ?? 0;
                $grandTotals['ushoni'] += $attendance->ushoni ?? 0;
                $grandTotals['male'] += $attendance->male;
                $grandTotals['female'] += $attendance->female;
                $grandTotals['total'] += $attendance->total;
                @endphp

                @endforeach
                <tr>
                    <th><strong>TOTAL</strong></th>
                    <th>{{ $totals['present'] }}</th>
                    <th>{{ $totals['sentry'] }}</th>
                    <th>{{ $totals['adm'] }}</th>
                    <th>{{ $totals['ed'] }}</th>
                    @if ($attendenceType->id != 1 &  $attendenceType->id != 3)
                    <th>{{ $totals['dog_and_horse'] }}</th>
                    <th>{{ $totals['michezo'] }}</th>
                    <th>{{ $totals['ujenzi'] }}</th>
                    <th>{{ $totals['usafi'] }}</th>
                    <th>{{ $totals['off'] }}</th>
                    <th>{{ $totals['course'] }}</th>
                    <th>{{ $totals['mess'] }}</th>
                    <th>{{ $totals['sick'] }}</th>
                    <th>{{ $totals['ushoni'] }}</th>
                    <th>{{ $totals['it'] }}</th>
                    <th>{{ $totals['msikitini'] }}</th>
                    @endif
                    <th>{{ $totals['field'] }}</th>
                    <th>{{ $totals['kazini'] }}</th>
                    <th>{{ $totals['male'] }}</th>
                    <th>{{ $totals['female'] }}</th>
                    <th>{{ $totals['total'] }}</th>
                </tr>

            </tbody>
        </table>
        @endforeach
        @if (count($sessionProgrammeAttendance) != 0)
        <h4 class="tight-header">JUMLA KUU</h4>
        <table class="table-sm table tight-table">
            <thead>
                <tr>
                    <th class="vertical">
                        <div></div>
                    </th>
                    <th class="vertical">
                        <div>PRESENT</div>
                    </th>
                    <th class="vertical">
                        <div>SENTRY</div>
                    </th>
                    <th class="vertical">
                        <div>ADM</div>
                    </th>
                    <th class="vertical">
                        <div>ED</div>
                    </th>
                    @if ($attendenceType->id != 1 &  $attendenceType->id != 3)
                    <th class="vertical">
                        <div>DOG&HORSE</div>
                    </th>
                    <th class="vertical">
                        <div>MICHEZO</div>
                    </th>
                    <th class="vertical">
                        <div>UJENZI</div>
                    </th>
                    <th class="vertical">
                        <div>USAFI</div>
                    </th>
                    <th class="vertical">
                        <div>OFF</div>
                    </th>
                    <th class="vertical">
                        <div>COURSE</div>
                    </th>
                    <th class="vertical">
                        <div>MESS</div>
                    </th>
                    <th class="vertical">
                        <div>ZAHANATI</div>
                    </th>
                    <th class="vertical">
                        <div>USHONI</div>
                    </th>
                    <th class="vertical">
                        <div>IT</div>
                    </th>
                    <th class="vertical">
                        <div>MSIKITINI</div>
                    </th>
                    @endif
                    <th class="vertical">
                        <div>FIELD</div>
                    </th>
                    <th class="vertical">
                        <div>KAZINI</div>
                    </th>
                    <th class="vertical">
                        <div>ME</div>
                    </th>
                    <th class="vertical">
                        <div>KE</div>
                    </th>
                    <th class="vertical">
                        <div>JUMLA</div>
                    </th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>TOTAL</th>
                    <td>{{ $grandTotals['present'] }}</td>
                    <td>{{ $grandTotals['sentry'] }}</td>
                    <td>{{ $grandTotals['adm'] }}</td>
                    <td>{{ $grandTotals['ed'] }}</td>
                    @if ($attendenceType->id != 1 &  $attendenceType->id != 3)
                    <td>{{ $grandTotals['dog_and_horse'] }}</td>
                    <td>{{ $grandTotals['michezo'] }}</td>
                    <td>{{ $grandTotals['ujenzi'] }}</td>
                    <td>{{ $grandTotals['usafi'] }}</td>
                    <td>{{ $grandTotals['off'] }}</td>
                    <td>{{ $grandTotals['course'] }}</td>
                    <td>{{ $grandTotals['mess'] }}</td>
                    <td>{{ $grandTotals['sick'] }}</td>
                    <td>{{ $grandTotals['ushoni'] }}</td>
                    <td>{{ $grandTotals['it'] }}</td>
                    <td>{{ $grandTotals['msikitini'] }}</td>
                    @endif
                    <td>{{ $grandTotals['field'] }}</td>
                    <td>{{ $grandTotals['kazini'] }}</td>
                    <td>{{ $grandTotals['male'] }}</td>
                    <td>{{ $grandTotals['female'] }}</td>
                    <td>{{ $grandTotals['total'] }}</td>
                </tr>

            </tbody>
        </table>
        @endif
        @if(count($sick_students))
        <br>
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <tbody>
                @foreach ($sick_students->chunk(2) as $chunk)
                <tr>
                    @foreach ($chunk as $sick_student)
                    <td style="padding: 4px; width: 50%; text-align: left;">
                        {{ $sick_student->excuse_type_id == 3 ? 'ADM' : 'ED' }}
                        {{ $sick_student->student->force_number }}
                        {{ $sick_student->student->first_name }}
                        {{ $sick_student->student->last_name }}
                        PLT {{ $sick_student->student->platoon }}
                    </td>
                    @endforeach

                    @if ($chunk->count() < 2) <td
                        style="padding: 4px; border: 1px solid #ccc; width: 50%; text-align: left;">
                        </td>
                        @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @php
        $duty = $company->teacherOnDuties()->whereNull('end_date')->first();
        $teacher = $duty ? $duty->staff : null;
        @endphp
        <p>........................................................</p>
        @if($teacher)
        <div style="width: 100%; display: flex; justify-content: space-between; font-weight: bold;">
            <span>DUTY NCO: {{ $teacher->forceNumber }} {{ $teacher->rank }} {{ $teacher->firstName }}</span>
            <span style="margin-left: 50px;">MOBILE: {{ $teacher->phoneNumber }}</span>
        </div>
        @else
        <div style="font-weight: bold;">
            DUTY NCO: Not Assigned<br>
            MOBILE: Not Available
        </div>
        @endif
        <p>........................................................</p>

        @foreach ($company->staffs as $staff)
        @if($staff->user()->role('OC Coy')->get()->isNotEmpty())
        <div style="width: 100%; display: flex; justify-content: space-between; font-weight: bold;">
            <span>OC: {{ $staff->forceNumber }} {{ $staff->rank }} {{ $staff->firstName }} {{ $staff->lastName }}</span>
            <span style="margin-left: 50px;">MOBILE: {{ $staff->phoneNumber }}</span>
            <p>........................................................</p>
        </div>
        @endif
        @endforeach
        </div>
    </center>
</body>

</html>