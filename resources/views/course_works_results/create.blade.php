@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Coursework Results</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Results</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Add Course Results</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('coursework_results.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif
            <form method="POST" action="{{ route('coursework_results.store') }}">
                @csrf
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Student:</strong>
                            <select name="student_id" class="form-control" required>    
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->first_name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Course:</strong>
                            <select name="course_id" class="form-control" required>           
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->courseName }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Coursework Title:</strong>
                            <select name="coursework_id" class="form-control" required>    
                                    @foreach ($courseWorks as $courseWork)
                                        <option value="{{ $courseWork->id }}">{{ $courseWork->coursework_title }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Score:</strong>
                            <input type="number" name="score" placeholder="Enter Score" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Semester:</strong>
                            <select name="semester_id" class="form-control">                                    
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <input type="number" name="created_by" value="{{ Auth::user()->id }}" class="form-control" hidden>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                    </div>
                </div>
            </form>
            
            </div>
        </div>
     
    </div>
  
    <div class="col-sm-4 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection