@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Register Programme Session</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Create New Session</h2>
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
            <form method="POST" action="{{ route('session_programmes.store') }}">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Session Name:</strong>
                            <input type="text" name="session_programme_name" placeholder="Enter Session Programme" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Description:</strong>
                            <textarea type="text" name="description" placeholder="Enter Descriptions" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Year:</strong>
                            <input type="text" name="year" placeholder="Enter Year of Admission" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Start Date:</strong>
                            <input type="date" name="startDate" placeholder="Enter Start Date" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>End Date:</strong>
                            <input type="date" name="endDate" placeholder="Enter End Date" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-12">
                        <div class="form-group">
                            <strong>Is Current:</strong>
                            <select name="is_current" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <input type="number" name="is_active" value="0" class="form-control" hidden>
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