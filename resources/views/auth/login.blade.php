<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include Head Blade -->
    @include('layouts.head')

    <!-- Page-specific CSS -->
    <style>
        .auth-box{
            padding: 30px;
            padding-bottom: 50px;
        }

        /* Optional: center container vertically on screen */
        body, html {
            height: 100%;
        }
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>
<body>

    <!-- Page wrapper starts -->
    <div class="page-wrapper">

        <!-- Auth container starts -->
        <div class="auth-container">

            <div class="d-flex justify-content-center" style="margin-top:4%">

                <!-- Form starts -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Logo starts -->
                    <center>
                        <a href="{{ url('/') }}" class="auth-logo mt-5 mb-3">
                            <img src="{{ asset('assets/images/logo.png') }}" 
                                 style="height:200px; width:200px"  
                                 alt="Police Logo">
                        </a>
                    </center>
                    <!-- Logo ends -->

                    <!-- Authbox starts -->
                    <div class="auth-box">

                        <h4 class="mb-4 text-center" style="color: #072A6C">TPS - SMIS</h4>

                        <div class="mb-3">
                            <label class="form-label" for="email">{{ __('Username') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}"   
                                       placeholder="Enter your username" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Enter password" required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-4 mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password ?') }}
                                </a>
                            @endif
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

    <!-- Bootstrap JS (ensure dropdowns & toggles work) -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
