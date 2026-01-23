@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Programme Session</a></li>
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
                            <h2>Edit Programme Session</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('session_programmes.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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

                <form method="POST" action="{{ route('session_programmes.update', $session_programme->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Session Name:</strong>
                                <input type="text" name="session_programme_name" placeholder="Enter Session Programme" class="form-control" value="{{ $session_programme->session_programme_name }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Description:</strong>
                                <textarea type="text" name="description" placeholder="Enter Descriptions" class="form-control">{{ $session_programme->description }}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>Year:</strong>
                                <input type="text" name="year" placeholder="Enter Year of Admission" class="form-control" value="{{ $session_programme->year }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>Start Date:</strong>
                                <input type="date" name="startDate" placeholder="Enter Start Date" class="form-control" value="{{ $session_programme->startDate ?? ''}}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>End Date:</strong>
                                <input type="date" name="endDate" placeholder="Enter End Date" class="form-control" value="{{ $session_programme->endDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>Is Current:</strong>
                                <select name="is_current" class="form-control">

                                    @if($session_programme->is_current == 1)
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    @else
                                        <option value="1">Yes</option>
                                        <option value="0" selected>No</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div>
                    </div>
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
