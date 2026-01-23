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
        }

        .details ul li::before {
            content: "• "; /* Add a bullet before each item */
        }
    </style>
</head>
<body>
    @foreach($students as $student)
    <div class="certificate-container">
        <!-- Full-Page Image -->
        <div class="certificate-image">
            <img src="{{ url('storage/app/public/certificates/certificate_bg.png') }}" alt="Certificate Background">
        </div>
        
        <!-- Layered Details -->
        <div class="details">
            <p style="margin-bottom:30px;"><i>This is to certify that</i></p>
            <p style="font-size:25px; font-weight: bold;">
                {{ $student->force_number }} {{ $student->rank }} {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
            </p>
            <p>
                has successfully attended and passed the <strong>SERGEANT COURSE NO.2/2024/2025</strong><br> 
                held at <strong>Tanzania Police School-Moshi</strong>
            </p>
            <p>
                From <strong>19 March 2025</strong> to <strong>30 May 2025</strong>.
            </p>
            <p style="text-align:left; margin-top:30px !important; font-size: 22px;"><i>The following subjects were completed:-</i></p>
            
            <ul>
                <li>Police Duties and Administration</li>
                <li>Human Rights and Policing</li>
                <li>Police Leadership</li>
                <li>Communication Skills and Customer Care</li>
                <li>Traffic Control and Management</li>
                <li>Criminal Investigation, Intelligence, and Forensic Science</li>
                <li>Criminal Procedure</li>
                <li>Law of Evidence</li>
                <li>Criminal Law</li>
                <li>Gender Issues and Child Protection</li>
                <li>Public Health and Environmental Protection</li>
                <li>Community Policing, Radicalization, Violent Extremism, and Terrorism</li>
                <li>Drill and Parade</li>
                <li>Military and Safety Training</li>
            </ul>
        </div>
    </div>
    @endforeach
</body>
</html>
