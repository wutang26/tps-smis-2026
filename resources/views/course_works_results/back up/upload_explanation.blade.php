@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Course Work Results</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Upload Coursework Results for  {{  $course->courseName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends --> 
@endsection

@section('style')
<style>
/* Responsive design for smaller devices */
/* Responsive design for smaller devices */
@media (max-width: 768px) {
    .card-header, .card-body {
        padding: 15px;
    }

    select, input {
        width: 100%;
        margin-bottom: 15px;
    }

    .d-flex.gap-2 {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .btn {
        width: 100%;
    }

    .backbtn {
        margin: 10px 0;
        width: 100%;
    }
}

</style>
@endsection

@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div>
                 <a href="{{ route('courseworkResultDownloadSample') }}">
                    <button class="btn btn-s btn-success">
                        <i class="bi bi-download"></i> &nbspSample For Uploading Coursework
                    </button>
                </a> 
                </div>

                <div class="mt-3">
                <p style="text-align: justify;">Please download the sample uploading excel file and review your coursework before submitting. If you encounter any issues, feel free to contact support.</p>
                </div>

                <div class="pull-right">
                    <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('coursework_results.index') }}">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">

            @include('layouts.sweet_alerts.index')

                <div class="card-body">
                    <form method="POST" action="{{ route('coursework.upload', $course->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="course_id" name="course_id" value="{{ $course->id }}">
                        
                        <!-- Responsive form group -->
                        <div class="form-group">
                            <label for="semesters">Select Semester</label>
                            <select name="semesterId" id="semesters" class="form-control" required>
                                <option value="" selected disabled>-- Select Semester --</option>
                                @foreach ($course->semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="courseworks">Select Coursework Type</label>
                            <select name="courseworkId" id="courseworks" class="form-control" required>
                                <option value="" selected disabled>-- Select Coursework Type --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="import_file">Upload File</label>
                            <input type="file" name="import_file" class="form-control mb-3" required>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload"></i> &nbsp Upload Coursework
                        </button>
                    </form>
                </div>

            </div>
        </div>     
    </div>
</div>
  

<script>
    // Fetch courseworks when semester is selected
    document.getElementById('semesters').addEventListener('change', function () {
        var semesterId = this.value;
        var courseId = document.getElementById('course_id').value;
        var courseworkSelect = document.getElementById('courseworks');
        courseworkSelect.innerHTML = '<option value="">Select coursework</option>'; // Clear previous options

        if (semesterId) {
            fetch(`/tps-smis/courseworks/${semesterId}/${courseId}`)
                .then(response => response.json())
                .then(courseworks => {
                    courseworks.forEach(coursework => {
                        var option = document.createElement('option');
                        option.value = coursework.id;
                        option.text = coursework.coursework_title;
                        courseworkSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching courseworks:', error));
        }
    });
</script>

@endsection
