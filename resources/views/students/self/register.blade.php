<!DOCTYPE html>
<html lang="en">

<head>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @include('layouts.head')
  <!-- Ensure the viewport meta tag is included for responsiveness -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <!-- Page wrapper starts -->
  <div class="page-wrapper">

    <!-- Auth container starts -->
    <div class="auth-container">

      <div class="d-flex justify-content-center">

        <!-- Form starts -->
        <form method="POST" action="{{ route('students.register') }}" class="w-100">
          @csrf

          <!-- Logo starts -->
          <center>
            <a href="/tps-smis" class="auth-logo mt-5 mb-3">
              <img src="/tps-smis/resources/assets/images/logo.png" class="img-fluid" style="max-height: 100px; width: auto;" alt="Police Logo" />
            </a>
          </center>
          <!-- Logo ends -->

          <!-- Authbox starts -->
          <div class="auth-box2 mx-auto" style="max-width: 800px;">

            <span class="mb-4" style="font-weight:500; font-size:24px;">Register Here</span><br>
            <i>Please fill out all fields carefully as they appear on your official documents.</i>

            <div class="row g-3 mb-3" style="margin-top:20px;">
              <!-- Force Number -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="force_number">Force Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-bookmark-star"></i>
                  </span>
                  <input id="force_number" type="text" class="form-control @error('force_number') is-invalid @enderror" name="force_number" value="{{ old('force_number') }}" required autocomplete="force_number" autofocus>
                  @error('force_number')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <!-- First Name -->
              <div class="col-12 col-md-6">
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

              <!-- Middle Name -->
              <div class="col-12 col-md-6">
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

              <!-- Last Name -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="last_name">Last Name <span class="text-danger">*</span></label>
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

              <!-- NIDA -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="nin">NIDA <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-person-badge"></i>
                  </span>
                  <input id="nin" type="text" class="form-control @error('nin') is-invalid @enderror" name="nin" value="{{ old('nin') }}" required autocomplete="nin" autofocus>
                  @error('nin')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <!-- Date of Birth -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-calendar2-day"></i>
                  </span>
                  <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required autocomplete="dob" autofocus>
                  @error('dob')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <!-- Email Address -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
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

              <!-- Gender -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="gender">Gender <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-gender-ambiguous"></i>
                  </span>
                  <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                    <option selected>Choose gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                  </select>
                  @error('gender')
                    <div class="error">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Programme/Course -->
              <div class="col-12">
                <label class="form-label" for="programme_id">Select the Course You Have Been Admitted To <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-check2-all"></i>
                  </span>
                  <select name="programme_id" id="programme_id" class="form-control @error('programme_id') is-invalid @enderror" required>
                    <option value="" selected disabled>-- Select your admitted course</option>
                    @foreach ($programmes as $programme)
                      <option value="{{ $programme->id }}">{{ $programme->programmeName }}</option>
                    @endforeach
                  </select>
                  @error('programme_id')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <!-- Password -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                  </span>
                  <input type="password" id="password" class="form-control" name="password" placeholder="Enter password">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                <div class="form-text">
                  Your password must be 8-20 characters long.
                </div>
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <!-- Confirm Password -->
              <div class="col-12 col-md-6">
                <label class="form-label" for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                  </span>
                  <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Confirm password">
                  <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                @error('password_confirmation')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <input type="number" name="session_programme_id" value="4" class="form-control" hidden>  
              <input type="text" name="rank" value="Constable" class="form-control" hidden>  

              <!-- Submit Button -->
              <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <a href="/tps-smis/login" class="btn btn-outline-dark w-100 mt-3">Already have an account? Login</a>
              </div>
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

  <!-- JavaScript for Password Toggle -->
  <script>
    function togglePassword(fieldId) {
      const input = document.getElementById(fieldId);
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);
    }
  </script>
</body>

</html>