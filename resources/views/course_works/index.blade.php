@extends('layouts.main')

@section('scrumb')
<!-- Breadcrumb Navigation -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course Work</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Coursework (CW) Assessment types for {{  $course->courseName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
<!-- Main Content -->
 
@session('success')
<div class="alert alert-success alert-dismissible " role="alert">
    {{ $value }}
</div>
@endsession
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">            
            <div class="card-header">
                <div class="pull-right">
                    <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('coursework_results.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                </div>

                <div class="mt-1">
                <p>&nbspHere you can configure coursework assessment types. If you encounter any issues, feel free to contact support.</p>
                </div>
                
                <div class="pull-right" style="float:right !important;">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseworkModal">
                        <i class="fa fa-plus"></i> Add Assessment Type
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Assessment Type</th>
                                    <th>Coursework Title</th>
                                    <th>Max Score</th>
                                    <th>Due Date</th>
                                    <th scope="col" width="280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($course->courseWorks as $index => $courseWork)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $courseWork->AssessmentType->type_name }}</td>
                                    <td>{{ $courseWork->coursework_title }}</td>
                                    <td>{{ $courseWork->max_score }}</td>
                                    <td>{{ $courseWork->due_date }}</td>
                                    <td>
                                    <!-- <a class="btn btn-info btn-sm" href="{{ route('course_works.show', $courseWork->id) }}">
                                        <i class="fa-solid fa-list"></i> Show
                                    </a> -->

                                    <!-- Trigger Button -->
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCourseworkModal{{ $courseWork->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>

                                    <!-- Delete Trigger -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCourseworkModal{{ $courseWork->id }}">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteCourseworkModal{{ $courseWork->id }}" tabindex="-1" aria-labelledby="deleteCourseworkModalLabel{{ $courseWork->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <form method="POST" action="{{ route('course_works.destroy', $courseWork->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="deleteCourseworkModalLabel{{ $courseWork->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                            <div>
                                                This coursework has associated results. <br>Deleting it will also remove all related records.
                                            </div>
                                        </div>

                                            <p><strong>Are you sure you want to proceed?</strong></p>
                                            <input type="hidden" name="cascade" value="true">
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash-can"></i> Delete with Results
                                            </button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    </div>
                                    <!-- End of Delete Modal -->


                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editCourseworkModal{{ $courseWork->id }}" tabindex="-1" aria-labelledby="editCourseworkModalLabel{{ $courseWork->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <form method="POST" action="{{ route('course_works.update', $courseWork->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="editCourseworkModalLabel{{ $courseWork->id }}">Edit Assessment Type</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        <div class="form-group">
                                            <label>Assessment Type</label>
                                            <select name="assessment_type_id" class="form-control">
                                            @foreach ($assessmentTypes as $type)
                                                <option value="{{ $type->id }}" {{ $courseWork->assessment_type_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->type_name }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Coursework Title</label>
                                            <input type="text" name="coursework_title" class="form-control" value="{{ $courseWork->coursework_title }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Max Score</label>
                                            <input type="number" name="max_score" class="form-control" value="{{ $courseWork->max_score }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <input type="date" name="due_date" class="form-control" value="{{ $courseWork->due_date }}">
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fa-solid fa-floppy-disk"></i> Update
                                        </button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                </div>
                                <!-- End of Edit Modal -->
                              @endforeach
                          </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="addCourseworkModal" tabindex="-1" aria-labelledby="addCourseworkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseworkModalLabel">Add Coursework Assessment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                <form method="POST" action="{{ route('course.coursework.store', ['courseId' => $course->id]) }}">
                    @csrf
                    <div class="form-group">
                        <strong>Assessment Type:</strong>
                        <select name="assessment_type_id" class="form-control">
                            @foreach ($assessmentTypes as $assessmentType)
                                <option value="{{ $assessmentType->id }}">{{ $assessmentType->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Coursework Title:</strong>
                        <input type="text" name="coursework_title" placeholder="Enter Coursework title" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong>Max Score:</strong>
                        <input type="number" name="max_score" placeholder="Enter max score" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong>Due Date:</strong>
                        <input type="date" name="due_date" placeholder="Enter Due Date" class="form-control">
                    </div>
                    <input type="number" name="created_by" value="{{ Auth::user()->id }}" hidden>
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-floppy-disk"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End of Create Modal -->
@endsection
