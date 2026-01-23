@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Coursework Results</a></li>
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
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered truncate m-0">
                            <thead>
                                <tr>
                                    <th colspan="6">Semester One</th>
                                </tr>
                                <tr>
                                    <th scope="col" width="1%">S/N</th>
                                    <th scope="col" width="15%">Course Code</th>
                                    <th scope="col" width="50%">Course Name</th>
                                    <th scope="col" width="10%">Credits</th>
                                    <th scope="col" width="7%">Marks</th>
                                    <th scope="col" width="10%">Remarks</th>
                                    <th scope="col" width="7%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp

                                @forelse($groupedResults as $courseId => $courseworks)
                                    <tr>
                                        <td colspan="7" class="font-weight-bold">{{ $courseworks[0]->course->courseName }}</td>
                                    </tr>
                                    @foreach($courseworks as $result)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $result->course->courseCode }}</td>
                                            <td>{{ $result->course->courseName }}</td>
                                            <td>{{ $result->course->credits }}</td>
                                            <td>{{ $result->score }}</td>
                                            <td>{{ $result->remarks }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('assign-courses.destroy', $result->id) }}" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7">No coursework results found for this student.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="table-outer nd">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered truncate m-0">
                            <thead>
                                <tr>
                                    <th colspan="6">Semester Two</th>
                                </tr>
                                <tr>
                                    <th scope="col" width="1%">S/N</th>
                                    <th scope="col" width="15%">Course Code</th>
                                    <th scope="col" width="50%">Course Name</th>
                                    <th scope="col" width="10%">Credits</th>
                                    <th scope="col" width="7%">Marks</th>
                                    <th scope="col" width="10%">Remarks</th>
                                    <th scope="col" width="7%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp

                                @forelse($groupedResults as $courseId => $courseworks)
                                    <tr>
                                        <td colspan="7" class="font-weight-bold">{{ $courseworks[0]->course->courseName }}</td>
                                    </tr>
                                    @foreach($courseworks as $result)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $result->course->courseCode }}</td>
                                            <td>{{ $result->course->courseName }}</td>
                                            <td>{{ $result->course->credits }}</td>
                                            <td>{{ $result->score }}</td>
                                            <td>{{ $result->remarks }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('assign-courses.destroy', $result->id) }}" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7">No coursework results found for this student.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
       

        </div>
    </div>
</div>
<!-- Row ends -->
@endsection


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Coursework Results</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($groupedBySemester as $semesterId => $results)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="semester-{{ $semesterId }}-tab" data-toggle="tab" href="#semester-{{ $semesterId }}" role="tab" aria-controls="semester-{{ $semesterId }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                Semester {{ $results->first()->semester->semester_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach($groupedBySemester as $semesterId => $results)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="semester-{{ $semesterId }}" role="tabpanel" aria-labelledby="semester-{{ $semesterId }}-tab">
                            <div class="table-outer mt-3">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered truncate m-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="1%">S/N</th>
                                                <th scope="col" width="15%">Course Code</th>
                                                <th scope="col" width="50%">Course Name</th>
                                                <th scope="col" width="10%">Credits</th>
                                                <th scope="col" width="7%">Marks</th>
                                                <th scope="col" width="10%">Remarks</th>
                                                <th scope="col" width="7%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach($results as $result)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $result->course->courseCode }}</td>
                                                    <td>{{ $result->course->courseName }}</td>
                                                    <td>{{ $result->programmeCourseSemester->credit_weight }}</td>
                                                    <td>{{ $result->score }}</td>
                                                    <td>{{ $result->remarks }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('assign-courses.destroy', $result->id) }}" style="display:inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fa-solid fa-trash"></i> Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
