@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">
                @if(isset($student))
                            Update
                        @else
                            Create
                        @endif
                        Step Two</a></li>
                </a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<?php $typeToAppend ="";
    if(isset(($student))){
        $typeToAppend = "edit";

    }else {
         $typeToAppend = "create";
    }
?>
                    <div class="col-md-2 text-left">
                        <button onclick="history.back()" class="btn btn-primary">Previous</button>
                    </div>
<form action="{{url('students/create/post-step-two/'.$typeToAppend)}}" method="POST">
    @csrf
    @method('POST')
    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">NIDA</label>
                        <input @if(isset($student)) value="{{$student->nin}}" @endif type="number" class="form-control" id="nin" name="nin" 
                            placeholder="Enter NIDA number" value="{{old('nin')}}">
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
                        <input @if(isset($student)) value="{{$student->phone}}" @endif type="number" class="form-control" id="phone" name="phone"
                            placeholder="Enter phone number" value="{{old('phone')}}">
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
                        <select  class="form-select" id="abc4" name="gender" required
                            aria-label="Default select example">
                            <option selected="" disabled>select gender</option>
                            <option @if(isset($student)) @if($student->gender == "M") selected @endif @endif value="M">Male</option>
                            <option @if(isset($student)) @if($student->gender == "F") selected @endif @endif value="F">Female</option>
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
                            <input @if(isset($student)) value="{{$student->dob}}" @endif type="date" id="abc3" max="2007-07-01" value="{{old('dob')}}"  name="dob"
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
                        <select class="form-select" name="company_id" id="abc4" required
                            aria-label="Default select example">
                            
                            <option selected disabled >select company</option>
                            @foreach ($companies as $company)
                            <option @if(isset($student) && $student->company_id == $company->id) selected @endif value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    @error('company_id')
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
                            <option selected disabled>select platoon</option>
                            <option @if(isset($student) && $student->platoon == "1") selected @endif value="1">1</option>
                            <option @if(isset($student) && $student->platoon == "2") selected @endif value="2">2</option>
                            <option @if(isset($student) && $student->platoon == "3") selected @endif value="3">3</option>
                            <option @if(isset($student) && $student->platoon == "4") selected @endif value="4">4</option>
                            <option @if(isset($student) && $student->platoon == "5") selected @endif value="5">5</option>
                            <option @if(isset($student) && $student->platoon == "6") selected @endif value="6">6</option>
                            <option @if(isset($student) && $student->platoon == "7") selected @endif value="7">7</option>
                            <option @if(isset($student) && $student->platoon == "8") selected @endif value="8">8</option>
                            <option @if(isset($student) && $student->platoon == "9") selected @endif value="9">9</option>
                            <option @if(isset($student) && $student->platoon == "10") selected @endif value="10">10</option>
                            <option @if(isset($student) && $student->platoon == "11") selected @endif value="11">11</option>
                            <option @if(isset($student) && $student->platoon == "12") selected @endif value="12">12</option>
                            <option @if(isset($student) && $student->platoon == "13") selected @endif value="13">13</option>
                            <option @if(isset($student) && $student->platoon == "14") selected @endif value="14">14</option>
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
                        <label class="form-label" for="abc4">Blood Group</label>
                        <select class="form-select" name="blood_group" id="abc4" aria-label="Default select example">
                            <option value=""> select blood group</option>
                            <option @if(isset($student) && $student->blood_group == "A+") selected @endif value="A+">A+</option>
                            <option @if(isset($student) && $student->blood_group == "A") selected @endif value="A">A</option>
                            <option @if(isset($student) && $student->blood_group == "B") selected @endif value="B">B</option>
                            <option @if(isset($student) && $student->blood_group == "O+") selected @endif value="O+">O+</option>
                        </select>
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
                        <label class="form-label" for="abc">Weight (in Kg)</label>
                        <input @if(isset($student)) value="{{$student->weight}}" @endif type="number" step="0.1" class="form-control" id="weight" name="weight" 
                            placeholder="Enter weight" value="{{old('weight')}}">
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
                        <label class="form-label" for="abc">Height (in ft)</label>
                        <input @if(isset($student)) value="{{$student->height}}" @endif type="number" min="4" step="0.1" class="form-control" id="height" name="height"
                            placeholder="Enter height" value="{{old('height')}}">
                    </div>
                    @error('weight')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-md-6 text-left">

            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">

                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection