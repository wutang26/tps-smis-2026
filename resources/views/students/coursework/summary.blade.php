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
        bottom: 10px; /* Adjust as needed */
        right: 15px;  /* Adjust as needed */
    }
</style>
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Coursework Results</a></li>
                <li class="breadcrumb-item right-align"><a href="#" id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('style')
<style>
    .table-outer {
        overflow-x: auto;
    }
    .table thead th, .table tbody td {
        border: 1px solid #dee2e6;
    }
    .table tbody tr:last-child td {
        border-bottom: 1px solid #dee2e6;
    }

    .nd{
        margin-top:20px;
    }
</style>
@endsection
@section('content')
<!-- Row starts -->
<!-- Row starts -->
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
                @php
                $i=1;
                $j=1;
                @endphp
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" style="margin-left:10px" href="{{ route('students.coursework') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
                <h2>Coursework Summary</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>Course Name</th>
                        <td>{{ $result->course->courseName }}</td>
                    </tr>
                    <tr>
                        <th>Course Code</th>
                        <td>{{ $result->course->courseCode }}</td>
                    </tr>
                    <tr>
                        <th>Credits</th>
                        <td>{{ $result->programmeCourseSemester->credit_weight }}</td>
                    </tr>
                    <tr>
                        <th>Marks</th>
                        <td>{{ $result->score }}</td>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <td><?php if($result->score < 16){ ?> <span style="color:red">Fail</span> <?php }else{ echo "Pass"; }?> </td>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <td>{{ $result->semester->semester_name }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
<!-- Row ends -->
@endsection
