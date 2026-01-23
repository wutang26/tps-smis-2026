@extends('layouts.main')

@section('style')
<style>
.back{
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
              <button class="btn btn-danger me-2">Change Password</button>
              <button class="btn btn-success">Active</button> 
            </div>
          </div>
        </div>
    </div>
</div>

<div class="row gx-4">
  @if(session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  @if($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body">
                    <!-- Custom tabs start -->
                    <div class="custom-tabs-container">

                      <!-- Nav tabs start -->
                      <!-- Nav tabs end -->

                      <!-- Tab content start -->
                      <div class="tab-content h-300">
                        <div class="tab-pane fade show active" id="oneA" role="tabpanel">
                            <!-- Row starts -->
                          <div class="row align-items-end">
                            <div class="col-xl-4 col-sm-6 col-12">
                              <div class="p-3">
                                <img src="/tps-smis/resources/assets/images/login.svg" alt="Contact Us" class="img-fluid" width="300" height="320">
                              </div>
                            </div>
                            <div class="col-sm-4 col-12">
                              <div class="card border mb-3">
                                <div class="card-body">

                                  <form action="{{ route('updatePassword', auth()->id()) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="currentPwd">Current password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="currentPwd" placeholder="Enter Current password" class="form-control" required>
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="bi bi-eye text-black"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="newPwd">New password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPwd" class="form-control" placeholder="Your password must be 8-20 characters long." required>
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="bi bi-eye text-black"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="confNewPwd">Confirm new password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confNewPwd" placeholder="Confirm new password" class="form-control" required>
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="bi bi-eye text-black"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <a class="btn btn-outline-dark" href="{{ url('/') }}">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>


                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->
 @endsection