@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">View Course</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
@include('layouts.sweet_alerts.index')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('courses.index') }}"><i
                                    class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Course Name:</strong>
                            {{ $course->courseName }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Course Code:</strong>
                            {{ $course->courseCode }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Department:</strong>
                            {{ $departmentName[0] }}
                        </div>
                    </div>
                    @can('course-create')
                    <div class="d-flex justify-content-end">
                        <a href="{{route('assign.instructors.form',$course->id)}}" class="btn btn-sm btn-success">Assign
                            Instructors</a>
                    </div>
                    @endcan
                    @if ($course->instructors->isNotEmpty())
                    <h3>Instructors</h3><br><br>
                    <table class="table-sm table">
                        <thead>
                            <th>S/N</th>
                            <th>Names</th>
                            <th>Email</th>
                            @can('course-create')
                            <th>Actions</th>
                            @endcan()
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            @endphp
                            @foreach ($course->instructors as $user)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td class="d-flex justify-content-between">
                                    @can('user-profile')
                                    <a href="{{route('staff.profile',$user->id)}}" class="btn btn-sm btn-info">View
                                        Profile</a>
                                    @endcan
                                    @can('course-create')
                                    <form id="unassignFormId{{$user->id}}"
                                        action="{{route('unassign.course',$user->course_instructor->first()->id)}}"
                                        method="post">
                                        @csrf
                                        <button type="button"
                                            onclick="confirmAction('unassignFormId{{$user->id}}', 'Unassign Instructor', 'Instructor','Unassign')"
                                            class="btn btn-sm btn-danger">Unassign</button>
                                    </form>
                                    @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4 col-12">
        <div class="card mb-8">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal">
                                Enroll
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-1 mb-3">
                    <h3>Enrolled Sessions</h3>
                </div><br>
                <div>
                    <ol>
                        @foreach ($course->enrolledSession as $session)
                        <li>
                            <div class="d-flex justify-content-between gap-2">
                                <span>{{$session->sessionProgramme->session_programme_name}}</span>
                                <form id="unerollForm" action="{{route('enrollments.session.delete', $session->id)}}" method="post">
                                    @csrf
                                   <button type="button" class="btn btn-sm text-danger text-decoration-underline" onclick="confirmAction('unerollForm','Unenroll Session Programme','Do you want to unenroll Session Programme','Unenroll')"><strong>Unenroll</strong> </button> 
                                </form>                                
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="statusModalLabel">
                    Enroll session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('enrollments.session.store')}}" method="post">
                    @csrf
                    <input type="text" name="course_id" value="{{$course->id}}" hidden>
                    <label for="">Session Programme</label>
                    <select name="session_programme_id" class="form-control" id="" required>
                        <option disabled value="">session programme</option>
                        @foreach ($session_programmes as $session_programme)
                        <option value="{{$session_programme->id }}">{{$session_programme->session_programme_name}}
                        </option>
                        @endforeach
                    </select>
                    @error('session_programme_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <label for="">Semester</label>
                    <select name="semester_id" class="form-control" id="" required>
                        <option disabled value="">semester</option>
                        @foreach ($semesters as $semester)
                        <option value="{{$semester->id }}">{{$semester->semester_name}}
                        </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection