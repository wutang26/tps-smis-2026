@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Final Results</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Final Results Lists</a></li>
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
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">

            </div>
            @if ($courses->isEmpty())
            <div class="alert alert-info" role="alert">
                No enrollments found. Please ensure students are enrolled in courses for the selected session programme.
            </div>
            @else
            <div class="pull-right">
                <!-- <a class="btn btn-success mb-2" href="{{ route('final_results.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New course</a> -->

                <form action="{{ route('final_results.generate.all') }}" method="GET" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="float:right !important; margin-right:1%">Generate Final Results</button>
                </form>

                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
                @endif
            </div>

            <div class="row gx-4">
                <div class="col-sm-8 col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course</th>
                                        <th width="250px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 0;
                                    @endphp
                                    
                                        @foreach ($courses as $course)
                                        <tr>
                                        <td>{{++$i}}.</td>
                                        <td>{{$course->courseName}}</td>
                                        <td>
                                            <form id="generateResultsForm{{$course->id}}"
                                                action="{{route('final_results.session.generate', $sessionProgrammeId)}}"
                                                method="post">
                                                @csrf
                                                <input type="text" value="{{$course->id}}" name="course_id" id="" hidden>
                                                
                                                <button type="button"
                                                    onclick="confirmAction('generateResultsForm{{$course->id}}', 'Generate Results',' results for {{$course->courseName}}','Generate')"
                                                    class="btn btn-sm btn-primary">Generate</button>
                                            </form>

                                        </td>
                                      </tr>
                                        @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>          
        </div>
         @endif
    </div>
</div>
<!-- Row ends -->
@endsection