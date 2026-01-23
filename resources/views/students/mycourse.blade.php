@extends('layouts.main')

@section('style')
<style>
    .breadcrumb {
        display: flex;
        width: 100%;
    }
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    #date {
        position: absolute;
        bottom: 10px;
        right: 15px;
    }

    .table-outer {
        overflow-x: auto;
        margin-top: 30px;
    }

    .table thead th, .table tbody td {
        border: 1px solid #dee2e6;
    }

    .table tbody tr:last-child td {
        border-bottom: 1px solid #dee2e6;
    }

    .semester-title {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Registered Courses</a></li>
                <li class="breadcrumb-item right-align"><a href="#" id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')
<div class="row gx-4">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">
                @if (session('success'))
                    <div class="alert alert-success">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @php
                    // Group courses by semester ID
                    $groupedBySemester = $courses->groupBy(function ($course) {
                        return $course->pivot->semester_id;
                    })->sortKeys(); // Use sortKeysDesc() for reverse order
                @endphp

                @forelse ($groupedBySemester as $semesterId => $semesterCourses)
                    <div class="table-outer">
                        <div class="semester-title">Semester {{ $semesterId }}</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered truncate m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Course Type</th>
                                        <th>Credit Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($semesterCourses as $course)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $course->courseCode }}</td>
                                            <td>{{ $course->courseName }}</td>
                                            <td>{{ $course->pivot->course_type }}</td>
                                            <td>{{ $course->pivot->credit_weight }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">
                        No courses registered.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
