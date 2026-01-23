@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Upload</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    @include('layouts.sweet_alerts.index')
    <div class="d-flex justify-content-between">
        <a href="{{ route('studentDownloadSample') }}"><button style="height: 30px;" class="btn btn-sm btn-success"><i
                    class="bi bi-download"></i>&nbsp&nbspDownload sample for Uploading Students</button></a>

        
    </div>
    <div class="mt-3">
        <p>&nbspPlease download the sample uploading excel file and review your students list before submitting. If you
            encounter any issues, feel free to contact support.</p>
    </div>

    <form method="POST" action="{{url('students/bulkimport')}}" style="display:inline" enctype="multipart/form-data"
            style="float:right;">
            @csrf
            @method('POST')
            <div class="d-flex gap-2" style="float:right;">
                <input style="height: 30px; width: 60%" required type="file" name="import_file" class="form-control mb-2">
                <button style="height: 30px;" title="Upload by CSV/excel file" type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-upload"></i>&nbspUpload
                    Students</i></button>
            </div>
        </form>
@endsection