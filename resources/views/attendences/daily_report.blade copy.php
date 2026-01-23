<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $date }}-{{ $company->name }}</title>
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

        body {
            /* padding: 20px; */
            /* You can adjust the padding value as needed */
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('resources/assets/images/logo.png') }}" alt="Watermark">
    </div>
    <center>
        <h4>TANZANIA POLICE SCHOOL - MOSHI</h4>
        <div class="container" style="margin-top:-10px;">
            <div class="header">
                <div style="text-align: center;">
                    <img src="{{ asset('resources/assets/images/logo.png') }}" style="height:60 !important; width:50"
                        alt="Police Logo">
                </div>

                @php
                    use App\Models\Student;
                    $date = Carbon\Carbon::parse("$date")
                @endphp
                <h4>{{ strtoupper($attendenceType->name) }} DAILY STATE {{ strtoupper($company->description) }}</h4>
                    <h4>  DATE {{ $date->format('d/m/Y')}}</h4>
                <h4>BASIC RECRUIT COURSE No 1. 2024/2025</h4>
            </div>
            <div class="table-container">
                <table class="page-break table-sm">
                    <thead>
                        <tr>
                            <th>Platoon</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Sentry</th>
                            <th>Leave</th>
                            <th>Off</th>
                            <th>Mess</th>
                            <th>Kazini</th>
                            <th>Sick</th>
                            <th>L/Up</th>
                            <th>ME</th>
                            <th>KE</th>
                            <th>Jumla</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $absent_students = [];
                            $sentry_students = [];
                            $mess_students = [];
                            $off_students = [];
                            $safari_students = [];
                            $total_present = 0;
                            $total_absent = 0;
                            $total_sentry = 0;
                            $total_safari = 0;
                            $total_off = 0;
                            $total_lockUp = 0;
                            $total_kazini = 0;
                            $total_messy = 0;
                            $total_sick = 0;
                            $total_male = 0;
                            $total_female = 0;
                            $grand_total = 0;
                        @endphp
                        @foreach ($company->platoons as $platoon)
                                                @php
                                                        $selectedSessionId = session('selected_session');
                                                if (!$selectedSessionId)
                                                    $selectedSessionId = 1;
                                                    $attendance = $platoon->attendences()->whereDate('created_at', $date)->where('session_programme_id', $selectedSessionId)->where('attendenceType_id', $attendenceType->id)->get();
                                                    if (count($attendance) > 0) {
                                                        $total_present += $attendance[0]->present;
                                                        $total_absent += $attendance[0]->absent;
                                                        $total_sentry += $attendance[0]->sentry;
                                                        $total_messy += $attendance[0]->messy;
                                                        $total_lockUp += $attendance[0]->lockUp;
                                                        $total_kazini += $attendance[0]->kazini;
                                                        $total_off += $attendance[0]->off;
                                                        $total_sick += $attendance[0]->sick;
                                                        $total_safari += $attendance[0]->safari;
                                                        $total_male += $attendance[0]->male;
                                                        $total_female += $attendance[0]->female;
                                                        $grand_total += $attendance[0]->total;
                                                        $absent_ids = $attendance[0]->absent_student_ids !=null? json_decode( $attendance[0]->absent_student_ids):[];
                                                        $sentry_student_ids = $attendance[0]->mess_student_ids !=null? explode(",", $attendance[0]->sentry_student_ids):[];
                                                        $mess_student_ids = $attendance[0]->mess_student_ids !=null? explode(",", $attendance[0]->mess_student_ids): [];
                                                        $off_student_ids =$attendance[0]->off_student_ids !=null?  explode(",", $attendance[0]->off_student_ids): [];
                                                        $safari_student_ids = $attendance[0]->safari_student_ids !=null? explode(",", $attendance[0]->safari_student_ids): [];
                                                        

                                                        /**if (count($mess_student_ids) > 0) {
                                                            $mess_students = Student::whereIn('id', $mess_student_ids);
                                                        }
                                                        if (count($off_student_ids) > 0) {
                                                            $off_students = Student::whereIn('id', $off_student_ids);
                                                        }

                                                        if (count($safari_student_ids) > 0) {
                                                            $safari_students = Student::whereIn('id', $safari_student_ids);
                                                        }
                                                            **/
                                                        for ($i = 0; $i < count($absent_ids); ++$i) {
                                                            array_push($absent_students, Student::find($absent_ids[$i]));
                                                        }
                                                        for ($i = 0; $i < count($sentry_student_ids); ++$i) {
                                                            array_push($sentry_students, Student::find($sentry_student_ids[$i]));
                                                        }
                                                        
                                                        for ($i = 0; $i < count($mess_student_ids); ++$i) {
                                                         array_push($mess_students, Student::find($mess_student_ids[$i]));
                                                        }

                                                        for ($i = 0; $i < count($off_student_ids); ++$i) {
                                                            array_push($off_students, Student::find($off_student_ids[$i]));
                                                        }
                                                        for ($i = 0; $i < count($safari_student_ids); ++$i) {
                                                            array_push($safari_students, Student::find($safari_student_ids[$i]));
                                                        }
                                                        
                                                    }      
                                                @endphp
                                                <tr>
                                                    <td>{{ $platoon->name }}</td>
                                                    <td>{{ $attendance[0]->present ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->absent ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->sentry ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->safari ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->off ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->mess ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->kazini ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->sick ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->lockUp ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->male ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->female ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->total ?? '-' }}</td>
                                                </tr>
                        @endforeach
                        @php

                        @endphp
                        <tr style="font-weight: bold;">
                            <td>JUMLA</td>
                            <td>{{ $total_present }}</td>
                            <td>{{ $total_absent }}</td>
                            <td>{{ $total_sentry }}</td>
                            <td>{{ $total_safari }}</td>
                            <td>{{ $total_off }}</td>
                            <td>{{ $total_messy }}</td>
                            <td>{{ $total_kazini }}</td>
                            <td>{{ $total_sick }}</td>
                            <td>{{ $total_lockUp }}</td> 
                            <td>{{ $total_male }}</td>
                            <td>{{ $total_female }}</td>
                            <td>{{ $grand_total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </center>
    @if (count($sentry_students)>0)
    <div class="table-container" style="width: 75%;">
        <center>
            <h4>Sentry Students</h4>
        </center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < count($sentry_students); $i++)
                    <tr>
                    <td>{{ $i + 1 }}</td>
                        <td>{{ $sentry_students[$i]->first_name }} {{ $sentry_students[$i]->middle_name }}
                            {{ $sentry_students[$i]->last_name }}</td>
                        <td>{{ $sentry_students[$i]->platoon }}</td>
                    </tr>
                @endfor

            </tbody>
        </table>

    </div>@endif
    @if (count($mess_students)>0)
    <div class="table-container" style="width: 75%;">
        <center>
            <h4>Mess Students</h4>
        </center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < count($mess_students); $i++)
            @if ($mess_students[$i] !== null)
                    <tr>
                    <td>{{ $i + 1 }}</td>
                        <td>{{ $mess_students[$i]->first_name }} {{ $mess_students[$i]->middle_name }} {{ $mess_students[$i]->last_name }}</td>
                        <td>{{ $mess_students[$i]->platoon }}</td>
                    </tr>
                    @endif
                @endfor

            </tbody>
        </table>

    </div>
    @endif
    @if (count($safari_students)>0)
    <div class="table-container" style="width: 75%;">
        <center>
            <h4>Safari Students</h4>
        </center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < count($safari_students); $i++)
            @if ($safari_students[$i] !== null)
                    <tr>
                    <td>{{ $i + 1 }}</td>
                        <td>{{ $safari_students[$i]->first_name }} {{ $safari_students[$i]->middle_name }} {{ $safari_students[$i]->last_name }}</td>
                        <td>{{ $safari_students[$i]->platoon }}</td>
                    </tr>
                    @endif
                @endfor

            </tbody>
        </table>

    </div>
    @endif
    @if (count($off_students)>0)
    <div class="table-container" style="width: 75%;">
        <center>
            <h4>Off Students</h4>
        </center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < count($off_students); $i++)
            @if ($off_students[$i] !== null)
                    <tr>
                    <td>{{ $i + 1 }}</td>
                        <td>{{ $off_students[$i]->first_name }} {{ $off_students[$i]->middle_name }} {{ $off_students[$i]->last_name }}</td>
                        <td>{{ $off_students[$i]->platoon }}</td>
                    </tr>
                    @endif
                @endfor

            </tbody>
        </table>

    </div>
    @endif
    @if (count($absent_students)>0)
    <div class="table-container" style="width: 75%;">
        <center>
            <h4>Absent Students</h4>
        </center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>

                @for ($i = 0; $i < count($absent_students); $i++)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $absent_students[$i]->first_name }} {{ $absent_students[$i]->middle_name }}
                            {{ $absent_students[$i]->last_name }}</td>
                        <td>{{ $absent_students[$i]->platoon }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div
    @endif

    @if ($sick_students -> isNotEmpty())
    <div class="table-container" style="width: 75%;">
        <center>
            <h4>Sick Students</h4>
        </center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Rest days</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < count($sick_students); $i++)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $sick_students[$i]->first_name }} {{ $sick_students[$i]->middle_name }}
                            {{ $sick_students[$i]->last_name }}</td>
                            <td>{{ $sick_students[$i]->sick->last()->rest_days }}</td>
                        <td>{{ $sick_students[$i]->platoon }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    @endif

    
</body>

</html>