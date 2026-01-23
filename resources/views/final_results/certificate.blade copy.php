<!DOCTYPE html>
<html>
<head>
    <style>
    @page {
        margin-top: 9mm;
        margin-bottom: 5mm;
    }

    body {
        margin: 0;
        padding: 0;
    }

        .certificate {
            width: 90%;
            margin: 20px auto;
            font-family: "Times New Roman", serif;
            text-align: center;
            border: 6px double black; /* Elegant double border */
            padding: 20px; /* Add spacing */
            background: linear-gradient(to bottom, #ffffff, #f7f7f7); /* Light gradient for a subtle effect */
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3); /* Add shadow for depth */
            position: relative;
            overflow: hidden;
        }

        /* Watermark Styling */
        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Send it to the background */
            color: rgba(0, 0, 0, 0.2); /* Increased opacity to make watermark more intense */
            font-size: 10px; /* Adjust size as needed */
            text-align: center;
            line-height: 1.5; /* Adjust line height for spacing between lines of text */
            white-space: nowrap;
            pointer-events: none; /* Ensures the watermark doesn’t interfere with interactions */

            /* Create a repeating horizontal watermark with reduced space between lines */
            background: repeating-linear-gradient(
                to bottom,
                transparent, 
                transparent 5px, /* Reduced space between repeated words (adjust this value) */
                rgba(0, 0, 0, 0.2) 5px, /* Increased opacity for more intense watermark */
                rgba(0, 0, 0, 0.2) 15px /* Reduced space between lines of watermark text */
            );
        }





        .header {
            margin-bottom: 20px !important;
        }

        .header h1, .header h2, .header h3 {
            margin: 5px 0;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            color: #2b2b2b;
            text-transform: uppercase;
        }

        .details {
            margin-bottom: 20px;
        }

        .details p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.5;
        }

        .subjects {
            margin: 20px auto;
            text-align: left;
            width: 80%;
            border: 1px solid black; /* Subtle border for subjects section */
            padding: 10px;
            background-color: #f9f9f9; /* Light background for emphasis */
        }

        .subjects ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            width: 80%;
            margin: 0 auto;
        }

        .signature div {
            text-align: center;
            font-size: 14px;
        }

        .footer {
            margin-top: 20px !important;
            /* margin-bottom: -20px !important; */
            font-size: 12px;
            color: gray;
        }
    </style>
</head>
<body>
    @foreach($students as $student)
    <div class="certificate">
        <!-- Watermark -->
        <div class="watermark">
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;
            Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp; Tanzania Police Force&nbsp;<br>
        </div>

        <!-- Header Section -->
        <div class="header">
            <h2>THE UNITED REPUBLIC OF TANZANIA</h2>
            <h2>MINISTRY OF HOME AFFAIRS</h2>
            <h3>TANZANIA POLICE FORCE</h3>
        </div>

        <!-- Details Section -->
        <div class="details">
            <center><img src="{{ url('resources/assets/images/logo.png') }}" alt="Logo" width="80" height="100"></center>
            <br>
            <p><i>This is to certify that</i></p>
            <p style="font-size:20px"><strong>{{ $student->force_number }} {{ $student->rank }} {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</strong></p>
            <p>has successfully attended and passed the <strong>SERGEANT COURSE NO.2/2024/2025</strong> <br> held at <strong>Tanzania Police School-Moshi</strong></p>
            <p>From <strong>10 December 2024</strong> to <strong>07 March 2025</strong>.</p>
          
            <p style="text-align: left;"><i>The following subjects were completed:</i></p>
            <div class="subjects">
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
                    <li>Drills and Parade</li>
                    <li>Military and Safety Training</li>
                </ul>
            </div>
        </div>

        
        <!-- Signature Section -->
        <div class="row signature" style="display: flex; justify-content: space-between; width: 100%; margin-top:33px">
            <div>
                <div style="text-align: left;">
                    <img src="{{ url('resources/assets/images/ci_kisalo.png') }}" alt="CI Signature">
                    <p style="margin-top:-4px"><strong>OMARY S. KISALO - ACP</strong></p>
                    <p style="margin-left: 15px;">CHIEF INSTRUCTOR</p>
                </div>
                <div style="text-align: right; margin-top:-500px">
                    <img src="{{ url('resources/assets/images/co_mungi.png') }}" alt="CO Signature">
                    <p style="margin-top:-4px"><strong>RAMADHANI A. MUNGI - SACP</strong></p>
                    <p style="margin-right: 50px;">COMMANDANT</p>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer" >
            <p>Issued on: {{ now()->toDateString() }}</p>
        </div>
    </div>

    <!-- Page Break for Next Student -->
    <div style="page-break-after: always;"></div>
    @endforeach
</body>
</html>
