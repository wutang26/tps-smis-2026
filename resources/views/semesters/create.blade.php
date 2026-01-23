@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Semester</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Semester</a></li>
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
                            <h2>Add New Semester</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('courses.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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
                <form action="{{ route('semesters.store') }}" method="POST"> 
                    @csrf 
                    <div class="mb-3"> 
                        <label for="semester_name" class="form-label">Semester Name</label> 
                        <input type="text" class="form-control" id="semester_name" name="semester_name" required> 
                    </div> 
                        <button type="submit" class="btn btn-success">Submit</button> 
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