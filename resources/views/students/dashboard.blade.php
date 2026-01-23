@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Dashboard</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<div>
    <h3>Announcements</h3>
    <!-- <p> 1. <i>Second Semister exams will start at 3rd March,2025.</i></p>
    <h6>Anounced by <i class="primary" style="color: blue;">Staff Staff</i></h6> -->

    No new Announcements
</div>
<div class="card mb-4">
    <div class="card-body back">
        <div class="row gx-4">
            <div class="col-10">

                <table class="table table-borderless">
                    <tr>
                        <td>Force Number: </td>
                        <td>{{$user->student->force_number }}</td>
                    </tr>
                    <tr>
                        <td>Full name: </td>
                        <td>{{$user->student->first_name}} {{$user->student->middle_name}} {{$user->student->last_name}}
                        </td>
                    </tr>
                    <tr>
                        <td>Rank: </td>
                        <td>{{$user->student->rank}}</td>
                    </tr>
                    @if ($user->student->programme)
                    <tr>
                        <td>Programme: </td>
                        <td>{{$user->student->programme->programmeName}}</td>
                    </tr>
                    @endif

                </table>
                <div style="margin-left: 5%;">
                    <h5>Registered Courses: </h5>
                    @if ($user->student->programme)
                        <div class="table-responsive">
                            <?php    $i = 0; ?>
                            <table class="table table-striped truncate m-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Credit</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->student->programme->courses as $course)
                                        <tr>
                                            <td>{{++$i}}.</td>
                                            <td>{{$course->courseCode}}</td>
                                            <td>{{$course->courseName}}</td>
                                            <td>{{$course->pivot->course_type}}</td>
                                            <td>{{$course->pivot->credit_weight}}</td>
                                            <td>{{$course->department->departmentName}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            @endsection