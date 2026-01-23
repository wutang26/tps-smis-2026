@extends('layouts.main')

@section('style')
<style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        h1, h2 { color: #2F4F4F; }
        .section { margin-bottom: 20px; }
        .header { text-align: center; }
        .photo { margin-top: 10px; }

    @media only screen and (min-width: 576px) {
        #pfno {
            margin-left:12.5% !important;
            background-color:red;
        }
    }

    @media only screen and (max-width: 600px) {
        .abcd{
            font-size:15px !important;
        }
    }
</style>
<!-- style ends -->
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Staffs</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Staff Curriculum Vitae (CV)</a></li>
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
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('staffs.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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

                        <div class="header">
                            <h1>{{ $staff->firstName }} {{ $staff->middleName }} {{ $staff->lastName }}</h1>
                            <p>{{ $staff->email }} | {{ $staff->phoneNumber }}</p>
                            <p>{{ $staff->currentAddress }}</p>
                            @if ($staff->photo)
                                <p class="photo"><img src="{{ asset($staff->photo) }}" alt="Photo" height="100"></p>
                            @endif
                        </div>
                        <div class="section">
                            <h2>Personal Details</h2>
                            <p><strong>Force Number:</strong> {{ $staff->forceNumber }}</p>
                            <p><strong>Date of Birth:</strong> {{ $staff->DoB }}</p>
                            <p><strong>Gender:</strong> {{ $staff->gender }}</p>
                            <p><strong>Marital Status:</strong> {{ $staff->maritalStatus }}</p>
                            <p><strong>Religion:</strong> {{ $staff->religion }}</p>
                            <p><strong>Tribe:</strong> {{ $staff->tribe }}</p>
                        </div>
                        <div class="section">
                            <h2>Professional Details</h2>
                            <p><strong>Rank:</strong> {{ $staff->rank }}</p>
                            <p><strong>Department:</strong> {{ $staff->department->name ?? 'N/A' }}</p>
                            <p><strong>Designation:</strong> {{ $staff->designation }}</p>
                            <p><strong>Contract Type:</strong> {{ $staff->contractType }}</p>
                            <p><strong>Joining Date:</strong> {{ $staff->joiningDate }}</p>
                            <p><strong>Location:</strong> {{ $staff->location }}</p>
                        </div>
                        <div class="section">
                            <h2>Education</h2>
                            <p><strong>Level:</strong> {{ $staff->educationLevel }}</p>
                        </div>
                        <div class="section">
                            <h2>Additional Details</h2>
                            <p><strong>Profile Complete:</strong> {{ $staff->profile_complete ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="section">
                            <h2>Other Information</h2>
                            <p>Add any custom details here...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection