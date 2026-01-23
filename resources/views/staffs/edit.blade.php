@extends('layouts.main')

@section('style')
<!-- style starts -->
<!-- Steps Wizard CSS -->
<link rel="stylesheet" href="/tps-smis/resources/assets/vendor/wizard/wizard.css" />

<style>
@media only screen and (min-width: 576px) {
    #pfno {
        margin-left: 12.5% !important;
        background-color: red;
    }
}

@media only screen and (max-width: 600px) {
    .abcd {
        font-size: 15px !important;
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
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Register Staff</a></li>
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
                        <div class="pull-left">
                            <!-- <h2>Create New Programme</h2> -->
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('staffs.index') }}"><i
                                    class="fa fa-arrow-left"></i> Back</a>
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
                <form method="POST" action="{{ route('staffs.update', $staff->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Wizard #2 -->
                    <div id="smartwizard2">
                        <ul class="nav">
                            <li class="nav-item abcd">
                                <a class="nav-link" href="#step-2a">
                                    <div class="num">1</div>
                                    Personal Details
                                </a>
                            </li>
                            <li class="nav-item abcd">
                                <a class="nav-link" href="#step-2b">
                                    <span class="num">2</span>
                                    Proffessional Qualifications
                                </a>
                            </li>
                            <li class="nav-item abcd">
                                <a class="nav-link" href="#step-2c">
                                    <span class="num">3</span>
                                    Next of Kin Details
                                </a>
                            </li>
                            <!-- <li class="nav-item abcd">
                          <a class="nav-link " href="#step-2d">
                            <span class="num">4</span>
                            Preview & Submit
                          </a>
                        </li> -->
                        </ul>

                        <div class="tab-content">
                            <div id="step-2a" class="tab-pane" role="tabpanel" aria-labelledby="step-2a">
                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row" style="margin-bottom:-1%">
                                                <div class="col-sm-3 col-12" id="pfn0">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">PF Number/Force
                                                                    Number</label>
                                                                <input type="text" class="form-control" id="forceNumber"
                                                                    name="forceNumber"
                                                                    placeholder="Enter PF Number/Force Number"
                                                                    value="{{ $staff->forceNumber }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Rank</label>
                                                                <select class="form-select" id="rank" name="rank"
                                                                    aria-label="Default select example">
                                                                    <option value="" disabled selected>Select Rank
                                                                    </option>
                                                                    <option value="PC"
                                                                        {{ $staff->rank == 'PC' ? 'selected' : '' }}>
                                                                        Police Constable (PC)</option>
                                                                    <option value="CPL"
                                                                        {{ $staff->rank == 'CPL' ? 'selected' : '' }}>
                                                                        Corporal (CPL)</option>
                                                                    <option value="SGT"
                                                                        {{ $staff->rank == 'SGT' ? 'selected' : ''  }}>
                                                                        Sergeant (SGT)</option>
                                                                    <option value="S/SGT"
                                                                        {{ $staff->rank == 'S/SGT' ? 'selected' : '' }}>
                                                                        Staff Sergeant (S/SGT)</option>
                                                                    <option value="SM"
                                                                        {{ $staff->rank == 'SM' ? 'selected' : ''  }}>
                                                                        Sergeant Major (SM)</option>
                                                                    <option value="A/INSP"
                                                                        {{ $staff->rank == 'A/INSP' ? 'selected' : '' }}>
                                                                        Assistant Inspector of Police (A/INSP)</option>
                                                                    <option value="INSP"
                                                                        {{ $staff->rank == 'INSP' ? 'selected' : '' }}>
                                                                        Inspector of Police (INSP)</option>
                                                                    <option value="ASP"
                                                                        {{ $staff->rank == 'ASP' ? 'selected' : ''  }}>
                                                                        Assistant Superitendent of Police (ASP)</option>
                                                                    <option value="SP"
                                                                        {{ $staff->rank == 'SP' ? 'selected' : ''  }}>
                                                                        Superitendent of Police (SP)</option>
                                                                    <option value="SSP"
                                                                        {{ $staff->rank == 'SSP' ? 'selected' : '' }}>
                                                                        Senior Superitendent of Police (SSP)</option>
                                                                    <option value="ACP"
                                                                        {{ $staff->rank == 'ACP' ? 'selected' : ''  }}>
                                                                        Assistant Commissioner of Police (ACP)</option>
                                                                    <option value="SACP"
                                                                        {{ $staff->rank == 'SACP' ? 'selected' : '' }}>
                                                                        Senior Assistant Commissioner of Police (SACP)
                                                                    </option>
                                                                    <option value="DCP"
                                                                        {{ $staff->rank == 'DCP' ? 'selected' : '' }}>
                                                                        Deputy Commissioner of Police (DCP)</option>
                                                                    <option value="CP"
                                                                        {{ $staff->rank == 'CP' ? 'selected' : '' }}>
                                                                        Commissioner of Police (CP)</option>
                                                                    <option value="IGP"
                                                                        {{ $staff->rank == 'IGP' ? 'selected' : '' }}>
                                                                        Inspector General of Police (IGP)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">National
                                                                    Identification Number</label>
                                                                <input type="text" class="form-control" id="nin"
                                                                    name="nin" placeholder="Enter NIDA Number"
                                                                    value="{{ $staff->nin }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Company </label>
                                                                <select class="form-control" name="company_id" id="" >
                                                                    <option value="" selected disaled>company</option>
                                                                    @foreach ($companies as $company)
                                                                        <option @if($staff->company_id == $company->id) selected @endif value="{{$company->id}}">{{$company->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom:-1%">
                                                <div class="col-sm-3 col-12" style="margin-bottom:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">First
                                                                    Name</label>
                                                                <input type="text" class="form-control" id="firstName"
                                                                    name="firstName" placeholder="Enter First Name"
                                                                    value="{{ $staff->firstName }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12" style="margin-bottom:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd"
                                                                    for="abc">Middlename</label>
                                                                <input type="text" class="form-control" id="middleName"
                                                                    name="middleName" placeholder="Enter Middle Name"
                                                                    value="{{ $staff->middleName }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12" style="margin-bottom:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Lastname
                                                                    (Surname)</label>
                                                                <input type="text" class="form-control" id="lastName"
                                                                    name="lastName" placeholder="Enter Last Name"
                                                                    value="{{ $staff->lastName }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12" style="margin-bottom:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Gender</label>
                                                                <select class="form-select" id="gender" name="gender"
                                                                    aria-label="Default select example">
                                                                    <option value="" disiabled selected>Choose gender
                                                                    </option>
                                                                    <option value="Male"
                                                                        {{ $staff->gender == 'Male' ? 'selected' : '' }}>
                                                                        Male</option>
                                                                    <option value="Female"
                                                                        {{ $staff->gender == 'Female' ? 'selected' : '' }}>
                                                                        Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Date of
                                                                    Birth</label>
                                                                <input type="date" class="form-control" id="DoB"
                                                                    name="DoB" placeholder="Enter Date of Birth"
                                                                    value="{{ $staff->DoB }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Marital
                                                                    Status</label>
                                                                <select class="form-select" id="maritalStatus"
                                                                    name="maritalStatus"
                                                                    aria-label="Default select example">
                                                                    <option value="" disabled selected>Choose Marital
                                                                        Status</option>
                                                                    <option value="Single"
                                                                        {{ $staff->maritalStatus == 'Single' ? 'selected' : '' }}>
                                                                        Single</option>
                                                                    <option value="Married"
                                                                        {{ $staff->maritalStatus == 'Married' ? 'selected' : '' }}>
                                                                        Married</option>
                                                                    <option value="Divorsed"
                                                                        {{ $staff->maritalStatus == 'Divorsed' ? 'selected' : '' }}>
                                                                        Divorsed</option>
                                                                    <option value="Complicated"
                                                                        {{ $staff->maritalStatus == 'Complicated' ? 'selected' : '' }}>
                                                                        Complicated</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd"
                                                                    for="abc">Religion</label>
                                                                <input type="text" class="form-control" id="religion"
                                                                    name="religion" placeholder="Enter religion"
                                                                    value="{{ $staff->religion }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Tribe</label>
                                                                <input type="text" class="form-control" id="tribe"
                                                                    name="tribe" placeholder="Enter tribe"
                                                                    value="{{ $staff->tribe }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Phone
                                                                    Number</label>
                                                                <input type="text" class="form-control" id="phoneNumber"
                                                                    name="phoneNumber" placeholder="Enter phone number"
                                                                    value="{{ $staff->phoneNumber }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Email
                                                                    Address</label>
                                                                <input type="email" class="form-control" id="email"
                                                                    name="email" placeholder="Enter email address"
                                                                    value="{{ $staff->email }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Current
                                                                    Address</label>
                                                                <input type="text" class="form-control"
                                                                    id="currentAddress" name="currentAddress"
                                                                    placeholder="Enter current address"
                                                                    value="{{ $staff->currentAddress }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Permanent
                                                                    Address</label>
                                                                <input type="text" class="form-control"
                                                                    id="permanentAddress" name="permanentAddress"
                                                                    placeholder="Enter permanent address"
                                                                    value="{{ $staff->permanentAddress }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->
                            </div>
                            <div id="step-2b" class="tab-pane" role="tabpanel" aria-labelledby="step-2b">
                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row" style="margin-bottom:-1%">
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Department</label>
                                                                <select class="form-select" id="department_id"
                                                                    name="department_id"
                                                                    aria-label="Default select example">
                                                                    <option value="" disabled selected>Choose Department
                                                                    </option>
                                                                    @foreach ($departments as $value => $dep)
                                                                    <option value="{{ $dep->id }}"
                                                                        {{ $staff->department_id == $dep->id ? 'selected' : '' }}>
                                                                        {{ $dep->departmentName }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd"
                                                                    for="abc">Designation</label>
                                                                <input type="text" class="form-control" id="designation"
                                                                    name="designation" placeholder="Enter designation"
                                                                    value="{{ $staff->designation }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Role</label>
                                                                <select name="roles[]" class="form-control"
                                                                    multiple="multiple">
                                                                    @foreach ($roles as $value => $label)
                                                                    <option value="{{ $value }}"
                                                                        {{ isset($userRole[$value]) ? 'selected' : ''}}>
                                                                        {{ $label }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Education
                                                                    Level</label>
                                                                <select class="form-select" id="educationLevel"
                                                                    name="educationLevel"
                                                                    aria-label="Default select example">
                                                                    <option value="" disabled selected>Select Education
                                                                        Level</option>
                                                                    <option value="std7"
                                                                        {{ $staff->educationLevel == 'std7' ? 'selected' : '' }}>
                                                                        Darasa la Saba</option>
                                                                    <option value="4m4"
                                                                        {{ $staff->educationLevel == '4m4' ? 'selected' : '' }}>
                                                                        Form Four</option>
                                                                    <option value="4m6"
                                                                        {{ $staff->educationLevel == '4m6' ? 'selected' : '' }}>
                                                                        Form Six</option>
                                                                    <option value="Certificate"
                                                                        {{ $staff->educationLevel == 'Certificate' ? 'selected' : '' }}>
                                                                        Certificate</option>
                                                                    <option value="Diploma"
                                                                        {{ $staff->educationLevel == 'Diploma' ? 'selected' : '' }}>
                                                                        Diploma</option>
                                                                    <option value="Degree"
                                                                        {{ $staff->educationLevel == 'Degree' ? 'selected' : '' }}>
                                                                        Bachelor Degree</option>
                                                                    <option value="Masters"
                                                                        {{ $staff->educationLevel == 'Masters' ? 'selected' : '' }}>
                                                                        Masters</option>
                                                                    <option value="PhD"
                                                                        {{ $staff->educationLevel == 'PhD' ? 'selected' : '' }}>
                                                                        PhD</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6 col-12" style="margin-left:12.5%;">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc4">Contract
                                                                    Type</label>
                                                                <select class="form-select" id="contractType"
                                                                    name="contractType"
                                                                    aria-label="Default select example">
                                                                    <option value="" disabled selected>Choose Contract
                                                                        Type</option>
                                                                    <option value="Permanent"
                                                                        {{ $staff->contractType == 'Permanent' ? 'selected' : '' }}>
                                                                        Permanent Contract</option>
                                                                    <option value="Temporary"
                                                                        {{ $staff->contractType == 'Temporary' ? 'selected' : '' }}>
                                                                        Temporary Contract</option>
                                                                    <option value="Fixed-Term"
                                                                        {{ $staff->contractType == 'Fixed-Term' ? 'selected' : '' }}>
                                                                        Fixed-Term Contract</option>
                                                                    <option value="Probationary"
                                                                        {{ $staff->contractType == 'Probation' ? 'selected' : '' }}>
                                                                        Probationary Contract</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Date of
                                                                    Joining</label>
                                                                <input type="date" class="form-control" id="joiningDate"
                                                                    name="joiningDate"
                                                                    value="{{ $staff->joiningDate }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd"
                                                                    for="abc">Location</label>
                                                                <input type="text" class="form-control" id="location"
                                                                    name="location" placeholder="Enter Location"
                                                                    value="{{ $staff->location }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->
                            </div>
                            <div id="step-2c" class="tab-pane" role="tabpanel" aria-labelledby="step-2c">
                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row" style="margin-bottom:-1%">
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Full
                                                                    Names</label>
                                                                <input type="text" class="form-control"
                                                                    id="nextofkinFullname" name="nextofkinFullname"
                                                                    placeholder="Enter Next of Kin Fullname"
                                                                    value="{{ $staffNextofkin->nextofkinFullname ?? ''}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd"
                                                                    for="abc">Relationship</label>
                                                                <input type="text" class="form-control"
                                                                    id="nextofkinRelationship"
                                                                    name="nextofkinRelationship"
                                                                    placeholder="Enter Next of Kin Relationship"
                                                                    value="{{ $staffNextofkin->nextofkinRelationship ?? ''}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Phone
                                                                    Number</label>
                                                                <input type="text" class="form-control"
                                                                    id="nextofkinPhoneNumber"
                                                                    name="nextofkinPhoneNumber"
                                                                    placeholder="Enter Next of Kin Phone Number"
                                                                    value="{{ $staffNextofkin->nextofkinPhoneNumber ?? ''}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Physical
                                                                    Address</label>
                                                                <input type="text" class="form-control"
                                                                    id="nextofkinPhysicalAddress"
                                                                    name="nextofkinPhysicalAddress"
                                                                    placeholder="Enter Next of Kin Physical Address"
                                                                    value="{{ $staffNextofkin->nextofkinPhysicalAddress ?? ''}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->
                            </div>
                            <!-- <div id="step-2d" class="tab-pane" role="tabpanel" aria-labelledby="step-2d"> -->
                            <!-- Row starts -->
                            <!-- <div class="row gx-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row abcd" style="margin-bottom:-1%">
                                            

                                            <h3>Preview & Submit</h3>
                                            <div id="preview-personal-details">Blah blah</div>
                                            <div id="preview-professional-qualifications"></div>
                                            <div id="preview-next-of-kin-details"></div>


                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- Row ends -->
                            <!-- </div> -->

                        </div>


                        <!-- Include optional progress bar HTML -->
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <input type="number" name="updated_by" value="{{ Auth::user()->id }}" class="form-control"
                            hidden>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center" id="btnSubmit"
                            style="margin-bottom:0px;">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i
                                    class="fa-solid fa-floppy-disk"></i> Update</button>
                        </div>

                    </div>

            </div>
        </div>
    </div>
</div>
<!-- Row ends -->






</form>
</div>
</div>

</div>
</div>
@endsection
@section('scripts')
<!-- scripts starts -->
<!-- Steps wizard JS -->
<script src="/tps-smis/resources/assets/vendor/wizard/wizard.min.js"></script>
<script src="/tps-smis/resources/assets/vendor/wizard/wizard-custom.js"></script>
<!-- scripts ends -->
@endsection