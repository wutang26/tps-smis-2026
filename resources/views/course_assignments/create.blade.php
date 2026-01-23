@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Courses Assignments</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Assign Course(s) for {{ $programme->programmeName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<div class="row gx-4">
    <div class="col-sm-4 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <!-- <div class="pull-left">
                            <h2>Add New Course</h2>
                        </div> -->
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('programmes.show', $programme->id) }}"><i class="fa fa-arrow-left"></i> Back</a>
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
                                
                <form action="{{ route('assign-courses.store', [$programme->id, $semester->id, $sessionProgramme->id]) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc4">Semester</label>
                                    <select class="form-select" name="semester_id" aria-label="Default select">
                                        <option value="" selected disabled>-- Choose semester</option> 
                                        <option value="1" {{ old('semester_id', 'default_value') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('semester_id', 'default_value') == '2' ? 'selected' : '' }}>2</option>
                                    </select>
                                </div>
                                @error('forceNumber')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>    
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc4">Course Type</label>
                                    <select class="form-select" name="course_type" aria-label="Default select">
                                        <option value="" selected disabled>-- Choose course type</option> 
                                        <option value="core" {{ old('course_type', 'default_value') == 'core' ? 'selected' : '' }}>Core</option>
                                        <option value="minor" {{ old('course_type', 'default_value') == 'minor' ? 'selected' : '' }}>Minor</option>
                                        <option value="optional" {{ old('course_type', 'default_value') == 'optional' ? 'selected' : '' }}>Optional</option>
                                    </select>
                                </div>
                                @error('forceNumber')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>        
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="course_id">Courses</label>
                                    <select name="course_ids[]" multiple id="course_id" class="form-control"> 
                                    <option value="" selected disabled>-- Choose course(s) to enroll</option> 
                                        @foreach ($courses as $course) 
                                        <option value="{{ $course->id }}">{{ $course->courseName }}</option> 
                                        @endforeach 
                                    </select> 
                                </div>
                                @error('course_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label abcd" for="abc">Credit Weight</label>
                                    <input type="number" class="form-control" name="credit_weight" placeholder="Enter weight of the course" value="{{old('credit_weight')}}" required>
                                </div>
                                @error('credit_weight')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div> 
                        @php
                        $sessionProgrammeId = session('selected_session', 9);
                        $sessionProgramme = \App\Models\SessionProgramme::find($sessionProgrammeId);
                        @endphp
                       <input type="hidden" name="programme_id" value="{{ $programme->id }}">
                        <input type="hidden" name="session_programme_id" value="{{ $sessionProgramme->id }}">
                        <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                            
                        <br> 
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
     
    </div>
  
    <div class="col-sm-8 col-12">
    <div class="card mb-8">
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">
                <p>{{ session('success') }}</p>
            </div>
            @endif
            @php
            $i=1;
            $j=1;
            @endphp

            <div class="table-outer">
                <div class="table-responsive">
                    <table class="table table-striped truncate m-0">
                        <thead>
                            <tr>
                                <th colspan="6">Semester One</th>
                            </tr>
                            <tr>
                                <th scope="col" width="5%">No</th>
                                <th scope="col" width="15%">Course Code</th>
                                <th scope="col" width="50%">Course Name</th>
                                <th scope="col" width="15%">Course Type</th>
                                <th scope="col" width="10%">Credit</th>
                                <th scope="col" width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses1 as $course)
                            @if ($course->pivot->course_type != 'Optional')
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $course->courseCode }}</td>
                                <td>{{ $course->courseName }}</td>
                                <td>{{ $course->pivot->course_type }}</td>
                                <td>{{ $course->pivot->credit_weight }}</td>
                                <td>
                                    
                                    <button class="btn  btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#More{{$programme->id}}">Staf</button>

                                    <a class="btn btn-primary btn-sm" href="{{ route('assign-courses.edit', $course->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('assign-courses.destroy', $course->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                        <tbody>
                            <tr>
                                <th colspan="6">Optional Course(s)</th>
                            </tr>
                            @foreach ($courses1 as $course)
                            @if ($course->pivot->course_type === 'Optional')
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $course->courseCode }}</td>
                                <td>{{ $course->courseName }}</td>
                                <td>{{ $course->pivot->course_type }}</td>
                                <td>{{ $course->pivot->credit_weight }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('assign-courses.edit', $course->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Staff
                                    </a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('assign-courses.edit', $course->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('assign-courses.destroy', $course->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-outer" style="margin-top:20px">
                <div class="table-responsive">
                    <table class="table table-striped truncate m-0">
                        <thead>
                            <tr>
                                <th colspan="6">Semester Two</th>
                            </tr>
                            <tr>
                                <th scope="col" width="5%">No</th>
                                <th scope="col" width="15%">Course Code</th>
                                <th scope="col" width="50%">Course Name</th>
                                <th scope="col" width="15%">Course Type</th>
                                <th scope="col" width="10%">Credit</th>
                                <th scope="col" width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses2 as $course)
                            @if ($course->pivot->course_type != 'Optional')
                            <tr>
                                <td>{{ $j++ }}</td>
                                <td>{{ $course->courseCode }}</td>
                                <td>{{ $course->courseName }}</td>
                                <td>{{ $course->pivot->course_type }}</td>
                                <td>{{ $course->pivot->credit_weight }}</td>
                                <td>
                                    <!-- Button to open the modal -->
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#More{{$programme->id}}-{{$course->id}}">Staf</button>

                                    <a class="btn btn-primary btn-sm" href="{{ route('assign-courses.edit', $course->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('assign-courses.destroy', $course->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                        <tbody>
                            <tr>
                                <th colspan="6">Optional Course(s)</th>
                            </tr>
                            @foreach ($courses2 as $course)
                            @if ($course->pivot->course_type === 'Optional')
                            <tr>
                                <td>{{ $j++ }}</td>
                                <td>{{ $course->courseCode }}</td>
                                <td>{{ $course->courseName }}</td>
                                <td>{{ $course->pivot->course_type }}</td>
                                <td>{{ $course->pivot->credit_weight }}</td>
                                <td>
                                    <!-- Button to open the modal -->
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#More{{$programme->id}}-{{$course->id}}">Staf</button>

                                    <a class="btn btn-primary btn-sm" href="{{ route('assign-courses.edit', $course->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('assign-courses.destroy', $course->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach

<!-- Modal -->
<div class="modal fade" id="More{{$programme->id}}" tabindex="-1" aria-labelledby="statusModalLabelMore{{$programme->id}}" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabelMore">Assign Instructors to Programme Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assign.instructors') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="programme_id" value="{{ $sessionProgramme->programme_id }}">
                    <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                    <input type="hidden" name="session_programme_id" value="{{ $sessionProgramme->id }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="form-group">
                        <label for="staffIds">Instructors (Select multiple):</label>
                        <select class="form-control" id="staffIds" name="staff_ids[]" multiple>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        @error('staff_ids')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="academic_year">Academic Year:</label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year" required>
                        @error('academic_year')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div><br>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Assign Instructor(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


                                
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/tps-smis/resources/assets/js/jquery-3.6.0.min.js"></script>
<script src="/tps-smis/resources/assets/js/cdnjs.cloudflare.js"></script>

@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        $('#staffIds').select2({
            width: '100%',
            placeholder: "Select Instructors",
            allowClear: true
        });
    });
</script>
@endsection
