@extends('layouts.main')

@section('style')
<!-- style starts -->
    <!-- Steps Wizard CSS -->
    <link rel="stylesheet" href="/tps-smis/resources/assets/vendor/wizard/wizard.css" />

<style>
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
    <div class="col-12">

                <div class="row mb-3">
                    <div class="col-6">
                        <!-- Left side (empty for now) -->
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('staffs.index') }}">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('staffs.store') }}">
                    @csrf

                    <div id="smartwizard2">
                        <ul class="nav">
                            <li class="nav-item abcd">
                                <a class="nav-link" href="#step-2a">
                                    <div class="num">1</div> Personal Details
                                </a>
                            </li>
                            <li class="nav-item abcd">
                                <a class="nav-link" href="#step-2b">
                                    <span class="num">2</span> Professional Qualifications
                                </a>
                            </li>
                            <li class="nav-item abcd">
                                <a class="nav-link" href="#step-2c">
                                    <span class="num">3</span> Next of Kin Details
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <!-- STEP 1: PERSONAL DETAILS -->
                            <div id="step-2a" class="tab-pane">
                                <div class="row g-3">
                                    <!-- Personal Details Fields -->
                                    @foreach([
                                        ['label'=>'PF Number/Force Number','name'=>'forceNumber','type'=>'text','required'=>true],
                                        ['label'=>'Rank','name'=>'rank','type'=>'select','options'=>['PC'=>'Police Constable (PC)','CPL'=>'Corporal (CPL)','SGT'=>'Sergeant (SGT)','S/SGT'=>'Staff Sergeant (S/SGT)','SM'=>'Sergeant Major (SM)','A/INSP'=>'Assistant Inspector of Police (A/INSP)','INSP'=>'Inspector of Police (INSP)','ASP'=>'Assistant Superintendent of Police (ASP)','SP'=>'Superintendent of Police (SP)','SSP'=>'Senior Superintendent of Police (SSP)','ACP'=>'Assistant Commissioner of Police (ACP)','SACP'=>'Senior Assistant Commissioner of Police (SACP)','DCP'=>'Deputy Commissioner of Police (DCP)','CP'=>'Commissioner of Police (CP)','IGP'=>'Inspector General of Police (IGP)']],
                                        ['label'=>'National Identification Number','name'=>'nin','type'=>'text'],
                                        ['label'=>'Company','name'=>'company_id','type'=>'select','options'=>$companies->pluck('description','id')->toArray()],
                                        ['label'=>'First Name','name'=>'firstName','type'=>'text','required'=>true],
                                        ['label'=>'Middle Name','name'=>'middleName','type'=>'text','required'=>true],
                                        ['label'=>'Last Name','name'=>'lastName','type'=>'text','required'=>true],
                                        ['label'=>'Gender','name'=>'gender','type'=>'select','options'=>['Male'=>'Male','Female'=>'Female']],
                                        ['label'=>'Date of Birth','name'=>'DoB','type'=>'date'],
                                        ['label'=>'Marital Status','name'=>'maritalStatus','type'=>'select','options'=>['Single'=>'Single','Married'=>'Married','Divorsed'=>'Divorsed','Complicated'=>'Complicated']],
                                        ['label'=>'Religion','name'=>'religion','type'=>'text'],
                                        ['label'=>'Tribe','name'=>'tribe','type'=>'text'],
                                        ['label'=>'Phone Number','name'=>'phoneNumber','type'=>'text'],
                                        ['label'=>'Email Address','name'=>'email','type'=>'email','required'=>true],
                                        ['label'=>'Current Address','name'=>'currentAddress','type'=>'text'],
                                        ['label'=>'Permanent Address','name'=>'permanentAddress','type'=>'text'],
                                    ] as $field)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <label class="form-label">{{ $field['label'] }}</label>
                                                    @if(isset($field['options']))
                                                        <select class="form-select" name="{{ $field['name'] }}">
                                                            <option selected disabled>Select {{ $field['label'] }}</option>
                                                            @foreach($field['options'] as $key=>$value)
                                                                <option value="{{ $key }}" {{ old($field['name']) == $key ? 'selected':'' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="{{ $field['type'] }}" class="form-control" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" {{ $field['required'] ?? false ? 'required':'' }}>
                                                    @endif
                                                    @error($field['name'])
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- STEP 2: PROFESSIONAL QUALIFICATIONS -->
                            <div id="step-2b" class="tab-pane">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Department</label>
                                                <select class="form-select" name="department_id">
                                                    <option selected disabled>Choose Department</option>
                                                    @foreach ($departments as $dep)
                                                        <option value="{{ $dep->id }}" {{ old('department_id') == $dep->id ? 'selected':'' }}>
                                                            {{ $dep->departmentName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('department_id')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Designation</label>
                                                <input type="text" class="form-control" name="designation" value="{{ old('designation') }}">
                                                @error('designation')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Role</label>
                                                <select multiple class="form-control" name="roles[]">
                                                    @foreach ($roles as $key=>$label)
                                                        <option value="{{ $key }}" {{ (collect(old('roles'))->contains($key)) ? 'selected':'' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('roles[]')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Education Level</label>
                                                <select class="form-select" name="educationLevel">
                                                    <option selected disabled>Choose Education Level</option>
                                                    @foreach(['std7'=>'Darasa la Saba','4m4'=>'Form Four','4m6'=>'Form Six','Certificate'=>'Certificate','Diploma'=>'Diploma','Degree'=>'Bachelor Degree','Masters'=>'Masters','PhD'=>'PhD'] as $key=>$label)
                                                        <option value="{{ $key }}" {{ old('educationLevel') == $key ? 'selected':'' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('educationLevel')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Contract Type</label>
                                                <select class="form-select" name="contractType">
                                                    <option selected disabled>Choose Contract Type</option>
                                                    @foreach(['Permanent','Temporary','Fixed-Term','Probationary'] as $type)
                                                        <option value="{{ $type }}" {{ old('contractType') == $type ? 'selected':'' }}>{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                                @error('contractType')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Date of Joining</label>
                                                <input type="date" class="form-control" name="joiningDate" value="{{ old('joiningDate') }}">
                                                @error('joiningDate')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <label class="form-label">Location</label>
                                                <input type="text" class="form-control" name="location" value="{{ old('location') }}">
                                                @error('location')<div class="error">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 3: NEXT OF KIN -->
                            <div id="step-2c" class="tab-pane">
                                <div class="row g-3">
                                    @foreach([
                                        ['label'=>'Full Names','name'=>'nextofkinFullname','type'=>'text'],
                                        ['label'=>'Relationship','name'=>'nextofkinRelationship','type'=>'text'],
                                        ['label'=>'Phone Number','name'=>'nextofkinPhoneNumber','type'=>'text'],
                                        ['label'=>'Physical Address','name'=>'nextofkinPhysicalAddress','type'=>'text'],
                                    ] as $field)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <label class="form-label">{{ $field['label'] }}</label>
                                                    <input type="text" class="form-control" name="{{ $field['name'] }}" value="{{ old($field['name']) }}">
                                                    @error($field['name'])
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-floppy-disk"></i> Submit
                            </button>
                        </div>

                    </div>
                </form>

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