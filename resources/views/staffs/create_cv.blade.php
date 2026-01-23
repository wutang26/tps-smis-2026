@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Staffs</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Update Curriculum Vitae (CV) </a>
                    </li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->
@endsection
@section('content')
    @include('layouts.sweet_alerts.index')
    <!-- Check if there are any validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('layouts.sweet_alerts.index')
    <!-- Custom tabs start -->
    <div class="custom-tabs-container">

        <!-- Nav tabs start -->
        <ul class="nav nav-tabs" id="customTab2" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab" aria-controls="oneA"
                    aria-selected="true"><i class="bi bi-person me-2"></i>Personal
                    Particulars</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab" aria-controls="twoA"
                    aria-selected="false"><i class="bi bi-info-circle me-2"></i>Education and Training</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab" aria-controls="threeA"
                    aria-selected="false"><i class="bi bi-credit-card-2-front me-2"></i>Other Courses, Proffession
                    examination </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab" aria-controls="fourA"
                    aria-selected="false"><i class="bi bi-eye-slash me-2"></i>Work Exprience</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-fiveA" data-bs-toggle="tab" href="#fiveA" role="tab" aria-controls="fiveA"
                    aria-selected="false"><i class="bi bi-eye-slash me-2"></i>Referees</a>
            </li>
        </ul>

        <div class="tab-content h-300">
            <div class="tab-pane fade show active" id="oneA" role="tabpanel">

                <!-- Row starts -->
                <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                    action="{{route('staff.update-cv', ['staffId' => $staff->id])}}">
                    @csrf
                    @method('POST')
                    <h3>Father's particulars</h3><br><br>
                    <div class="row gx-4">
                        @php
                            if (is_string($staff->fatherParticulars)) {
                                $fatherParticulars = json_decode($staff->fatherParticulars, true);
                            } else {
                                $fatherParticulars = $staff->fatherParticulars; // already array/null
                            }
                        @endphp


                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Names </label>
                                        <input type="text" value="{{$fatherParticulars[0] ?? null}}" class="form-control"
                                            id="father_names" name="father_names" placeholder="Enter father's names">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Village 0f Birth </label>
                                        <input type="text" value="{{$fatherParticulars[1] ?? null}}" class="form-control"
                                            id="father's_names" name="father_village_of_birth"
                                            placeholder="Enter father's village of birth">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Ward 0f Birth </label>
                                        <input type="text" value="{{$fatherParticulars[2] ?? null}}" class="form-control"
                                            id="father's_names" name="father_ward_of_birth"
                                            placeholder="Enter father's Ward of birth">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">District 0f Birth </label>
                                        <input type="text" value="{{$fatherParticulars[3] ?? null}}" class="form-control"
                                            id="father's_names" name="father_district_of_birth"
                                            placeholder="Enter father's district of birth">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Region 0f Birth </label>
                                        <input type="text" value="{{$fatherParticulars[4] ?? null}}" class="form-control"
                                            id="father's_names" name="father_region_of_birth"
                                            placeholder="Enter father's region of birth">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        if (is_string($staff->motherParticulars)) {
                            $motherParticulars = json_decode($staff->motherParticulars, true);
                        } else {
                            $motherParticulars = $staff->motherParticulars; // already array/null
                        }
                    @endphp
                    <h3>Mother's particulars</h3><br>
                    <div class="row gx-4">
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Names </label>
                                        <input type="text" value="{{$motherParticulars[0] ?? null}}" class="form-control"
                                            id="father_names" name="mother_names" placeholder="Enter father's names">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Village 0f Birth </label>
                                        <input type="text" value="{{$motherParticulars[1] ?? null}}" class="form-control"
                                            id="father's_names" name="mother_village_of_birth"
                                            placeholder="Enter father's village of birth">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Ward 0f Birth </label>
                                        <input type="text" value="{{$motherParticulars[2] ?? null}}" class="form-control"
                                            id="father's_names" name="mother_ward_of_birth"
                                            placeholder="Enter father's Ward of birth">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">District 0f Birth </label>
                                        <input type="text" value="{{$motherParticulars[3] ?? null}}" class="form-control"
                                            id="father's_names" name="mother_district_of_birth"
                                            placeholder="Enter mother's district of birth">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Region 0f Birth </label>
                                        <input type="text" value="{{$motherParticulars[4] ?? null}}" class="form-control"
                                            id="father's_names" name="mother_region_of_birth"
                                            placeholder="Enter mother's region of birth">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        if (is_string($staff->parentsAddress)) {
                            $parentsAddress = json_decode($staff->parentsAddress, true);
                        } else {
                            $parentsAddress = $staff->parentsAddress; // already array/null
                        }
                    @endphp
                    <h3>Parent current addres</h3><br>
                    <div class="row gx-4">
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Village </label>
                                        <input type="text" value="{{$parentsAddress[0] ?? null}}" class="form-control"
                                            id="father's_names" name="parentsVillage" placeholder="Enter current village">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Ward</label>
                                        <input type="text" value="{{$parentsAddress[1] ?? null}}" class="form-control"
                                            id="father's_names" name="parentsWard" placeholder="Enter current ward">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">District</label>
                                        <input type="text" value="{{$parentsAddress[2] ?? null}}" class="form-control"
                                            id="father's_names" name="parentsDistrict" placeholder="Enter current district">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Region</label>
                                        <input type="text" value="{{$parentsAddress[3] ?? null}}" class="form-control"
                                            id="father's_names" name="parentsRegion" placeholder="Enter current region">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" type="submit">Update</button>
                    </div>
                </form>
                <!-- Row ends -->

            </div>
            <div class="tab-pane fade container" id="twoA" role="tabpanel">
                @php
                    $primary_school = $staff->schools()->where('education_level_id', 1)->first();
                    if (is_string($primary_school)) {
                        $primary_school = json_decode($primary_school, true);
                    }
                @endphp
                <!-- Row starts -->
                <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                    action="{{route('staff.update_school-cv', ['staffId' => $staff->id])}}">
                    @csrf
                    @method('POST')
                    <h3>Primary Schools</h3><br><br>
                    <div class="row gx-4">

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Name </label>
                                        <input type="text" value="{{ $primary_school->name ?? '' }}" class="form-control"
                                            id="" name="primary_school_name" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Village </label>
                                        <input type="text" value="{{ $primary_school->village ?? ''}}" class="form-control"
                                            id="" name="primary_school_village" placeholder="Village">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Ward </label>
                                        <input type="text" value="{{ $primary_school->ward ?? '' }}" class="form-control"
                                            id="" name="primary_school_ward" placeholder="Ward">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">District </label>
                                        <input type="text" value="{{ $primary_school->district ?? '' }}" class="form-control"
                                            id="" name="primary_school_district" placeholder="District">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Region </label>
                                        <input type="text" value="{{ $primary_school->region ?? ''}}" class="form-control"
                                            id="" name="primary_school_region" placeholder="Region">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Year of Admission </label>
                                        <input type="text" value="{{ $primary_school->admission_year ?? '' }}"
                                            class="form-control" id="" name="primary_school_YoA" placeholder="1982">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Year of Graduation </label>
                                        <input type="text"
                                            value="{{ $primary_school->graduation_year ?? ''                                                                                                                                                                                                                       }}"
                                            class="form-control" id="" name="primary_school_YoG" placeholder="1988">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-sm-4 col-12">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="m-0">
                                        <label class="form-label" for="abc">Education Level </label>
                                        <select class="form-control" name="" id="">
                                            <option value="" disabled>select level</option>
                                            @foreach ($education_levels as $education_level)
                                                <option value="{{$education_level->id}}">{{$education_level->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                        </div> -->

                    </div>
                    <div class=" mt-4">

                        <!-- Secondary Schools Section -->
                        @php
                            $secondary_school = $staff->schools()->where('education_level_id', 2)->first();
                            if (is_string($primary_school)) {
                                $secondary_school = json_decode($secondary_school, true);
                            }
                        @endphp
                        <h3 class="mb-3">Secondary Schools</h3>
                        <div class="row gx-4 mb-4">
                            <input type="hidden" name="secondary_school_type" value="2">

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" value="{{ $secondary_school->name ?? '' }}"
                                            name="secondary_school_name" placeholder="Name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Village</label>
                                        <input type="text" class="form-control"
                                            value="{{ $secondary_school->village ?? '' }}" name="secondary_school_village"
                                            placeholder="Village">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Ward</label>
                                        <input type="text" class="form-control" value="{{ $secondary_school->ward ?? '' }}"
                                            name="secondary_school_ward" placeholder="Ward">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">District</label>
                                        <input type="text" class="form-control"
                                            value="{{ $secondary_school->district ?? '' }}" name="secondary_school_district"
                                            placeholder="District">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Region</label>
                                        <input type="text" class="form-control" value="{{ $secondary_school->region ?? '' }}"
                                            name="secondary_school_region" placeholder="Region">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Year of Admission</label>
                                        <input type="text" class="form-control"
                                            value="{{ $secondary_school->admission_year ?? '' }}" name="secondary_school_YoA"
                                            placeholder="1982">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Year of Graduation</label>
                                        <input type="text" class="form-control"
                                            value="{{ $secondary_school->graduation_year ?? '' }}"
                                            name="secondary_school_YoG" placeholder="1988">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Secondary Schools Section -->
                        @php
                            $advanced_secondary_school = $staff->schools()->where('education_level_id', 3)->first();
                            if (is_string($advanced_secondary_school)) {
                                $advanced_secondary_school = json_decode($advanced_secondary_school, true);
                            }
                        @endphp
                        <h3 class="mb-3">Advanced Secondary School</h3>
                        <div class="row gx-4">
                            <input type="hidden" name="advanced_secondary_school_type" value="3">

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->name ?? '' }}"
                                            name="advanced_secondary_school_name" placeholder="Name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Village</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->village ?? '' }}"
                                            name="advanced_secondary_school_village" placeholder="Village">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Ward</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->ward ?? '' }}"
                                            name="advanced_secondary_school_ward" placeholder="Ward">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">District</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->district ?? '' }}"
                                            name="advanced_secondary_school_district" placeholder="District">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Region</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->region ?? '' }}"
                                            name="advanced_secondary_school_region" placeholder="Region">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Year of Admission</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->admission_year ?? '' }}"
                                            name="advanced_secondary_school_YoA" placeholder="1982">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <label class="form-label">Year of Graduation</label>
                                        <input type="text" class="form-control"
                                            value="{{ $advanced_secondary_school->graduation_year ?? '' }}"
                                            name="advanced_secondary_school_YoG" placeholder="1988">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
@php
    $colleges = $staff->schools()->where('education_level_id', 4)->get();
@endphp                    

<h3>Universities/Colleges</h3><br><br>
<div id="college-container">

    @if($colleges->count() > 0)
        @foreach($colleges as $college)
            <div class="row gx-4 college-entry">
                <div class="col-sm-4 col-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="m-0">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="colleges_name[]" 
                                    value="{{ $college->name }}" placeholder="Name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="m-0">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" name="duration[]" 
                                    value="{{ $college->duration }}" placeholder="1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="m-0">
                                <label class="form-label">Region/Country</label>
                                <input type="text" class="form-control" name="colleges_name_region[]" 
                                    value="{{ $college->country ?? $college->region }}" placeholder="Tanzania">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="m-0">
                                <label class="form-label">Award</label>
                                <input type="text" class="form-control" name="colleges_award[]" 
                                    value="{{ $college->award }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="m-0">
                                <label class="form-label">Year of Admission</label>
                                <input type="number" class="form-control" name="colleges_YoA[]" 
                                    value="{{ $college->admission_year }}" placeholder="1982">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="m-0">
                                <label class="form-label">Year of Graduation</label>
                                <input type="number" class="form-control" name="colleges_YoG[]" 
                                    value="{{ $college->graduation_year }}" placeholder="1988">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remove button -->
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-college">Remove</button>
                </div>
            </div>
        @endforeach
    @else
        {{-- Default empty block if no college exists --}}
        <div class="row gx-4 college-entry">
            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="colleges_name[]" placeholder="Name">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" name="duration[]" placeholder="1">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label">Region/Country</label>
                            <input type="text" class="form-control" name="colleges_name_region[]" placeholder="Tanzania">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label">Award</label>
                            <input type="text" class="form-control" name="colleges_award[]">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label">Year of Admission</label>
                            <input type="number" class="form-control" name="colleges_YoA[]" placeholder="1982">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label">Year of Graduation</label>
                            <input type="number" class="form-control" name="colleges_YoG[]" placeholder="1988">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remove button -->
            <div class="col-12 text-end">
                <button type="button" class="btn btn-danger btn-sm remove-college">Remove</button>
            </div>
        </div>
    @endif
</div>


<!-- Add button -->
<div class="mt-3">
    <button type="button" id="add-college" class="btn btn-secondary">+ Add Another College/University</button>
</div>


                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" type="submit">Update</button>
                    </div>
                </form>
                <!-- Row ends -->

            </div>
            <div class="tab-pane fade" id="threeA" role="tabpanel">
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#other">Add</button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>S/NO</td>
                            <th style="width:100px;">Duration</th>
                            <th>College/Organization</th>
                            <th>Theme and Award</th>
                            <th>Venue</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @if ($staff->schools)
                            @foreach ($staff->schools as $school)
                                @if ($school->education_level_id == 5)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{ $school->duration }}</td>
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->award }}</td>
                                        <td>{{ $school->venue }}</td>
                                        <td class="">
                                            <!-- <a href="" class="btn btn-sm btn-warning">Edit</a> -->
                                            <form id="deleteSchoolForm{{ $school->id }}"
                                                action="{{route('staff.delete_school', ['schoolId' => $school->id])}}" method='POST'>
                                                @csrf
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('deleteSchoolForm{{ $school->id }}', 'Other Course or Proffessional ')"
                                                    type="button">Delete</button>
                                            </form>
                                            @include('layouts.sweet_alerts.confirm_delete')
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>

                </table>
                <div class="modal fade" id="other" tabindex="-1" aria-labelledby="other" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newLabel">Add work or Experience</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                                    action="{{route('staff.update_other_courses-cv', ['staffId' => $staff->id])}}">
                                    @csrf
                                    @method('POST')
                                    <!-- Row starts -->
                                    <div class="row gx-4">
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">College/Organization </label>
                                                        <input type="text" class="form-control" id="" name="college"
                                                            required placeholder="Name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Duration </label>
                                                        <input type="text" class="form-control" id="" required
                                                            name="duration" placeholder="1 month">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Theme and Award </label>
                                                        <input type="text" class="form-control" id="" name="award" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Venue </label>
                                                        <input type="text" class="form-control" id="" name="venue" required
                                                            placeholder="Tanzania">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-primary" type="submit">Update</button>
                                        </div>
                                    </div><br>
                                </form>
                            </div>

                        </div>



                    </div>

                </div>


                <!-- Row ends -->

            </div>
            <div class="tab-pane fade" id="fourA" role="tabpanel">

                <!-- Row starts -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-2">
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#new">Add</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>S/NO</td>
                                    <th>ORGANIZATION</th>
                                    <th>LOCATION</th>
                                    <th>TITLE</th>
                                    <th>DUTIES</th>
                                    <th>From-To</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($staff->work_experiences as $work_experience)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$work_experience->institution}}</td>
                                        <td>{{$work_experience->address}}</td>
                                        <td>{{$work_experience->job_title}}</td>
                                        <td>
                                            @php
                                                $duties = $work_experience->duties == null ? null :
                                                    json_decode($work_experience->duties);
                                            @endphp
                                            <ul>
                                                @foreach ($duties as $duty)
                                                    <li>{{ $duty }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{$work_experience->start_date}} - {{$work_experience->end_date ?? ''}}</td>
                                        <td class="">
                                            <!-- <a href="" class="btn btn-sm btn-warning">Edit</a> -->
                                            <form id="deleteForm{{ $work_experience->id }}"
                                                action="{{route('staff.delete_experience', ['experienceId' => $work_experience->id])}}"
                                                method='POST'>
                                                @csrf
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('deleteForm{{ $work_experience->id }}', 'Work experience ')"
                                                    type="button">Delete</button>
                                            </form>
                                            @include('layouts.sweet_alerts.confirm_delete')
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="modal fade" id="new" tabindex="-1" aria-labelledby="new" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newLabel">Add work or Experience</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="work_experienceForm"
                                        action="{{route('staff.update_work_experience-cv', ['staffId' => $staff->id])}}"
                                        method="POST">
                                        @csrf
                                        <div class="row gx-4">
                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="m-0">
                                                            <label class="form-label" for="abc">From </label>
                                                            <input type="number" min='1960' class="form-control" id=""
                                                                name="start_date" required placeholder="1990">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="m-0">
                                                            <label class="form-label" for="abc">To </label>
                                                            <input type="number" min="1" class="form-control" id=""
                                                                type="number" min='1960' name="end_date" placeholder="1996">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="m-0">
                                                            <label class="form-label" for="abc">Organization/Institution
                                                            </label>
                                                            <input type="text" class="form-control" id="" name="institution"
                                                                required placeholder="TPS">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="m-0">
                                                            <label class="form-label" for="abc">Title </label>
                                                            <input type="text" class="form-control" id="" name="job_title">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="m-0">
                                                            <label class="form-label" for="abc">Location</label>
                                                            <input type="text" class="form-control" id="" name="address"
                                                                required placeholder="Arusha">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-12">
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="m-0">
                                                            <label class="form-label" for="abc">Duties </label>
                                                            <div class="d-flex gap-2 mb-2 duty-row" data-index="0">
                                                                <textarea class="form-control" id="duties" name="duties[]"
                                                                    placeholder="Enter job duties here..."
                                                                    rows="2"></textarea>
                                                                <button style="height:40px;" type="button"
                                                                    class="btn btn-danger delete-duty-btn"
                                                                    onclick="deleteDuty(0)">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button id="addDutyButton" class="btn btn-sm btn-warning" type="button">Add
                                                Duty</button>
                                        </div>
                                    </form>
                                    <script
                                        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
                                        </script>
                                    <div id="dutyContainer">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="submitWorkExperienceForm()"
                                            class="btn btn-primary">Update</button>
                                    </div>

                                </div>



                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="fiveA" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-2">
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#newReferee">Add</button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>S/N</td>
                                    <th>NAME</th>
                                    <th>TITLE</th>
                                    <th>ORGANIZATION</th>
                                    <th>ADDRESS</th>
                                    <th>EMAIL</th>
                                    <th>PHONE</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($staff->referees as $referee)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$referee->referee_fullname}}</td>
                                        <td>{{$referee->title}}</td>
                                        <td>{{$referee->organization}}</td>
                                        <td>{{$referee->address}}</td>
                                        <td>{{$referee->email_address}}</td>
                                        <td>{{$referee->phone_number}}</td>

                                        <td class="">
                                            <!-- <a href="" class="btn btn-sm btn-warning">Edit</a> -->
                                            <form id="deleteForm{{ $referee->id }}"
                                                action="{{ route('referees.delete', $referee) }}" method='DELETE'>
                                                @csrf
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('deleteForm{{ $referee->id }}', 'Referee ')"
                                                    type="button">Delete</button>
                                            </form>
                                            @include('layouts.sweet_alerts.confirm_delete')
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="modal fade" id="newReferee" tabindex="-1" aria-labelledby="newRefereeModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-info" id="newRefereeModalLabel">
                                            Add Referees Details
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="refereeForm" action="{{route('referees.store')}}" method="post">
                                            @csrf
                                            <div class="row gx-4">
                                                <div class="col-sm-6 col-12">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc">Names </label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="referee_fullname" required
                                                                    placeholder="HARUNI SAIDI SIDINGI">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" id="" name="user_id"
                                                        value="{{$staff->user->id}}" hidden>
                                                </div>
                                                <div class="col-sm-6 col-12">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc">Title </label>
                                                                <input type="text" class="form-control" id="" name="title"
                                                                    required placeholder="Manager">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-12">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc">Organization </label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="organization" placeholder="Manager">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-12">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc">Address </label>
                                                                <input type="text" class="form-control" id="" name="address"
                                                                    placeholder="Dodoma">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-12">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc">Email </label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="email_address" required
                                                                    placeholder="example@gmail.com">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-12">
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label" for="abc">Phone </label>
                                                                <input type="text" class="form-control" id="" required
                                                                    name="phone_number" placeholder="0765100100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" onclick="submiRefereeForm()"
                                                    class="btn btn-primary">Update</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row ends -->

            </div>
        </div>

    </div>

    <script>
        function submitWorkExperienceForm() {
            document.getElementById('work_experienceForm').submit();
        }
        function submiRefereeForm() {
            document.getElementById('refereeForm').submit();
        }
        let dutyIndex = 1;

        document.getElementById('addDutyButton').addEventListener('click', () => {
            const container = document.createElement('div');
            container.className = 'd-flex gap-2 mb-2 duty-row';
            container.setAttribute('data-index', dutyIndex);

            container.innerHTML = `
            <textarea class="form-control" name="duties[]" placeholder="Enter job duties here..." rows="2"></textarea>
            <button style="height:40px;" type="button" class="btn btn-danger delete-duty-btn" onclick="deleteDuty(${dutyIndex})">Delete</button>
        `;
            document.querySelector('[name="duties[]"]').parentNode.parentNode.appendChild(container);
            dutyIndex++;
            checkAndDisableDeleteButton();
        });

        function deleteDuty(index) {
            // Find all duty rows
            const allDutyRows = document.querySelectorAll('.duty-row');

            allDutyRows.forEach(row => {
                if (parseInt(row.getAttribute('data-index')) === index) {
                    row.remove();
                }
            });

            // Optional: reindex remaining rows
            reindexDutyRows();
            checkAndDisableDeleteButton();
        }

        function reindexDutyRows() {
            const rows = document.querySelectorAll('.duty-row');
            rows.forEach((row, i) => {
                row.setAttribute('data-index', i);
                const button = row.querySelector('.delete-duty-btn');
                if (button) {
                    button.setAttribute('onclick', `deleteDuty(${i})`);
                }
            });
        }

        function checkAndDisableDeleteButton() {
            const taskRows = document.querySelectorAll('.duty-row');
            const deleteButtons = document.querySelectorAll('.delete-duty-btn');

            // If only one task is left, disable the delete button for that task
            if (taskRows.length === 1) {
                deleteButtons.forEach(button => {
                    button.disabled = true; // Disable all delete buttons
                });
            } else {
                // Enable the delete buttons for all tasks
                deleteButtons.forEach(button => {
                    button.disabled = false;
                });
            }
        }

        // Run the check on initial load to ensure proper state
        document.addEventListener('DOMContentLoaded', checkAndDisableDeleteButton);


document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("college-container");
    const addBtn = document.getElementById("add-college");

    // function to update remove buttons state
    function updateRemoveButtons() {
        const entries = container.querySelectorAll(".college-entry");
        entries.forEach(btn => {
            const removeBtn = btn.querySelector(".remove-college");
            removeBtn.disabled = (entries.length === 1); // disable if only one
        });
    }

    addBtn.addEventListener("click", function () {
        const firstEntry = container.querySelector(".college-entry");
        const clone = firstEntry.cloneNode(true);

        // clear inputs in cloned node
        clone.querySelectorAll("input").forEach(input => input.value = "");

        container.appendChild(clone);
        updateRemoveButtons();
    });

    container.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-college")) {
            const entries = container.querySelectorAll(".college-entry");
            if (entries.length > 1) {
                e.target.closest(".college-entry").remove();
                updateRemoveButtons();
            }
        }
    });

    // run once on load
    updateRemoveButtons();
});

    </script>
@endsection