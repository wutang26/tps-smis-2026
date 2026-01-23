@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="">Beats</a></li>
                <li class="breadcrumb-item active"><a href="">Beat Report</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')

@if(session('success'))
<div style="color: green">{{ session('success') }}</div>
@endif



<div class="container">
<h1>Beats Report {{\Carbon\Carbon::today()->format('d-m-Y')}}</h1><br>


<!-- <div style="left:200px;" class="custom-tabs-container"> -->

    <!-- Report data in tabs -->
    <ul class="nav nav-tabs" id="customTab2" role="tablist">
        <?php
            $i = 0;
        ?>
        @foreach ($companies as $company)
        <li class="nav-item" role="presentation" style="font-size:22px">
            <a id="tab-one{{$company->name}}" data-bs-toggle="tab" href="#one{{$company->name}}" role="tab"
                aria-controls="one{{$company->name}}" aria-selected="true" @if ($i==0) class="nav-link active" @else
                class="nav-link" @endif> {{$company->description}} </a>
        </li>
        <?php    $i = +1; ?>
        @endforeach
    </ul>


    <div class="tab-content h-300">
        @for ($j = 0; $j < count($report); ++$j) <div id="one{{$report[$j]['company_name']}}" @if ($j==0)
            class="tab-pane fade show active" @else class="tab-pane fade" @endif role="tabpanel">
            @php
            $data = $report[$j]['data'];
            @endphp
            <div class="d-flex  justify-content-end">
                <a href="{{route('report.history',[ $report[$j]['company_id']])}}">
                    <button title="Download report" class="btn btn-sm btn-success"><i class="gap 2 bi bi-download"></i>
                        Report</button>
                </a>
            </div>
            <h2>{{$report[$j]['company_name']}} - Company Summary</h2>

            <table style="width: 100%" class="table table-striped truncate m-0">
                <thead>
                    @php
                    $i = 0;
                    @endphp

                </thead>
                <tbody>

                    <tr>
                        <td>{{++$i}}</td>
                        <td>Total Students</td>
                        <td>{{ $data['totalStudents'] }}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Eligible Students</td>
                        <td>{{ $data['totalEligibleStudents'] }}</td>
                    </tr>
                    <tr>
                        <td>{{++$i}}</td>
                        <td>Ineligible Students</td>
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
            <br>

            <h2>Round Attendance</h2>
            <table style="width: 100%" class="table mb-4">
                <thead>
                    <tr>
                        <th></th>
                        <th>Current round</th>
                        <th>Attained Current Round</th>
                        <th>Exceeded Current Round</th>
                        <th>Not Attained Current Round</th>
                        <th>Fasting Student</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td>{{$data['current_round']}}</td>
                        <td>{{$data['attained_current_round']}}</td>
                        <td>{{$data['exceededAttained_current_round']}}</td>
                        <td>{{$data['NotAttained_current_round']}}</td>
                        <td>{{$data['fastingStudentCount']}}</td>
                    </tr>
                </tbody>
            </table>
            <div>

                <h2>Ineligible Students Based on Vitengo</h2>
                @foreach ($data['vitengo'] as $kitengo)
                @if(count($kitengo['students']) > 0)
                <br>
                <div class="mb-3">
                    <h4>{{ $kitengo['name'] }}</h4>
                    </div>
                <table style="width: 100%" class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th width="5%">S/N</th>
                            <th width="85%">Names</th>
                            <th width="10%">Platoon</th>
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
                <br>
                <div class="mb-3">
                  <h4>EMERGENCE</h4>  
                </div>
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
                <div class="mb-3">
                    <h4>RESERVED AND REPLACED STUDENTS</h4>
                    </div>
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
    @endfor
</div>

@endsection