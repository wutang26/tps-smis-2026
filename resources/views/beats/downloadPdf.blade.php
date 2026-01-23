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
    <title>Document</title>

    <style>
        .page-break {
            page-break-inside: auto;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            height: 1%;
        }

        table#t01 {
            width: 100%;
            background-color: #f2f2d1;
        }
    </style>
</head>

<body>
    <?php 
            use Carbon\Carbon;
        ?>
    <center class="d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h4><b>TANZANIA POLICE SCHOOL-MOSHI</b></h4>
            <img src="{{ public_path('logo.png') }}" style="height:50px; width:50px" alt="Logo"></br>
        </div></br>
        <span> RATIBA YA
            @if ($beatType_id == 1)
                MALINDO
            @else
                DORIA
            @endif
            {{$company->name}} COMPANY
        </br>
        </span>

    </center>

    

</body>

</html>