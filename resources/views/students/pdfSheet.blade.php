<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ public_path('css/main.min.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/custom.css') }}">
    <link rel="stylesheet" href="/tps-smis/resources/assets/fonts/bootstrap/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/main.min.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/custom.css" />
    <title>Attendance Report</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 2px 2px;

    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    .watermark {
        position: fixed;
        top: 45%;
        left: 50%;
        width: 200px;
        height: 200px;
        opacity: 0.2;
        transform: translate(-50%, -50%);
        z-index: -1;
    }

    .page-break {
        page-break-after: always;
    }


    * {
        pointer-events: none;
        /* Disable all pointer interactions */
    }

    @page :first{
        size: A4 potrait;
        margin: 0 50px 5px 50px;
        /* top, right, bottom, left */
    }

    body {
        margin: 0;
        padding: 0;
        /* important: don't use padding, it breaks DomPDF layout */
    }

    .top-container {
        margin-top: 0;
        /* no need for extra spacing if @page works */
        text-align: center;
    }

    .top-container h4 {
        margin: 0;
    }

    .top-container img {
        height: 40px;
        width: 30px;
        margin: 4px 0;
    }

    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 2 0 2 10px;
        font-size: 10px;
        /* word-wrap: break-word; */

    }

    th {
        background-color: #f0f0f0;
    }


    /* On screen (optional) */
    table {
        font-size: 11px;
        table-layout: fixed;
        width: 100%;
    }

    th,
    td {
        word-wrap: break-word;
        text-align: left;
    }


    .tight-header {
        margin-bottom: 4px !important;
        margin-top: 1px !important;
        padding: 0 !important;
    }

    .tight-table {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('resources/assets/images/logo.png') }}" alt="Watermark">
    </div>
    <center>
        <div class="top-container" style="margin-top: 30px; text-align: center;">
            <h4 class="tight-header">TANZANIA POLICE SCHOOL - MOSHI</h4>

            <img src="{{ asset('resources/assets/images/logo.png') }}" style="height: 40px; width: 30px; margin: 0 0;"
                alt="Police Logo">

            <h4>{{ strtoupper($platoon->company->description) }} - PLT {{ strtoupper($platoon->name) }}</h4>
        </div>
        <table class="table table-sm table-bordered table-hover tight-table">
            <thead>
                <tr>
                    <th style="width: 5%;">S/N</th>
                    <th style="width: 15%;">FORCE NUMBER</th>
                    <th style="width: 50%;">NAMES</th>
                    <!-- <th style="width: 50%;">STATUS</th> -->
                    <th style="width: 30%;">SIGNATURE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td >{{ $loop->iteration }}</td>
                    <td >{{ $student->force_number }}</td>
                    <td >
                        {{ trim("{$student->first_name} {$student->middle_name} {$student->last_name}") }}
                    </td>
                    <!-- <td>{{$student->status}}</td> -->
                    <td ></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <body>

</html>