@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
@session('success')
    <div class="alert alert-success alert-dismissible " role="alert">
        {{ $value }}
    </div>
@endsession
<form name="add-blog-post-form" id="add-blog-post-form" method="post"
    action="{{url('students/' . $student->id . '/update')}}">
    @csrf
    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Force Number </label>
                        <input @if(isset($student->force_number)) value = "{{$student->force_number}}" @endif type="text" class="form-control" id="force_number" name="force_number"
                            placeholder="Enter force number">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Rank</label>
                        <select class="form-select" id="abc4" name="rank" required aria-label="Default select example">
                            <!-- <option selected="">select gender</option> -->
                            <option <?php if ($student->rank == "RC") {?> selected<?php } ?> value="RC">Recruit</option>
                            <option <?php if ($student->rank == "CPL") {?> selected<?php } ?> value="CPL">Copral</option>
                            <option <?php if ($student->rank == "SGT") {?> selected<?php } ?> value="SGT">Copral</option>
                        </select>
                    </div>
                    @error('rank')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">First Name</label>
                        <input value="{{$student->first_name}}" type="text" class="form-control" id="first_name"
                            name="first_name" required placeholder="Enter firstname">
                    </div>
                    @error('first_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Middle Name</label>
                        <input value="{{$student->middle_name}}" type="text" class="form-control" id="middle_name"
                            name="middle_name" required placeholder="Enter middlename">
                    </div>
                    @error('middle_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Last Name</label>
                        <input value="{{$student->last_name}}" type="text" class="form-control" id="last_name"
                            name="last_name" required placeholder="Enter lastname">
                    </div>
                    @error('last_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Home Region</label>
                        <input value="{{$student->home_region}}" type="text" class="form-control" id="home_region"
                            name="home_region" required placeholder="Enter home region">
                    </div>
                    @error('home_region')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Education Level</label>
                        <select class="form-select" id="abc4" name="education_level" required
                            aria-label="Default select example">
                            <!-- <option selected="">select gender</option> -->
                            <option <?php if ($student->education_level == "Form Four") {?> selected<?php } ?>
                                value="Form Four">Form Four</option>
                            <option <?php if ($student->education_level == "Form Six") {?> selected<?php } ?>
                                value="Form Six">Form Six</option>
                            <option <?php if ($student->education_level == "Certificate") {?> selected<?php } ?>
                                value="Certificate">Certificate</option>
                            <option <?php if ($student->education_level == "Diploma") {?> selected<?php } ?>
                                value="Diploma">Diploma</option>
                            <option <?php if ($student->education_level == "Degree") {?> selected<?php } ?> value="Degree">
                                Degree</option>

                        </select>
                    </div>
                    @error('education_level')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">NIDA</label>
                        <input value="{{$student->nin}}" type="number" class="form-control" id="last_name" name="nin"
                            required placeholder="Enter NIDA number">
                    </div>
                    @error('nin')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Phone</label>
                        <input value="{{$student->phone}}" type="number" class="form-control" id="phone" name="phone"
                            placeholder="Enter phone number">
                    </div>
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Gender</label>
                        <select class="form-select" id="abc4" name="gender" required
                            aria-label="Default select example">
                            <!-- <option selected="">select gender</option> -->
                            <option <?php if ($student->gender == "M") {?> selected<?php } ?> value="M">Male</option>
                            <option <?php if ($student->gender == "F") {?> selected<?php } ?> value="F">Female</option>
                        </select>
                    </div>
                    @error('gender')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc3">Date of Birth</label>
                        <div class="input-group">
                            <input value="{{$student->dob}}" type="date" id="abc3" required name="dob"
                                class="form-control datepicker" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Company</label>
                        <select class="form-select" name="company" id="abc4" required
                            aria-label="Default select example">
                            <!-- <option >select company</option> -->
                            <option <?php if ($student->company == "HQ") {?> selected<?php } ?> value="HQ">HQ</option>
                            <option <?php if ($student->company == "A") {?> selected<?php } ?> value="A">A</option>
                            <option <?php if ($student->company == "B") {?> selected<?php } ?> value="B">B</option>
                            <option <?php if ($student->company == "C") {?> selected<?php } ?> value="C">C</option>
                        </select>
                    </div>
                    @error('company')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Platoon</label>
                        <select class="form-select" name="platoon" id="abc4" aria-label="Default select example">
                            <!-- <option selected="">select platoon</option> -->
                            <option <?php if ($student->platoon == "1") {?> selected<?php } ?> value="1">1</option>
                            <option <?php if ($student->platoon == "2") {?> selected<?php } ?> value="2">2</option>
                            <option <?php if ($student->platoon == "3") {?> selected<?php } ?> value="3">3</option>
                            <option <?php if ($student->platoon == "4") {?> selected<?php } ?> value="4">4</option>
                            <option <?php if ($student->platoon == "5") {?> selected<?php } ?> value="5">5</option>
                            <option <?php if ($student->platoon == "6") {?> selected<?php } ?> value="6">6</option>
                            <option <?php if ($student->platoon == "7") {?> selected<?php } ?> value="7">7</option>
                            <option <?php if ($student->platoon == "8") {?> selected<?php } ?> value="8">8</option>
                            <option <?php if ($student->platoon == "9") {?> selected<?php } ?> value="9">9</option>
                            <option <?php if ($student->platoon == "10") {?> selected<?php } ?> value="10">10</option>
                            <option <?php if ($student->platoon == "11") {?> selected<?php } ?> value="11">11</option>
                            <option <?php if ($student->platoon == "12") {?> selected<?php } ?> value="12">12</option>
                            <option <?php if ($student->platoon == "13") {?> selected<?php } ?> value="13">13</option>
                            <option <?php if ($student->platoon == "14") {?> selected<?php } ?> value="14">14</option>
                        </select>
                    </div>
                    @error('platoon')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Height (ft)</label>
                        <input value="{{$student->height}}" type="number" class="form-control" id="height" name="height"
                            placeholder="Enter height in ft">
                    </div>
                    @error('height')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Weight (Kg)</label>
                        <input value="{{$student->weight}}" type="number" class="form-control" id="weight" name="weight"
                            placeholder="Enter weight in kg">
                    </div>
                    @error('weight')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Blood Group</label>
                        <select class="form-select" name="blood_group" id="abc4" aria-label="Default select example">
                            <option <?php if ($student->blood_group == "A+") {?> selected<?php } ?> value="A+">A+</option>
                            <option <?php if ($student->blood_group == "A") {?> selected<?php } ?> value="A">A</option>
                            <option <?php if ($student->blood_group == "B") {?> selected<?php } ?> value="B">B</option>
                            <option <?php if ($student->blood_group == "O") {?> selected<?php } ?> value="O">O+</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

@endsection