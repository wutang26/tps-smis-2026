@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Create</a></li>
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
<!-- Form wizard starts -->
<form name="add-blog-post-form" id="add-blog-post-form" method="POST" action="{{url('students/store')}}">
    @csrf
    @method('POST')
    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Force Number </label>
                        <input type="text" class="form-control" id="force_number" name="force_number"
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
                        <select class="form-select" id="abc4" name="rank" required
                            aria-label="Default select example">
                            <option value="Recruit">Recruit</option>
                            <option value="Copral">Copral</option>
                            <option value="Sergent">Copral</option>
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
                        <input type="text" class="form-control" id="first_name" name="first_name"
                        required  placeholder="Enter firstname">
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
                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                        required placeholder="Enter middlename">
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
                        <input type="text" class="form-control" id="last_name" name="last_name"
                        required placeholder="Enter lastname">
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
                        <label class="form-label" for="abc4">Education Level</label>
                        <select class="form-select" id="abc4" name="education_level" required
                            aria-label="Default select example">
                            <!-- <option selected="">select gender</option> -->
                            <option value="Form Four">Form Four</option>
                            <option value="Form Six">Form Six</option>
                            <option value="Certificate">Certificate</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Degree">Degree</option>

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
                        <label class="form-label" for="abc">Home Region</label>
                        <input type="text" class="form-control" id="home_region" name="home_region"
                        required placeholder="Enter home region">
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
                        <label class="form-label" for="abc">NIDA</label>
                        <input type="number" class="form-control" id="last_name" name="nin"
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
                        <input type="number" class="form-control" id="phone" name="phone"
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
                            <option value="M">Male</option>
                            <option value="F">Female</option>
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
                            <input type="date" id="abc3" max="2007-07-01" required name="dob" class="form-control datepicker" />
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
                        <select class="form-select" name="company" id="abc4" required aria-label="Default select example">
                            <!-- <option >select company</option> -->
                            <option value="HQ">HQ</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
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
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
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
                        <input type="number" class="form-control" id="height" name="height"
                            placeholder="Enter height in feet">
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
                        <input type="number" class="form-control" id="weight" name="weight"
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
                            <option value="A+">A+</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="O+">O+</option>
                        </select>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>



@endsection