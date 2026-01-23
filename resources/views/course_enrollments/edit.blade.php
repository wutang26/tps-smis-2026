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
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('enrollments.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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
                <form action="{{ route('enrollments.update', $optionalCourseEnrollment->id) }}" method="POST"> 
                    @csrf @method('PUT') <div> <label for="student_id">Student</label> <select name="student_id" id="student_id"> @foreach ($students as $student) <option value="{{ $student->id }}" {{ $student->id == $optionalCourseEnrollment->student_id ? 'selected' : '' }}>{{ $student->name }}</option> @endforeach </select> </div> <div> <label for="course_id">Course</label> <select name="course_id" id="course_id"> @foreach ($courses as $course) <option value="{{ $course->id }}" {{ $course->id == $optionalCourseEnrollment->course_id ? 'selected' : '' }}>{{ $course->courseName }}</option> @endforeach </select> </div> <div> <label for="semester_id">Semester</label> <select name="semester_id" id="semester_id"> @foreach ($semesters as $semester) <option value="{{ $semester->id }}" {{ $semester->id == $optionalCourseEnrollment->semester_id ? 'selected' : '' }}>{{ $semester->semester_name }}</option> @endforeach </select> </div> <div> <label for="enrollment_date">Enrollment Date</label> <input type="date" name="enrollment_date" id="enrollment_date" value="{{ $optionalCourseEnrollment->enrollment_date }}"> </div> <button type="submit">Update</button> 
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