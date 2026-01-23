<!DOCTYPE html>
<html>
<head>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%; /* Make the container fill the viewport */
        }

        .certificate-image img {
            position: relative; /* Ensure proper positioning */
            display: flex; /* Enables flexbox layout */
            justify-content: center; /* Centers horizontally */
            align-items: center; /* Centers vertically */
            width: 100%; /* Stretch the image to fill most of the page width */
            height: 98%; /* Stretch the image to fill most of the page height */
            margin-top:13px;
            object-fit: cover; /* Ensures the image maintains proportions */
            loading: lazy; /* Improve loading performance for the image */
        }

        .details {
            position: absolute;
            top: 56%; /* Center text vertically */
            left: 50%; /* Center text horizontally */
            transform: translate(-50%, -50%);
            text-align: center;
            color: #000; /* Ensure text is visible against the image */
            width: 80%; /* Adjust text width for readability */
        }

        .details p {
            margin: 10px 0;
            font-size: 22px; /* Adjust font size */
        }

        .details ul {
            list-style: none;
            padding: 0;
            margin-left: 25px;
            margin-top: 15px;
            text-align: left; /* Align subjects for readability */
        }

        .details ul li {
            font-size: 23px;
            margin: 2px 0;
            font-weight: bold; /* Make the text bold */
           /* font-style: italic; /* Italicize the text */
        }

        .details ul li::before {
            content: "• "; /* Add a bullet before each item */
        }
    </style>
</head>
<body>
    @php
function pdf_safe($string) {
    // Replace known unsupported characters with safe versions
    $replace = [
        "′" => "'",     // prime symbol → normal apostrophe
        "″" => '"',     // double prime → normal quote
        "“" => '"', 
        "”" => '"',
        "‘" => "'",
        "’" => "'",
    ];

    return strtr($string, $replace);
}
@endphp

    @foreach($students as $student)
                                                    @php
                                                 if ($student->enrollment_status == 0)
                                                    continue;
                                                @endphp
    <div class="certificate-container">
        <!-- Full-Page Image -->
        <div class="certificate-image">
            <img src="{{ url('storage/app/public/certificates/certificate_bg.png') }}" alt="Certificate Background">
        </div>
        
        <!-- Layered Details -->
        <div class="details">
            <p style="margin-top:30px; margin-bottom:90px;"><i>This is to certify that</i></p>
            <p style="font-size:25px; font-weight: bold;">
                {{ $student->force_number }} {{ $student->rank }} {{ pdf_safe($student->first_name) }} {{ pdf_safe($student->middle_name) }} {{ pdf_safe($student->last_name) }}
            </p>
            <p>
                has successfully attended and passed the <strong>{{ $session_programme->session_programme_name }}</strong><br> 
                held at <strong>Tanzania Police School-Moshi</strong>
            </p>
            <p>
                From <strong>{{ \Carbon\Carbon::parse($session_programme->startDate)->format('d F Y') }}</strong>
                 to <strong>{{ \Carbon\Carbon::parse($session_programme->endDate)->format('d F Y') }}.</strong>


            <p style="text-align:left; margin-top:30px !important; font-size: 22px;">The following subjects were completed:-</p>
            
            <ul>

                <!-- @foreach($programme_courses as $programme_course)
                    <li>{{ $programme_course->course->courseName }}</li>                                                                                                            
                @endforeach -->
                <li>FIRST CALL</li>
                <li>REVEILLE</li>               
                <li>ATTENTION</li>
                <li>FORWAR/CHARGE</li>
                <li>TATTOO</li>
                <li>TAPS</li>
                <li>RETREAT</li>
                <li>RECALL</li>
                <li>ASSEMBLY</li>
            
            </ul>
            <br>
        </div>
    </div>
    @endforeach
</body>
</html>