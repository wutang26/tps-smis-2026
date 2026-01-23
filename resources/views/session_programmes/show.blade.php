@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">View Programme Sessions</a></li>
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
                        <!-- <div class="pull-left">
                            <h2>View Programme Session</h2>
                        </div> -->
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('session_programmes.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Programme Session Name:</strong>
                            {{ $session_programme->session_programme_name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Descriptions:</strong>
                            {{ $session_programme->description }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Start Date:</strong>
                            {{ $session_programme->startDate }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>End Date:</strong>
                            {{ $session_programme->endDate }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Status:</strong>
                            @if ($session_programme->is_active == 1)
                                <label style="color:green">Active Session</label>
                            @else
                            <label style="color:red">Not Active</label>
                            @endif
                        </div>
                    </div>
                </div>
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