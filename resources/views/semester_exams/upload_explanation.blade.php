@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Course Work Results</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Upload Course Exam Results for  {{  $course->courseName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends --> 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div>
                 <a href="{{ route('courseworkResultDownloadSample') }}">
                    <button  class="btn btn-s btn-success">
                        <i class="bi bi-download"></i> &nbspSample For Uploading Course Exam results
                    </button>
                </a> 
                </div>

                <div class="mt-3">
                <p>&nbspPlease download the sample uploading excel file and review your course results before submitting. If you encounter any issues, feel free to contact support.</p>
                </div>

                <div class="pull-right">
                    <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('coursework_results.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body">

            @include('layouts.sweet_alerts.index')
                <div class="d-flex gap-2 float-end">
                    <form method="POST"  action="{{ route('course_exam_results.upload', $course->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="d-flex gap-2 justify-content-end">
                            <!-- Semester Select -->
                            

                            <!-- Coursework Select -->
                            

                            <div class="d-flex gap-2" style="width:550px">
                                <!-- File Upload -->
                                <input type="file" name="import_file" class="form-control mb-2" required 
                                    style="width: 80%; height: 40px; text-align: center; line-height: 40px; padding: 0; padding-left: 10px;">
                                <input type="text" name="semesterId" value="{{$semesterId}}" hidden>
                                <!-- Button -->
                                <button 
                                    style="width: 60%; height: 40px;" 
                                    type="submit" 
                                    class="btn btn-success">
                                    <i class="bi bi-upload"></i>&nbsp Upload exam results
                                </button>
                            </div>
                        </div>

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
        var courseworkSelect = document.getElementById('courseworks');
        courseworkSelect.innerHTML = '<option value="">Select course</option>'; // Clear previous options

        if (semesterId) {
            fetch(`/tps-smis/courseworks/${semesterId}`)
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
