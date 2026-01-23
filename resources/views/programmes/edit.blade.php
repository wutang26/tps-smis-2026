@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Programme</a></li>
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
                            <h2>Edit Programme</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('programmes.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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

                <form method="POST" action="{{ route('programmes.update', $programme->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Programme Name:</strong>
                                <input type="text" name="programmeName" placeholder="Enter Programme Name" class="form-control" value="{{ $programme->programmeName }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Abbreviation:</strong>
                                <input type="text" name="abbreviation" placeholder="Enter Abbreviation" class="form-control" value="{{ $programme->abbreviation }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <strong>Duration (In Months):</strong>
                                <input type="number" name="duration" placeholder="Enter Duration Period" class="form-control" value="{{ $programme->duration }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">
                                <!-- <strong>Department:</strong>
                                <select name="department_id" class="form-control">
                                    @foreach ($departments as $value => $dep)
                                        <option value="{{ $value }}" {{ $dep->id == $programme->department_id ? 'selected' : ''}}>
                                            {{ $dep->departmentName }}
                                        </option>
                                    @endforeach
                                </select> -->

                                <label for="department_id">Department</label> 
                                <select name="department_id" id="department_id" class="form-control" required> 
                                    @foreach($departments as $department) 
                                        <option value="{{ $department->id }}" {{ $programme->department_id == $department->id ? 'selected' : '' }}> 
                                            {{ $department->departmentName }} 
                                        </option> 
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-12">
                            <div class="form-group">                                
                                <label for="studylevel_id">Study Level</label> 
                                <select name="studyLevel_id" id="studylevel_id" class="form-control" required> 
                                    @foreach($studylevels as $studylevel) 
                                        <option value="{{ $studylevel->id }}" {{ $programme->studylevel_id == $studylevel->id ? 'selected' : '' }}> 
                                            {{ $studylevel->studyLevelName }} 
                                        </option> 
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <input type="number" name="updated_by" value="{{ Auth::user()->id }}" class="form-control" hidden>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Update</button>
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