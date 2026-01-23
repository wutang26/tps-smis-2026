<!DOCTYPE html>
<html>
<head>
   <style>
    @page {
        margin: 0cm;
    }

    body {
        margin: 0;
        font-family: 'Times New Roman', serif;
        background: url('{{ public_path("images/certificate_bg.jpg") }}') no-repeat center center;
        background-size: cover;
    }

    .certificate {
        text-align: center;
        padding: 50px 50px;
        height: 100%;
    }

    

    .certificate h1 {
        font-size: 40px;
        font-family: 'Old English Text MT', 'Times New Roman', serif;
        font-weight: bold;
        margin-bottom: 20px;
        color: #3b2f22;
        text-transform: uppercase;
    }

    .certificate h2, .certificate h3 {
        font-size: 24px;
        margin: 5px 0;
    }

    .certificate p {
        font-size: 18px;
        margin: 10px 0;
        line-height: 1.6;
    }

    .certificate .highlight {
        font-size: 20px;
        font-weight: bold;
    }

    .certificate .diploma-title {
        font-family: 'Old English Text MT', serif;
        font-size: 28px;
        margin: 30px 0;
    }

    .certificate .signature-section {
        margin-top: 60px;
        padding: 0 60px;
    }

    .certificate .signature-section img {
        width: 140px;
        height: auto;
    }

    .certificate .signature-section p {
        font-size: 14px;
        margin: 4px 0 0 0;
    }

    .certificate .seal {
        width: 80px;
        margin: 30px auto 0;
    }

    .certificate .footer {
        font-size: 14px;
        margin-top: 30px;
    }

    .subjects ul {
        font-size: 16px;
        text-align: left;
        padding-left: 40px;
        margin: 10px auto;
        width: 70%;
    }

    .subjects li {
        margin-bottom: 6px;
    }

    .footer {
        margin-top: 10px !important;
        margin-bottom: -20px !important;
        font-size: 12px;
        color: gray;
    }

    /* Signature Section Layout */
    .signature-row {
        width: 100%;
        display: block;
        margin-top: 30px;
    }

    .signature-left, .signature-right {
        text-align: center;
        width: 45%;
        float: left;
    }

    .signature-right {
        float: right;
    }

    .signature-left img, .signature-right img {
        max-width: 150px;
        height: auto;
    }

    .signature-left p, .signature-right p {
        margin-top: -4px;
    }

    .signature-left p strong, .signature-right p strong {
        font-size: 16px;
    }

    /* Clear the floats to prevent layout issues */
    .signature-row:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Avoid unnecessary page break */
    .no-page-break {
        page-break-after: auto;
    }

</style>

</head>
<body>
    @foreach($students as $student)
    <div class="certificate">
        
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
          
            <p style="text-align: left; margin-left:30px"><i>The following subjects were completed:-</i></p>
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
        <div class="signature-row">
            <div class="signature-left">
                <img src="{{ url('resources/assets/images/ci_kisalo.png') }}" alt="CI Signature">
                <p><strong>OMARY S. KISALO - ACP</strong></p>
                <p>Chief Instructor</p>
            </div>

            <div class="signature-right">
                <img src="{{ url('resources/assets/images/co_mungi.png') }}" alt="CO Signature">
                <p><strong>RAMADHANI A. MUNGI - SACP</strong></p>
                <p>Commandant</p>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Issued on: {{ now()->toDateString() }}</p>
        </div>
    </div>


    @endforeach
</body>
</html>
