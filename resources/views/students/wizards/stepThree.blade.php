@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">
                @if(isset($student))
                            Update
                        @else
                            Create
                        @endif
                     Final Step</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<div class="col-md-2 text-left">
                        <button onclick="history.back()" class="btn btn-primary">Previous</button>
                    </div>
<h1>Next of Kin information</h1>
<?php $typeToAppend ="";
    if(isset(($student))){
        $typeToAppend = "edit";

    }else {
         $typeToAppend = "create";
    }
?>
<form action="{{url('students/create/post-step-three/'.$typeToAppend)}}" method="POST">
    @csrf
    @method('POST')

    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Next Kin names</label>
                        <input @if(isset($student)) value="{{$student->next_kin_names}}" @endif type="text" class="form-control" id="last_name" name="next_kin_names" 
                            placeholder="Enter next kin names" value="{{old('next_kin_names')}}">
                    </div>
                    @error('next_kin_names')
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
                        <input @if(isset($student)) value="{{$student->next_kin_phone}}" @endif type="number" class="form-control" id="phone" name="next_kin_phone" 
                            placeholder="Enter phone number" value="{{old('next_kin_phone')}}">
                    </div>
                    @error('next_kin_phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Relationship</label>
                        <input @if(isset($student)) value="{{$student->next_kin_relationship}}" @endif type="text" class="form-control" id="phone" name="next_kin_relationship"
                            placeholder="Enter relationship" value="{{old('next_kin_relationship')}}">
                    </div>
                    @error('next_kin_relationship')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Next Kin Address</label>
                        <input @if(isset($student)) value="{{$student->next_kin_address}}" @endif type="text" class="form-control" id="phone" name="next_kin_address"
                            placeholder="Enter address" value="{{old('next_kin_address')}}">
                    </div>
                    @error('next_kin_address')
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

                    <button class="btn btn-primary">
                    @if(isset($student))
                            Save
                        @else
                            Submit
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection