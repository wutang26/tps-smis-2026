<!DOCTYPE html>
<html lang="en">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
  @include('layouts.head')
  
  <body>
    <!-- Page wrapper starts -->
    <div class="page-wrapper">

      <!-- Auth container starts -->
      <div class="auth-container">

        <div class="d-flex justify-content-center">

        <!-- Form starts -->
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <!-- Logo starts -->
            <center>
                <a href="/tps-smis" class="auth-logo mt-5 mb-3">
                <img src="/tps-smis/resources/assets/images/logo.png" style="height:200 !important; width:200" alt="Police Logo" />
                </a>
            </center>
            <!-- Logo ends -->

            <!-- Authbox starts -->
            <div class="auth-box2">

              <span class="mb-4" style="font-weight:500; font-size:24px;">Register Here</span><br><i>Be carefully</i>
                  <div class="row mb-3" style="margin-top:20px">
                      <div class="col-xs-12 col-sm-6 col-md-6 mb-3 bottom">
                        <label class="form-label" for="force_number">Force Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-person"></i>
                          </span>
                          <input id="force_number" type="text" class="form-control @error('force_number') is-invalid @enderror" name="force_number" value="{{ old('force_number') }}" required autocomplete="force_number" autofocus>
                          @error('force_number')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 mb-3 bottom">
                        <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-person"></i>
                          </span>
                          <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                          @error('first_name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      </div>
                      </div>
                      <div class="row mb-3">
                      <div class="col-xs-12 col-sm-6 col-md-6 mb-3 bottom">
                        <label class="form-label" for="middle_name">Middle Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-person"></i>
                          </span>
                          <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}" required autocomplete="middle_name" autofocus>

                          @error('middle_name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 mb-3 bottom">
                        <label class="form-label" for="last_name">Last name <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-person"></i>
                          </span>
                          <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                          @error('last_name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      </div>
                      <div class="row mb-3">
                      <div class="col-xs-12 col-sm-6 col-md-6 mb-3 bottom">
                        <label class="form-label" for="nin">NIDA <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-person"></i>
                          </span>
                          <input id="nin" type="text" class="form-control @error('nin') is-invalid @enderror" name="nin" value="{{ old('nin') }}" required autocomplete="nin" autofocus>

                          @error('nin')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 mb-3 bottom">
                        <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-person"></i>
                          </span>
                          <input id="dob" type="text" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required autocomplete="dob" autofocus>

                          @error('dob')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      </div>
                      <div class="row mb-3">

                      <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                        <label class="form-label" for="email">Email Address<span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                          </span>                          
                          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                          @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                          <label class="form-label" for="programme_id">Select your Course Admitted<span class="text-danger">*</span></label>
                          <div class="input-group">
                            <span class="input-group-text">
                              <i class="bi bi-envelope"></i>
                            </span>
                            <select name="programme_id" id="programme_id" class="form-control @error('programme_id') is-invalid @enderror" required> 
                              <option value = "" selected disabled >-- Select your admitted course</option>    
                              @foreach ($programmes as $programme) 
                                <option value="{{ $programme->id }}">{{ $programme->programmeName }}</option> 
                                @endforeach 
                            </select>
                          </select>
                            @error('programme_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                          <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <span class="input-group-text">
                              <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" id="password" class="form-control" placeholder="Enter password">
                            <button class="btn btn-outline-secondary" type="button">
                              <i class="bi bi-eye"></i>
                            </button>
                          </div>
                          <div class="form-text">
                            Your password must be 8-20 characters long.
                          </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 mb-3">
                          <label class="form-label" for="password">Confirm Password <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <span class="input-group-text">
                              <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" id="password" class="form-control" placeholder="Enter password">
                            <button class="btn btn-outline-secondary" type="button">
                              <i class="bi bi-eye"></i>
                            </button>
                          </div>
                          <div class="form-text">
                            Your password must be 8-20 characters long.
                          </div>
                        </div>
                      </div>

                      <center>
                        <div class="col-md-6 mb-3" style="position:relative; !important">
                          <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                            <a href="/tps-smis/login" class="btn btn-outline-dark">Already have an account? Loginn</a>
                          </div>
                        </div>
                      </center>

                </div>
            </div>
            <!-- Authbox ends -->

          </form>
          <!-- Form ends -->

        </div>
      </div>
      <!-- Auth container ends -->

    </div>
    <!-- Page wrapper ends -->

  </body>

</html>