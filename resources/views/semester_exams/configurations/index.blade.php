@extends('layouts.main')

@section('scrumb')
<!-- Breadcrumb Navigation -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Semester Exam</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Semester Exam (SE) Configuration for {{  $course->courseName }}</a></li>
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
                    <a class="btn btn-primary btn-sm mb-2 backbtn"
                        href="{{ route('semester_exams.index', ['semester_id' => $course->semester_id]) }}">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
                

                <div class="mt-1">
                <p>&nbspHere you can configure Semester Examination (SE). If you encounter any issues, feel free to contact support.</p>
                </div>
                
                <div class="pull-right" style="float:right !important;">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseworkModal">
                        <i class="fa fa-plus"></i> Add Configuration
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($course->semesterExams->isNotEmpty())
                
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Semester Exam Title</th>
                                    <th>Max Score</th>
                                    <th>Date</th>
                                    <th scope="col" width="280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->semesterExams as $exam)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $exam->exam_title }}</td>
                                    <td>{{ $exam->max_score }}</td>
                                    <td>{{ $exam->exam_date }}</td>
                                    <td>
                                        <!-- Actions -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateExamModal{{ $exam->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Update
                                        </button>

                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteExamModal{{ $exam->id }}">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="deleteExamModal{{ $exam->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('semester_exams.destroy', $exam->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger">Confirm Deletion</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="alert alert-warning">
                                                                This will permanently delete the exam configuration.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete Configuration</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Update Modal -->
                                 <div class="modal fade" id="updateExamModal{{ $exam->id }}" tabindex="-1" aria-labelledby="updateExamModalLabel{{ $exam->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('semester_exams.update', $exam->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateExamModalLabel{{ $exam->id }}">Update Semester Exam Configuration</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mb-2">
                                                        <label for="exam_title_{{ $exam->id }}"><strong>Exam Title:</strong></label>
                                                        <input type="text" name="exam_title" id="exam_title_{{ $exam->id }}"
                                                            value="{{ $exam->exam_title }}" class="form-control" required>
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label for="max_score_{{ $exam->id }}"><strong>Max Score:</strong></label>
                                                        <input type="number" name="max_score" id="max_score_{{ $exam->id }}"
                                                            value="{{ $exam->max_score }}" class="form-control" required>
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label for="exam_date_{{ $exam->id }}"><strong>Date:</strong></label>
                                                        <input type="date" name="exam_date" id="exam_date_{{ $exam->id }}"
                                                            value="{{ $exam->exam_date }}" class="form-control" required>
                                                    </div>
                                                    <input type="hidden" name="updated_by" value="{{ auth()->id() }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                @endforeach

                          </tbody>

                        </table>
                    </div>
                </div>@else
                   &nbsp; -- No Configuration Found.
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCourseworkModal" tabindex="-1" aria-labelledby="addCourseworkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseworkModalLabel">Add Semester Exam Configuration</h5>
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
                <form method="POST" action="{{ route('course.store', ['courseId' => $course->id]) }}">
                    @csrf
                
                    <div class="form-group">
                        <strong>Title:</strong>
                        <input type="text" name="exam_title" class="form-control" value="Final Semester Exam" readonly>
                    </div>
                    <div class="form-group">
                        <strong>Max Score:</strong>
                        <input type="number" name="max_score" placeholder="Enter max score" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong>Date:</strong>
                        <input type="date" name="exam_date" placeholder="Enter  Date" class="form-control">
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
@endsection
