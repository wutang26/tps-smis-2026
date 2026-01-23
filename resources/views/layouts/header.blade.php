<!-- App header starts -->
<style>
    /* Responsive adjustments */
    @media (max-width: 600px) {
        .title-container {
            max-width: 95%;
            padding: 10px;
        }

        .session-input-group {
            width: 90%;
            margin: 15px auto;
        }

        .session-input-group form {
            flex-direction: column;
            align-items: stretch;
        }

        .session-input-group .input-group {
            flex-direction: column;
        }

        .session-input-group button,
        .session-input-group select {
            width: 100%;
            margin-bottom: 10px;
        }

        .session-input-group button:last-child,
        .session-input-group select:last-child {
            margin-bottom: 0;
        }

        .outside {
            font-size: 18px;
            margin-left: -3%;
            margin-right: 1%;
            height: 36px;
        }

        .activesession1 {
            display: none;
        }
    }

    @media (min-width: 468px) {
        .outside {
            font-size: 18px;
            margin-left: 1%;
            height: 36px;
            width: 30%
        }

        .outside form {
            margin-left: 10px;
        }

    }

    /* Profile Image Style */
    .profile-image {
        width: 50px;
        /* Adjust this value based on your menu or profile size */
        height: 50px;
        /* Same as width to make it circular */
        object-fit: cover;
        /* Ensures the image is not stretched */
        border-radius: 50%;
        /* This makes the image circular */
        border: 2px solid #fff;
        /* Optional: adds a border for contrast */
    }

    /* Optional: Style for larger profile pictures if needed */
    .profile-image-large {
        width: 100px;
        /* Adjust based on profile size */
        height: 100px;
    }

    /* For the menu icon (smaller image size) */
    .icon-box.md .profile-image {
        width: 40px;
        /* Adjust smaller size for menu */
        height: 40px;
        /* Same size for circular shape */
    }
</style>
<div class="app-header d-flex align-items-center">
    <!-- Toggle buttons starts -->
    <div class="d-flex">
        <button class="toggle-sidebar">
            <i class="bi bi-list lh-1"></i>
        </button>
        <button class="pin-sidebar">
            <i class="bi bi-list lh-1"></i>
        </button>
    </div>
    <!-- Toggle buttons ends -->

    <!-- App brand sm starts -->
    <div class="app-brand-sm d-lg-none d-flex">
        <!-- Logo sm starts -->
        <!-- <a href="index.html">
            <img src="assets/images/logo-sm.svg" class="logo" alt="Tps Gallery">
        </a> -->
        <!-- Logo sm end -->
    </div>
    <!-- App brand sm ends -->

    <!-- Session starts -->
    <!-- @php
        use Illuminate\Support\Facades\DB;

        // Retrieve all session programmes from the database
        $sessionProgrammes = DB::table('session_programmes')->where('is_current', 1)->get();

        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }

        // Get the selected session ID from the session
        $selectedSessionId = session('selected_session') ?? 1; // Default to 1 if not set
    @endphp -->

    @can('programme-session-list')
        <div class="input-group outside" style="">
            <form action="{{ url()->current() }}" method="GET" style="display:inline-block;">
                <div class="input-group inside" style="font-size:18px; height:36px;">
                    <button class="btn btn-outline-secondary activesession1" type="button">
                        Active Session
                    </button>
                    <select name="session_id" class="form-control activeSession" id="sessionProgramme"
                        onchange="this.form.submit()">
                        <option value="" disabled {{ !$selectedSessionId ? 'selected' : '' }}>Choose the session</option>
                        @foreach($sessionProgrammes as $sessionProgramme)
                            <option value="{{ $sessionProgramme->id }}" {{ $sessionProgramme->id == $selectedSessionId ? 'selected' : '' }}>
                                {{ $sessionProgramme->session_programme_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>



        <!-- <div class="input-group" style="font-size:18px; margin-left: 1%; height:36px; width:30%">
            <form action="{{ url()->current() }}" method="GET" style="display:inline-block; margin-left:10px;">
                <div class="input-group" style="font-size:18px; height:36px;">
                    <button class="btn btn-outline-secondary" type="button">
                        Active Session
                    </button>
                    <select name="session_id" class="form-control activeSession" id="sessionProgramme" onchange="this.form.submit()">
                        <option value="" disabled {{ !$selectedSessionId ? 'selected' : '' }}>Choose the session</option>
                        @foreach($sessionProgrammes as $sessionProgramme)
                            <option value="{{ $sessionProgramme->id }}" {{ $sessionProgramme->id == $selectedSessionId ? 'selected' : '' }}>
                                {{ $sessionProgramme->session_programme_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div> -->
    @endcan
    <!-- Session ends -->

    <!-- App header actions starts -->
    <div class="header-actions">
        <!-- Search container start -->
        <div class="search-container d-xl-block d-none me-3">
            <input type="text" class="form-control" id="searchData" placeholder="Search" />
            <i class="bi bi-search"></i>
        </div>
        <!-- Search container ends -->

        <!-- Header action bar starts -->
        <div class="bg-white p-2 rounded-4 d-flex align-items-center">
            @include('notifications.list')
            <!-- User settings start -->
            <div class="dropdown ms-2">
                @if(Auth::check())
                    <!-- Check if user is authenticated -->
                    <a id="userSettings" class="dropdown-toggle user-settings" href="#!" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 text-truncate d-lg-block d-none">{{ Auth::user()->name }}</span>
                        <div class="icon-box md rounded-4 fw-bold bg-primary-subtle text-primary">
                            @if (auth()->user()->hasRole('Student') && auth()->user()->student->photo)
                                <!-- Display user photo if available, with size adjustments -->
                                <img src="{{ url('storage/app/public/' . auth()->user()->student->photo) }}" alt="profile"
                                    class="profile-image" />
                            @else
                                                <!-- Display initials if no photo available -->
                                                <?php
                                $string = Auth::user()->name;

                                // Function to get the initials
                                function getFirstLetters($string)
                                {
                                    $words = explode(' ', $string);
                                    $firstLetters = '';
                                    foreach ($words as $word) {
                                        if (!empty($word)) {
                                            $firstLetters .= strtoupper($word[0]); // Make initials uppercase
                                        }
                                    }
                                    return $firstLetters;
                                }

                                // Display the initials
                                echo getFirstLetters($string);
                                ?>
                            @endif
                        </div>

                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg">
                        <a class="dropdown-item d-flex align-items-center" @if(auth()->user()->hasRole('Student'))
                        href="{{ url('student/profile/' . Auth::user()->id) }}" @else
                            href="{{ url('staff/profile/' . Auth::user()->id) }}" @endif>
                            <i class="bi bi-person fs-4 me-2"></i>My Profile
                        </a>
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ url('/profile/change-password/' . Auth::user()->id) }}"><i
                                class="bi bi-gear fs-4 me-2"></i>Change Password</a>
                        <div class="mx-3 my-2 d-grid">
                            <a href="{{ route('logout') }}" class="btn btn-warning"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            <!-- User settings end -->
        </div>
        <!-- Header action bar ends -->
    </div>
    <!-- App header actions ends -->
</div>
@yield('scrumb')
<!-- App header ends -->