@extends('layouts.main')

@section('style')
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"> -->
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
</style>

@endsection
@section('content')

<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body back">
                <div class="profile-header">
                    <img src="/tps-smis/resources/assets/images/profile/avatar.jpg" alt="Profile Picture" />
                </div>

                <div class="d-flex justify-content-end mt-3">
                  
                    @if($user->staff)
                    <a href="{{ route('staffs.resume', $user->staff->id) }}" class="btn btn-primary me-2">Curriculum Vitae</a>
                    @endif
                    <button class="btn btn-danger me-2">Edit Profile</button>
                    <button class="btn btn-success">Active</button>
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
                                            <div class="row gx-4">
                                                <div class="col-sm-2 col-12">

                                                    <!-- Form field start -->
                                                    <div class="mb-3">
                                                        <label for="forceNumber" class="form-label">Force Number</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="bi bi-person"></i>
                                                            </span>
                                                            <input type="text" class="form-control" id="forceNumber"
                                                                value="{{$user->staff->forceNumber ?? ''}}" Disabled>
                                                        </div>
                                                    </div>
                                                    <!-- Form field end -->

                                                </div>

                                                <div class="col-sm-3 col-12">

                                                    <!-- Form field start -->
                                                    <div class="mb-3">
                                                        <label for="fullName" class="form-label">Full Name</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="bi bi-person"></i>
                                                            </span>
                                                            <input type="text" class="form-control" id="fullName"
                                                                value="{{$user->staff->firstName ?? ''}} {{$user->staff->middleName ?? ''}} {{$user->staff->lastName ?? ''}}"
                                                                Disabled>
                                                        </div>
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
                                                            <input type="email" class="form-control" id="yourEmail"
                                                                value="{{$user->email}}" Disabled>
                                                        </div>
                                                    </div>
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
                                                            <input type="text" class="form-control" id="contactNumber"
                                                                value="{{$user->staff->phoneNumber ?? ''}}" Disabled>
                                                        </div>

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
                                                            <input type="text" class="form-control" id="birthDay"
                                                                value="{{$user->staff->DoB ?? ''}}" Disabled>
                                                        </div>
                                                    </div>
                                                    <!-- Form field end -->

                                                </div>
                                                <div class="col-12">

                                                    <!-- Form field start -->
                                                    <div class="m-0">
                                                        <label class="form-label" for="abt">About </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="bi bi-filter-circle"></i>
                                                            </span>
                                                            <textarea class="form-control" id="abt" rows="4"
                                                                Disabled> Hey, blah blah</textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Form field end -->

                                                </div>

                                            </div>
                                            <!-- Row ends -->

                                        </div>
                                    </div>
                                </div>
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
@endsection