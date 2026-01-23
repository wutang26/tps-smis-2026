<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Kibali cha Ruhusa Kilichokataliwa</title>

    <style>
        /* ----- PAGE SIZE & MARGINS ----- */
        @page   { margin: 25px;  }
        body    {
             /*font-family: DejaVu Sans, sans-serif;
                   font-size: 14px;  line-height: 1.55; */
                  margin-top: 10px; /* Adjust top margin */
                  color:#000;  padding:32px; }

        /* ----- HEADER ----- */
        .header           { text-align:center; margin-bottom:10px; }
        .header img       { width:80px; height:80px; }
        .header h2        { margin:6px 0 4px; font-size:20px; }
        .header h3        { margin:0; font-size:16px; letter-spacing:.5px; }

        /* ----- PERMIT TEXT ----- */
        .permit-content   { margin-top:18px; }
        .permit-content p { margin:12px 0; text-align:justify; }

        /* ----- SIGNATURES ----- */
        /* .signatures       { margin-top:26px; } */
        .signatures table { width:100%; text-align:center; }
        .signatures td    { 
            /* padding-top:36px;  */
            vertical-align:bottom; }
        .signatures img   { width:65px; }

        /* ----- FOOTER ----- */
        .footer           { text-align:center; margin-top:40px; font-size:13px; }

        /* ----- WATERMARK ----- */
        .watermark1{
            position:fixed;   top:25%; left:50%;
            width:25%; height:25%;
            transform:translate(-50%, -50%);
            opacity:.06; z-index:-1;
        }
        .watermark2{
            position:fixed;   top:65%; left:50%;
            width:25%; height:25%;
            transform:translate(-50%, -50%);
            opacity:.06; z-index:-1;
        }
    </style>
</head>

<body>

    {{-- ---------- HEADER ---------- --}}
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Nembo ya Polisi">
        <h2>SHULE YA POLISI TANZANIA - MOSHI</h2>
        <h3>KIBALI KILICHOKATALIWA CHA MWANAFUNZI KUTOKA NJE YA SHULE</h3>
    </div>

    {{-- Watermark --}}
    <img src="{{ public_path('logo.png') }}" class="watermark1" alt="Watermark">

    {{-- ---------- PERMIT BODY ---------- --}}
    <div class="permit-content">
        <p>
            Mwanafunzi {{ 
            $leaveRequest->student->first_name . ' ' .
            $leaveRequest->student->middle_name . ' ' .
            $leaveRequest->student->last_name }},
            kutoka Kombania {{ $leaveRequest->company->name }},
            Platuni namba {{ $leaveRequest->platoon }},
            hajaruhusiwa kutoka nje ya shule kwenda
            {{ $leaveRequest->location }}
            kwa sababu {{ $leaveRequest->reason }},
            Namba ya simu: {{ $leaveRequest->phone_number }}
        </p>

        
    </div>

    {{-- ---------- SIGNATURES ---------- --}}
    <div class="signatures">
        <table>
            <tr>
                <td>
                    <img src="{{ public_path('signatures/oc.png') }}" alt="S/M Signature"><br>
                    Sir Major
                </td>
                <td>
                    <img src="{{ public_path('signatures/oc.png') }}" alt="OC Signature"><br>
                    OC-TPS Moshi
                </td>
                <td>
                    <img src="{{ public_path('signatures/oc.png') }}" alt="C/I Signature"><br>
                    ChiefInstructor-TPS Moshi
                </td>
            </tr>
        </table>
    </div>
       
    {{-- ---------- FOOTER ---------- --}}
    <div class="footer">
        © {{ date('Y') }} Jeshi la Polisi Tanzania. Haki zote zimehifadhiwa.
    </div>


<hr class="mt-3 mb-3" style="margin: 75px 0 50px 0;">


    {{-- ---------- HEADER ---------- --}}
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Nembo ya Polisi">
        <h2>SHULE YA POLISI TANZANIA - MOSHI</h2>
        <h3>KIBALI KILICHOKATALIWA CHA MWANAFUNZI KUTOKA NJE YA SHULE</h3>
    </div>

    {{-- Watermark --}}
    <img src="{{ public_path('logo.png') }}" class="watermark1" alt="Watermark">

    {{-- ---------- PERMIT BODY ---------- --}}
    <div class="permit-content">
        <p>
            Mwanafunzi {{ 
            $leaveRequest->student->first_name . ' ' .
            $leaveRequest->student->middle_name . ' ' .
            $leaveRequest->student->last_name }},
            kutoka Kombania {{ $leaveRequest->company->name }},
            Platuni namba {{ $leaveRequest->platoon }},
            hajaruhusiwa kutoka nje ya shule kwenda
            {{ $leaveRequest->location }}
            kwa sababu {{ $leaveRequest->reason }},
            Namba ya simu: {{ $leaveRequest->phone_number }}
        </p>

        
    </div>

    {{-- ---------- SIGNATURES ---------- --}}
    <div class="signatures">
        <table>
            <tr>
                <td>
                    <img src="{{ public_path('signatures/oc.png') }}" alt="S/M Signature"><br>
                    Sir Major
                </td>
                <td>
                    <img src="{{ public_path('signatures/oc.png') }}" alt="OC Signature"><br>
                    OC-TPS Moshi
                </td>
                <td>
                    <img src="{{ public_path('signatures/oc.png') }}" alt="C/I Signature"><br>
                    ChiefInstructor-TPS Moshi
                </td>
            </tr>
        </table>
    </div>
       
    {{-- ---------- FOOTER ---------- --}}
    <div class="footer">
        © {{ date('Y') }} Jeshi la Polisi Tanzania. Haki zote zimehifadhiwa.
    </div>

</body>
</html>
