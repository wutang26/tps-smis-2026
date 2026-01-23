@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">View Programme</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends --> 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <!-- <div class="pull-left">
                            <h2>Programme Management</h2>
                        </div> -->
                        <!-- <h2 class="card-title" style="float:left !important; margin-left:1%">Programme Management</h2> --> 


                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" style="margin-left:10px" href="{{ route('programmes.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('assign-courses.assignCourse', $programme->id ) }}"><i class="fa fa-arrow-left"></i> Configure Programme</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Programme Name:</strong>
                            {{ $programme->programmeName }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Programme Abbreviation:</strong>
                            {{ $programme->abbreviation }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Duration Period:</strong>
                            {{ $programme->duration }} &nbsp; Year
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Department:</strong>
                            {{ $departmentName[0] }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Study Level:</strong>
                            {{ $studyLevelName[0] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>     
    </div>
  
    <div class="col-sm-6 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection