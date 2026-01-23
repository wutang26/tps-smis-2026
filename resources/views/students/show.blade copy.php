@extends('layouts.main')

@section('style')
    <style>
        .back {
            border-radius: 30% !important;
        }

        .profile-header {
            background-image: url('/tps-smis/resources/assets/images/profile/bg-profile.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 200px;
            position: relative;
        }

        .profile-header img {
            position: absolute;
            bottom: -50px;
            left: 20px;
            border-radius: 50%;
            border: 5px solid white;
        }

        .profile-header .profile-info {
            position: absolute;
            bottom: 20px;
            left: 150px;
            color: white;
        }

        .nav-tabs .nav-link.active {
            background-color: #f8f9fa;
        }

        .leftlabel {
            margin-top: 16px;
            font-weight: normal;
        }
    </style>

@endsection
@section('content')
    @include('layouts.sweet_alerts.index')
    <!-- Row starts -->
    @php
        function formatNida($value) {
            if (!is_string($value) || strlen($value) < 20) return $value;
            return substr($value, 0, 8) . '-' . substr($value, 8, 5) . '-' . substr($value, 13, 5) . '-' . substr($value, 18, 2);
        }
    @endphp
    <div class="row gx-4">
        <div class="col-sm-12 col-12">
            <div class="card mb-4">
                <div class="card-body back">
                    <div class="profile-header">
                        @if($student->photo)
                        <img src="{{ url('storage/app/public/' . $student->photo) }}" alt="{{ $student->name }}'s Photo">
                        @else
                        <img src="/tps-smis/resources/assets/images/profile/avatar.jpg" alt="Profile Picture" />
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-3 gap-2">
                        @can('beat-edit')
                        @if($student->beat_status != 6)
                            @if ($student->beat_status == 4 && $student->pendingSafari()->exists()  )
                                <form action="{{ route('returnSafariStudent', $student->pendingSafari()->first()->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT') <!-- Spoofing PUT request -->
                                    <button type="submit" class="btn btn-primary">Return</button>
                                </form>

                            @else
                                <button class="btn  btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#SafariDetails">To Safari</button>
                            @endif
                            @endif
                        @endcan

                        @can('beat-edit')
                            <a class="btn btn-{{ $student->fast_status == 0 ? 'secondary' : 'primary' }}"
                                href="{{ route('updateFastingStatus', ['studentId' => $student->id, 'fastingStatus' => $student->fast_status == 0 ? 1 : 0]) }}">
                                {{ $student->fast_status == 0 ? 'Not Fasting' : 'Fasting' }}</a>
                        @endcan()
                        @if ($student->status  != 'approved')
                            <!-- <form action="{{ route('students.approve', $student->id) }}" method="POST" style="display:inline">
                                @csrf
                                @can('student-approve')
                                    <button type="submit" class="btn btn-warning" style="margin-right:5px">Approve</button>
                                @endcan()
                            </form> -->
                            
                            <form id="confirmForm{{ $student->id }}" action="{{ route('students.approve', $student->id) }}" method="post">
                                @csrf
                                    <button onclick="confirmAction('confirmForm{{ $student->id }}', 'Verify','{{$student->force_number}} {{$student->rank}} {{$student->first_name}}','Verify')" type="button" class="btn btn-info">
                                        Verify
                                    </button>
                            </form>
                        @else
                            <button class="btn btn-success" style="margin-right:5px">Verified</button>
                        @endif
                        @can('student-edit')
                            <button id="editAboutBtn" class="btn btn-warning me-2">Edit Profile</button>
                            <button id="cancelEditBtn" class="btn btn-sm btn-danger d-none">✖ Cancel</button>
                        @endcan()
                        <div class="pull-right" style="margin-left:-5px">
                            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn btn-primary"> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-4">
        <div class="col-sm-12 col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Custom tabs start -->
                    <div class="custom-tabs-container">

                        <!-- Nav tabs start -->
                        <ul class="nav nav-tabs" id="customTab2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                                    aria-controls="oneA" aria-selected="true"><i class="bi bi-person me-2"></i> My Personal
                                    Details</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab"
                                    aria-controls="twoA" aria-selected="false"><i class="bi bi-info-circle me-2"></i>My
                                    Attendances</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab"
                                    aria-controls="threeA" aria-selected="false"><i
                                        class="bi bi-credit-card-2-front me-2"></i>My Leave(s)</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab"
                                    aria-controls="fourA" aria-selected="false"><i class="bi bi-eye-slash me-2"></i>Change
                                    Password</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-fiveA" data-bs-toggle="tab" href="#fiveA" role="tab"
                                    aria-controls="fiveA" aria-selected="false"><i
                                        class="bi bi-credit-card-2-front me-2"></i>Behavior Trend</a>
                            </li>
                        </ul>
                        <!-- Nav tabs end -->

                        <!-- Tab content start -->
                        <div class="tab-content h-300">
                            <div class="tab-pane fade show active" id="oneA" role="tabpanel">

                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="col-sm-12 col-12">
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <!-- Row starts -->
                                                <form id="studentUpdateForm" method="POST" action="{{ route('students.update', $student->id) }}" onsubmit="return validateFullNameBeforeSubmit();" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Editable fields go here -->
                                                    <div class="row gx-4">
                                                            <div class="col-sm-2 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="forceNumber" class="form-label">Force Number</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-person"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="forceNumber" value="{{$student->force_number}}" Disabled>
                                                                        <input type="text" name="force_number" class="form-control editable-field d-none" id="forceNumber" value="{{$student->force_number}}">
                                                                    </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <!-- Form field start -->
                                                                 <div class="mb-3">
                                                                    <label for="fullNameEdit" class="form-label">Full Name</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-person"></i>
                                                                        </span>

                                                                        {{-- Static field --}}
                                                                        <input type="text"
                                                                            class="form-control static-field"
                                                                            id="fullNameStatic"
                                                                            value="{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}"
                                                                            disabled>

                                                                        {{-- Editable field --}}
                                                                        <input type="text"
                                                                            name="full_name"
                                                                            class="form-control editable-field d-none"
                                                                            id="fullNameEdit"
                                                                            value="{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}"
                                                                            placeholder="Enter first, middle, and last name">

                                                                    </div>
                                                                    @if ($errors->has('full_name'))
                                                                        <div class="text-danger small">
                                                                            ⚠️ {{ $errors->first('full_name') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="yourEmail" class="form-label">Email</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-envelope"></i>
                                                                        </span>
                                                                        <input type="email" class="form-control static-field" id="yourEmail" value="{{$student->email ?? ''}}" Disabled>
                                                                        <input type="email" name="email" class="form-control editable-field d-none" id="yourEmail" value="{{$student->email ?? ''}}">
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('email'))
                                                                    <div class="text-danger small">
                                                                        ⚠️ {{ $errors->first('email') }}
                                                                    </div>
                                                                @endif
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-2 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="contactNumber" class="form-label">Contact</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-phone"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="contactNumber" value="{{$student->phone}}" Disabled>
                                                                        <input type="text" name="phone" class="form-control editable-field d-none" id="contactNumber" value="{{$student->phone}}">
                                                                    </div>
                                                                    @if ($errors->has('phone'))
                                                                        <div class="text-danger small">
                                                                            ⚠️ {{ $errors->first('phone') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-2 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="birthDay" class="form-label">Date of Birth</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-calendar4"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="birthDay" value="{{$student->dob}}" Disabled>
                                                                        <input type="date" name="dob" class="form-control editable-field d-none" id="birthDay" value="{{$student->dob}}">
                                                                    </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-12">
                                                                <!-- Form field start -->
                                                                <div class="m-0">
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-filter-circle"></i> <label class="form-label" for="abt" style="font-size: medium;"> &nbsp;&nbsp;&nbsp;About - {{$student->force_number}} {{$student->rank}} {{$student->first_name}}</label>
                                                                        </span>
                                                                    </div>
                                                                    <div class="card-body" style="background-color: rgb(209, 209, 214);">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="p-3 me-3 w-100">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="enrolledCourse" class="form-label" style="min-width: 150px; font-weight:bold;">Enrolled Course:</label>
                                                                                        <p class="static-field leftlabel" style="font-size: medium;">{{ $student->programme->programmeName ?? '-' }}</p>
                                                                                        <input type="text" class="form-control editable-field d-none" value="{{ $student->programme->programmeName ?? '' }}" disabled>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="company" class="form-label" style="min-width: 150px; font-weight:bold">Company:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->company->name ?? '-' }} - {{ $student->platoon ?? '-' }}</p>
                                                                                        <input type="text" class="form-control editable-field d-none" value="{{ $student->company->name ?? '' }} - {{ $student->platoon ?? '' }}" id="company" disabled>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="gender" class="form-label" style="min-width: 150px; font-weight:bold">Gender:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->gender ?? '-' }}</p>
                                                                                        <input type="text" class="form-control editable-field d-none" value="{{ $student->gender ?? '' }}" id="gender" disabled>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="blood_group" class="form-label" style="min-width: 150px; font-weight:bold">Blood Group:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->blood_group ?? '-' }}</p>
                                                                                        <select name="blood_group" id="blood_group" class="form-select editable-field d-none">
                                                                                            <option value="" disabled selected>Select Blood Group</option>
                                                                                            @php
                                                                                                $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                                                            @endphp
                                                                                            @foreach ($bloodGroups as $group)
                                                                                                <option value="{{ $group }}" {{ ($student->blood_group ?? '') === $group ? 'selected' : '' }}>
                                                                                                    {{ $group }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>

                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="education_level" class="form-label" style="min-width: 150px; font-weight:bold">Education Level:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->education_level ?? '-' }}</p>
                                                                                        <input type="text" name="education_level" class="form-control editable-field d-none" value="{{ $student->education_level ?? '' }}" id="education_level">
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="profession" class="form-label" style="min-width: 150px; font-weight:bold">Profession:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->profession ?? '-' }}</p>
                                                                                        <input type="text" name="profession" class="form-control editable-field d-none" value="{{ $student->profession ?? '' }}" id="profession">
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="kitengo" class="form-label" style="min-width: 150px; font-weight:bold">Kitengo:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->vitengo->name ?? '-' }}</p>
                                                                                        <select name="vitengo_id" id="vitengo_id" class="form-select editable-field d-none">
                                                                                            <option value="">-- Select Kitengo --</option>
                                                                                            @foreach ($vitengo as $kitengo)
                                                                                            <option value="{{ $kitengo->id }}" {{ $student->vitengo_id == $kitengo->id ? 'selected' : '' }}>
                                                                                                {{ $kitengo->name }}
                                                                                            </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="nin" class="form-label me-2" style="min-width: 150px; font-weight:bold">NIDA:</label>
                                                                                        <p class="static-field leftlabel">{{ $student->nin ?? '-'}}</p>

                                                                                        <input type="text" name="nin" class="form-control editable-field d-none" maxlength="23" id="nin" value="{{ $student->nin ?? '' }}">
                                                                                        @if ($errors->has('nin'))
                                                                                            <div class="text-danger small">
                                                                                                ⚠️ {{ $errors->first('nin') }}
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="home_region" class="form-label me-2" style="min-width: 150px; font-weight:bold">Home Region:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->home_region ?? '-' }}</p>
                                                                                        <input type="text" name="home_region" class="form-control editable-field d-none" value="{{ $student->home_region ?? '' }}">
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="entry_region" class="form-label me-2" style="min-width: 150px; font-weight:bold">Entry Region:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->entry_region ?? '-' }}</p>
                                                                                        <input type="text" name="entry_region" class="form-control editable-field d-none" value="{{ $student->entry_region ?? '' }}">
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="bank_name" class="form-label me-2" style="min-width: 150px; font-weight:bold">Bank Name:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->bank_name ?? '-' }}</p>
                                                                                        <input type="text" name="bank_name" class="form-control editable-field d-none" value="{{ $student->bank_name ?? '' }}">
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="account_number" class="form-label me-2" style="min-width: 150px; font-weight:bold">Account No:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->account_number ?? '-' }}</p>
                                                                                        <input type="text" name="account_number" class="form-control editable-field d-none" value="{{ $student->account_number ?? '' }}">
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="height" class="form-label me-2" style="min-width: 150px; font-weight:bold">Height:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->height ? $student->height . ' ft' : '-' }}</p>
                                                                                        <input type="text" name="height" class="form-control editable-field d-none" value="{{ $student->height ?? '' }}">
                                                                                        @if ($errors->has('height'))
                                                                                            <div class="text-danger small">
                                                                                                ⚠️ {{ $errors->first('height') }}
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-2">
                                                                                        <label for="weight" class="form-label me-2" style="min-width: 150px; font-weight:bold">Weight:</label>
                                                                                        <p class="static-field leftlabel"> {{ $student->weight ? $student->weight . ' KG' : '-' }}</p>
                                                                                        <input type="text" name="weight" class="form-control editable-field d-none" value="{{ $student->weight ?? '' }}">
                                                                                        @if ($errors->has('weight'))
                                                                                            <div class="text-danger small">
                                                                                                ⚠️ {{ $errors->first('weight') }}
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                                
                                                                <!-- Form field start -->
                                                                <div class="m-0">
                                                                    
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-filter-circle"></i> <label class="form-label" for="abt" style="font-size: large;"> &nbsp;&nbsp;&nbsp;Next of Kin(s) Informations</label>
                                                                        </span>
                                                                    </div>
                                                                    <div class="card-body" style="background-color: rgb(209, 209, 214);">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="w-100">
                                                                                <table class="table table-bordered">
                                                                                    <thead class="table-primary">
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th>Name</th>
                                                                                            <th>Relationship</th>
                                                                                            <th>Phone</th>
                                                                                            <th>Address</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @php
                                                                                        if (empty($student->next_of_kin )) {
                                                                                            $kins = [
                                                                                                ['name' => '-', 'relationship' => '-', 'phone' => '-', 'address' => '-']
                                                                                            ];
                                                                                        }else{
                                                                                            $kins = $student->next_of_kin;
                                                                                        }
                                                                                        @endphp
                                                                                        @foreach ($kins as $kin)
                                                                                            <tr>
                                                                                            <td>{{ $loop->iteration }}</td>

                                                                                            <td>
                                                                                                <span class="static-field">{{ $kin['name'] }}</span>
                                                                                                <input type="text" name="next_of_kin[{{ $loop->index }}][name]" class="form-control form-control-sm editable-field d-none">
                                                                                            </td>

                                                                                            <td>
                                                                                                <span class="static-field">{{ $kin['relationship'] }}</span>
                                                                                                <input type="text" name="next_of_kin[{{ $loop->index }}][relationship]" class="form-control form-control-sm editable-field d-none">
                                                                                            </td>

                                                                                            <td>
                                                                                                <span class="static-field">{{ $kin['phone'] }}</span>
                                                                                                <input type="text" name="next_of_kin[{{ $loop->index }}][phone]" class="form-control form-control-sm editable-field d-none">
                                                                                            </td>

                                                                                            <td>
                                                                                                <span class="static-field">{{ $kin['address'] }}</span>
                                                                                                <input type="text" name="next_of_kin[{{ $loop->index }}][address]" class="form-control form-control-sm editable-field d-none">
                                                                                            </td>
                                                                                            </tr>
                                                                                        @endforeach

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- Row ends -->
                                            </div>
                                        </div>
                                    </div>
                                    @if ($student->status  == 'approved')
                                        <div class="d-flex justify-content-end" style="margin-left:-5px">
                                            <p class="mb-0">Verified By: <span class="text-success">{{ $student->verifier->name ?? '-' }}</span></p>
                                        </div>
                                    @endif
                                </div>
                                <!-- Row ends -->

                            </div>
                            <div class="tab-pane fade" id="twoA" role="tabpanel">

                                <!-- Row starts -->
                                <div class="row gx-5 align-items-center">
                                    <div class="col-sm-4 col-12">
                                        <div class="p-3">
                                            <img src="/tps-smis/resources/assets/images/notifications.svg"
                                                alt="Notifications" class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-12">
                                        <!-- List 2 group start -->
                                            <div class="row">
                                                <div class="col-sm-2 col-12">
                                                    <!-- Form field start -->
                                                    <div class="mb-3">
                                                    </div>
                                                    <!-- Form field end -->
                                                </div>
                                            </div>
                                        <!-- List 2 group end -->
                                    </div>

                                </div>
                                <!-- Row ends -->

                            </div>
                            <div class="tab-pane fade" id="threeA" role="tabpanel">

                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="col-12">

                                        <!-- List 3 group start -->

                                        <!-- List 3 group end -->

                                    </div>
                                </div>
                                <!-- Row ends -->

                            </div>
                            <div class="tab-pane fade" id="fourA" role="tabpanel">

                                <!-- Row starts -->
                                <div class="row align-items-end">
                                    <div class="col-xl-4 col-sm-6 col-12">
                                        <div class="p-3">
                                            <img src="/tps-smis/resources/assets/images/login.svg" alt="Contact Us"
                                                class="img-fluid" width="300" height="320">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-12">
                                        <div class="card border mb-3">
                                            <div class="card-body">

                                                <div class="mb-3">
                                                    <label class="form-label" for="currentPwd">Current password <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="password" id="currentPwd"
                                                            placeholder="Enter Current password" class="form-control">
                                                        <button class="btn btn-outline-secondary" type="button">
                                                            <i class="bi bi-eye text-black"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="newPwd">New password <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="password" id="newPwd" class="form-control"
                                                            placeholder="Your password must be 8-20 characters long.">
                                                        <button class="btn btn-outline-secondary" type="button">
                                                            <i class="bi bi-eye text-black"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="confNewPwd">Confirm new password <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="password" id="confNewPwd"
                                                            placeholder="Confirm new password" class="form-control">
                                                        <button class="btn btn-outline-secondary" type="button">
                                                            <i class="bi bi-eye text-black"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->

                            </div>
                            
                            <div class="tab-pane fade" id="fiveA" role="tabpanel">

                                <!-- Row starts -->
                                
                                <div class="row gx-4">
                                    <div class="col-sm-12 col-12">
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <!-- Row starts -->
                                                <form id="studentUpdateForm" method="POST" action="{{ route('students.update', $student->id) }}" onsubmit="return validateFullNameBeforeSubmit();" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Editable fields go here -->
                                                    <div class="row gx-4">
                                                            <div class="col-sm-2 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="forceNumber" class="form-label">Force Number</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-person"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="forceNumber" value="{{$student->force_number}}" Disabled>
                                                                        <input type="text" name="force_number" class="form-control editable-field d-none" id="forceNumber" value="{{$student->force_number}}">
                                                                    </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <!-- Form field start -->
                                                                 <div class="mb-3">
                                                                    <label for="fullNameEdit" class="form-label">Full Name</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-person"></i>
                                                                        </span>

                                                                        {{-- Static field --}}
                                                                        <input type="text"
                                                                            class="form-control static-field"
                                                                            id="fullNameStatic"
                                                                            value="{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}"
                                                                            disabled>

                                                                        {{-- Editable field --}}
                                                                        <input type="text"
                                                                            name="full_name"
                                                                            class="form-control editable-field d-none"
                                                                            id="fullNameEdit"
                                                                            value="{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}"
                                                                            placeholder="Enter first, middle, and last name">

                                                                    </div>
                                                                    @if ($errors->has('full_name'))
                                                                        <div class="text-danger small">
                                                                            ⚠️ {{ $errors->first('full_name') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-3 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="yourEmail" class="form-label">Company</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-builiding"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="yourCompany" value=" {{ $student->company->name ?? '-' }} - {{ $student->platoon ?? '-' }}" Disabled>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('email'))
                                                                    <div class="text-danger small">
                                                                        ⚠️ {{ $errors->first('email') }}
                                                                    </div>
                                                                @endif
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-2 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="contactNumber" class="form-label">Contact</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-phone"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="contactNumber" value="{{$student->phone}}" Disabled>
                                                                        <input type="text" name="phone" class="form-control editable-field d-none" id="contactNumber" value="{{$student->phone}}">
                                                                    </div>
                                                                    @if ($errors->has('phone'))
                                                                        <div class="text-danger small">
                                                                            ⚠️ {{ $errors->first('phone') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-sm-2 col-12">
                                                                <!-- Form field start -->
                                                                <div class="mb-3">
                                                                    <label for="birthDay" class="form-label">Date of Birth</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-calendar4"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control static-field" id="birthDay" value="{{$student->dob}}" Disabled>
                                                                        <input type="date" name="dob" class="form-control editable-field d-none" id="birthDay" value="{{$student->dob}}">
                                                                    </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                            <div class="col-12">
                                                                <!-- Form field start -->
                                                                <div class="m-0">
                                                                    <div class="card mt-4">
                                                                        <div class="card-header bg-warning text-dark">
                                                                            <strong>Lockup History</strong>
                                                                        </div>
                                                                        <div class="card-body" style="background-color: rgb(209, 209, 214);">
                                                                            @if ($lockups->count())
                                                                            @foreach ($lockups as $lockup)
                                                                                <div class="mb-3 p-3 bg-light border rounded">
                                                                                <p class="mb-1"><strong>Arrested:</strong> {{ \Carbon\Carbon::parse($lockup->arrested_at)->format('d M Y') }}</p>
                                                                                <p class="mb-1"><strong>Days Held:</strong> {{ $lockup->days }}</p>
                                                                                <p class="mb-1"><strong>Released:</strong> {{ $lockup->released_at ? \Carbon\Carbon::parse($lockup->released_at)->format('d M Y') : 'Not yet released' }}</p>
                                                                                <p class="mb-0"><strong>Description:</strong> {{ $lockup->description }}</p>
                                                                                </div>
                                                                            @endforeach
                                                                            @else
                                                                            <p class="text-muted">No lockup records found for this student.</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @if ($dismissal)
                                                                    <div class="card mt-4 border-danger">
                                                                        <div class="card-header bg-danger text-white">
                                                                        <strong>This student is Dismissed</strong>
                                                                        </div>
                                                                        <div class="card-body" style="background-color: rgb(248, 222, 222);">
                                                                        <p class="mb-1"><strong>Dismissed At:</strong> {{ \Carbon\Carbon::parse($dismissal->dismissed_at)->format('d M Y') }}</p>
                                                                        <p class="mb-1"><strong>Reason:</strong> {{ $dismissal->reason_label }} ({{ $dismissal->category }})</p>
                                                                        @if ($dismissal->custom_reason && $dismissal->custom_reason !== 'null')
                                                                            <p class="mb-1"><strong>Specified Reason:</strong> {{ $dismissal->custom_reason ?? '' }}</p>
                                                                        @endif
                                                                        <p class="mb-0 text-muted"><em>Recorded by system on {{ \Carbon\Carbon::parse($dismissal->created_at)->format('d M Y, H:i') }}</em></p>
                                                                        </div>
                                                                    </div>
                                                                    @endif


                                                                    <div class="card-body" style="background-color: rgb(209, 209, 214);">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="p-3 me-3 w-100">
                                                                                <div class="row">

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Form field end -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!-- Row ends -->
                                            </div>
                                        </div>
                                    </div>
                                    @if ($student->status  == 'approved')
                                        <div class="d-flex justify-content-end" style="margin-left:-5px">
                                            <p class="mb-0">Verified By: <span class="text-success">{{ $student->verifier->name ?? '-' }}</span></p>
                                        </div>
                                    @endif
                                </div>
                                <!-- Row ends -->

                            </div>
                        </div>
                        <!-- Tab content end -->

                    </div>
                    <!-- Custom tabs end -->

                    <!-- Buttons start -->
                    <!-- <div class="d-flex gap-2 justify-content-end">
                          <button type="button" class="btn btn-outline-dark">
                            Cancel
                          </button>
                          <button type="button" class="btn btn-primary">
                            Update
                          </button>
                        </div> -->
                    <!-- Buttons end -->

                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <!-- Row ends -->


    <div class="modal fade" id="SafariDetails" tabindex="-1" aria-labelledby="statusModalLabelMore" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabelMore">
                        Student Safari Details
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('storeSafariStudent', $student) }}" method="POST">
                        @csrf
                        <div class="d-flex gap-2 mb-3">
                            <label for="">Safari Type</label>
                            <select class="form-control" style="width: 83%;" name="safari_type_id" id="" required>
                                <option value="" selected disabled>select type</option>
                                @foreach ($safari_types as $safari_type)
                                    <option value="{{ $safari_type->id }}">{{ $safari_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Description</label>
                            <textarea class="form-control" name="description" id="" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-primary">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmSaveLabel">Confirm Changes</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to save these updates to this student's profile and next-of-kin information?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="confirmSubmitBtn">Yes, Save Changes</button>
            </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('editAboutBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const staticFields = document.querySelectorAll('.static-field');
    const editableFields = document.querySelectorAll('.editable-field');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmSaveModal'));
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    const form = document.getElementById('studentUpdateForm');

    let isInEditMode = false;

    function toggleEditMode(enable) {
      staticFields.forEach(el => el.classList.toggle('d-none', enable));
      editableFields.forEach(el => el.classList.toggle('d-none', !enable));
      cancelBtn.classList.toggle('d-none', !enable);
      editBtn.textContent = enable ? '💾 Save' : '✏️ Edit Profile';
      isInEditMode = enable;
    }

    editBtn.addEventListener('click', function () {
      if (!isInEditMode) {
        toggleEditMode(true);
      } else {
        // Show confirmation modal before submitting
        confirmModal.show();
      }
    });

    cancelBtn.addEventListener('click', function () {
      toggleEditMode(false);
      editableFields.forEach((input, i) => {
        input.value = staticFields[i].textContent.trim();
      });
    });

    confirmSubmitBtn.addEventListener('click', function () {
      form.submit();
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const nidaInput = document.querySelector('#nin');

    if (nidaInput) {
      nidaInput.addEventListener('input', function () {
        let raw = this.value.replace(/[^0-9]/g, '').slice(0, 20);
        let formatted = '';
        if (raw.length > 0) formatted += raw.substring(0, 8);
        if (raw.length >= 9) formatted += '-' + raw.substring(8, 13);
        if (raw.length >= 14) formatted += '-' + raw.substring(13, 18);
        if (raw.length >= 19) formatted += '-' + raw.substring(18, 20);
        this.value = formatted;
      });
    }
  });
</script>
<script>
    function validateFullNameBeforeSubmit() {
        const input = document.getElementById('fullNameEdit');
        const feedback = document.getElementById('nameFeedback');
        const parts = input.value.trim().split(/\s+/);

        if (parts.length !== 3) {
            input.classList.add('is-invalid');
            feedback.classList.remove('d-none');
            feedback.innerText = "⚠️ Please enter exactly three names: First, Middle, and Last Name.";
            return false; // Prevent form submission
        } else {
            input.classList.remove('is-invalid');
            feedback.classList.add('d-none');
            return true; // Proceed with form submission
        }
    }
</script>
@endsection
