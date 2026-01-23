@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Courses Enrollment</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Optional Enrollment</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                        <h1>Edit Enrollment</h1> 
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('assignments.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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
                

                
<h1>Edit Course Assignment for {{ $programme->programmeName }} in {{ $semester->semester_name }} (Session: {{ $sessionProgramme->session_programme_name }})</h1>
<form action="{{ route('assignments.update', [$programme->id, $semester->id, $sessionProgramme->id, $course->id]) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="course_type">Course Type:</label>
    <input type="text" name="course_type" value="{{ old('course_type', $course->pivot->course_type) }}">
    <label for="credit_weight">Credit Weight:</label>
    <input type="number" name="credit_weight" value="{{ old('credit_weight', $course->pivot->credit_weight) }}">
    <button type="submit">Update Course</button>
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
