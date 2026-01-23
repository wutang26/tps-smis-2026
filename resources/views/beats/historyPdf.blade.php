<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Beats Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
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
            padding: 20px; /* You can adjust the padding value as needed */
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
            </div>
            <h5>BASIC RECRUIT COURSE NO.01.2024/2025 {{$report['company_name']}} COY</h5>
            <h5>BEAT REPORT {{\Carbon\Carbon::today()->format('d-m-Y')}}</h5>
            <div class="table-container">
                
            @php
            $data = $report['data'];
            @endphp
            <table  class="table table-striped truncate m-0">
                <thead>
                    @php
                    $i = 0;
                    @endphp

                </thead>
                <tbody>

                    <tr style="">
                        <td>{{++$i}}</td>
                        <td>Total Students</td>
                        <td>{{ $data['totalStudents'] }}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Eligible Students for beat</td>
                        <td>{{ $data['totalEligibleStudents'] }}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Ineligible Students for beat</td>
                        <td>{{ $data['totalIneligibleStudents'] }}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Eligible Students percentage</td>
                        <td> {{$data['eligibleStudentsPercent']}}%</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Ineligible Students percentage</td>
                        <td> {{$data['InEligibleStudentsPercent']}}%</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Guard Areas</td>
                        <td> {{$data['guardAreas']}}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Patrol Areas</td>
                        <td> {{$data['patrolAreas']}}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Students Required Per Day</td>
                        <td> {{$data['number_of_guards']}}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Students Reserved  Per Day</td>
                        <td> 10</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Estimated Number of Days per Round</td>
                        <td> {{ $data['days_per_round'] }}</td>
                    </tr>
                </tbody>
            </table>

            <table  class="table mb-4">
                <thead>
                    <tr colspan="3"></tr>
                    <tr>
                        <th>Current round</th>
                        <th>Attained Current Round</th>
                        <th>Exceeded Current Round</th>
                        <th>Not Attained Current Round</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$data['current_round']}}</td>
                        <td>{{$data['attained_current_round']}}</td>
                        <td>{{$data['exceededAttained_current_round']}}</td>
                        <td>{{$data['NotAttained_current_round']}}</td>

                    </tr>
                </tbody>
            </table>
            <div>
                <h2>Ineligible Students Based on Vitengo</h2>
                @foreach ($data['vitengo'] as $kitengo)
                @if(count($kitengo['students']) > 0)
                <h2>{{ $kitengo['name'] }}</h2>
                <table  class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Names</th>

                            <th>Platoon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $n =0; @endphp
                        @foreach ($kitengo['students'] as $student)
                        <tr>
                            <td>{{++$n}}</td>
                            <td>{{$student->first_name}} {{$student->last_name}}</td>
                            <td>{{$student->platoon}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endforeach

                <h2>EMERGENCE</h2>
                <table class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Names</th>
                            <th>Reason</th>
                            <th>Platoon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $n =0; @endphp
                        @foreach ($data['emergencyStudents'] as $student)
                        <tr>
                            <td>{{++$n}}</td>
                            <td>{{$student->first_name}} {{$student->last_name}}</td>
                            <td>{{$student->beat_emergency}}</td>
                            <td>{{$student->platoon}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h4>RESERVED AND REPLACED STUDENTS</h4>
                <table style="width: 100%" class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="40%">Names</th>
                            <th width="45%">Reason</th>
                            <th width="10%">Platoon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $n =0; @endphp
                        @foreach ($data['reserveStudents'] as $reserve)
                        @if($reserve->replaced_student_id)

                        <tr>
                            <td>{{++$n}}</td>
                            <td>{{$reserve->student->first_name}} {{$reserve->student->last_name}}</td>
                            <td>{{$reserve->replacement_reason}}</td>
                            <td>{{$reserve->student->platoon}}</td>

                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </center>
    
</body>

</html>