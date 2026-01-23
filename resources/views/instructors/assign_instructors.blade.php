@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')


@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="container mt-5">
        <h2>Assign Instructors to {{ $course->courseName }} Course</h2>
        <form action="{{ route('assign.instructors') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="programmeCourseId">Programme Course</label>
                <select class="form-control" name="programme_id" id="" required>
                    <option value="" selected disabled>select program</option>
                    @foreach ($course->programmes as $program)
                        <option value="{{$program->id}}">{{$program->programmeName}}</option>
                    @endforeach
                </select>
            </div>       
                <input type="text" value="{{ $course->semesters[0]->pivot->semester_id }}"  class="form-control" id="programmeCourseId" name="semester_id" hidden>
                <input type="text" value="{{ $course->semesters[0]->pivot->session_programme_id }}"  class="form-control" id="programmeCourseId" name="session_programme_id" hidden>
                <input type="text" value="{{ $course->id }}"  class="form-control" id="programmeCourseId" name="course_id" hidden>
                <div class="form-group">
                <label for="staffIds">Instructors (Select multiple):</label>
                <select class="form-control" id="staffIds" name="staff_ids[]" multiple required>
                    @foreach($staffs as $staff)
                        @if ($staff->user->hasRole('Instructor'))
                            <option value="{{ $staff->id }}">{{ $staff->firstName }} {{ $staff->lastName }}</option>    
                        @endif                       
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Assign Instructors</button>
        </form>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

@endsection
