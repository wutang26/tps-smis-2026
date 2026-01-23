@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Departments</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">View Department</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends --> 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-8">
    <div class="col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" style="margin-left:10px" href="{{ route('departments.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Department Name:</strong>
                            {{ $department->departmentName }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Campus Description:</strong>
                            {{ $department->description }}
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Status:</strong>
                            @if ($department->is_active == 1)
                                <label style="color:green">Active</label>
                            @else
                            <label style="color:red">Not Active</label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>     
    </div>
  
    <div class="col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection