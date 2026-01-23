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
    <title>Hospital Report</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 2px 6px;

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

    body {
        padding: 0 20px 0 20px;
        /* You can adjust the padding value as needed */
    }

    * {
        pointer-events: none;
        /* Disable all pointer interactions */
    }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('resources/assets/images/logo.png') }}" alt="Watermark">
    </div>
    <center>
        <h4>SHULE YA POLISI TANZANIA - MOSHI</h4>
        <div class="container" style="margin-top:-20px;">
            <div class="header">
                <div style="text-align: center;">
                    <img src="{{ asset('resources/assets/images/logo.png') }}" style="height:60 !important; width:50"
                        alt="Police Logo">
                </div>
                <h4>RIPOTI YA HOSPITALI 
                @if($start_date && $end_date)
                    KUTOKA TAREHE {{ \Carbon\Carbon::parse($start_date)->format('d F, Y') }} HADI {{ \Carbon\Carbon::parse($end_date)->format('d F, Y') }}
                @else
                    TAREHE
                {{\Carbon\Carbon::now()->format('d F, Y')}}
                @endif
            </h4>
            </div>
        </div>

        <table class="table-sm table">
            <thead>
                <th></th>
                <th>Tarehe </th>
                <th>Waliofika</th>
                <th>Excuse Duty</th>
                <th>Light Duty</th>
                <th>Waliolazwa</th>
            </thead>
            <tbody>
                @foreach($data['dailyCounts'] as $index => $day)
                <tr>
                    {{-- Only set the rowspan for the first row of the period --}}
                    @if($index === 0)
                    <td rowspan="{{ count($data['dailyCounts']) }}">{{ 'Ndani ya Siku '.($start_date && $end_date ? \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) : '7') }}</td> {{-- Example Period --}}
                    @endif
                    <td>{{ \Carbon\Carbon::parse($day['date'])->format('d F, Y') }}</td>
                    <td>{{ $day['total'] }}</td>
                    <td>{{ $day['ED'] }}</td>
                    <td>{{ $day['LD'] }}</td>
                    <td>{{ $day['Adm'] }}</td>
                </tr>
                @endforeach

                @foreach($data['weeklyCounts'] as $index => $week)
                <tr>
                    {{-- Only set the rowspan for the first row of the period --}}
                    @if($index === 0)
                    <td rowspan="{{ count($data['weeklyCounts']) }}">{{ 'Ndani ya Wiki '.($start_date && $end_date ? round(\Carbon\Carbon::parse($start_date)->diffInWeeks(\Carbon\Carbon::parse($end_date))+1,0) : '5') }}</td> {{-- Example Period --}}
                    @endif
                    <td>{{ $week['date'] }}</td>
                    <td>{{ $week['total'] }}</td>
                    <td>{{ $week['ED'] }}</td>
                    <td>{{ $week['LD'] }}</td>
                    <td>{{ $week['Adm'] }}</td>
                </tr>
                @endforeach

                @foreach($data['monthlyCounts'] as $index => $month)
                <tr>
                    {{-- Only set the rowspan for the first row of the period --}}
                    @if($index === 0)
                    <td rowspan="{{ count($data['monthlyCounts']) }}">{{ 'Ndani ya miezi '.($start_date && $end_date ? round(\Carbon\Carbon::parse($start_date)->diffInMonths(\Carbon\Carbon::parse($end_date))+1,0) : '3') }}</td>
                    {{-- Example Period --}}
                    @endif
                    <td>{{ $month['month'] }}</td>
                    <td>{{ $month['total'] }}</td>
                    <td>{{ $month['ED'] }}</td>
                    <td>{{ $month['LD'] }}</td>
                    <td>{{ $month['Adm'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </center><br>
    <h4>WALIOHUDHURIA MARA NYINGI ZAIDI</h4>
    <table class="table table-responsive table-sm">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Majina</th>
                <th>Platuni</th>
                <th>Idadi</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 0;
            @endphp
            @foreach ($data['mostOccurredStudents'] as $patient)
            <tr>
                <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{++$i}}.</td>
                <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">
                    {{ $patient['student']->force_number?? '' }} {{ $patient['student']->first_name }}
                    {{ $patient['student']->last_name }}</td>
                <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{ $patient['student']->company->name }}
                    - {{ $patient['student']->platoon }}</td>
                <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{ $patient['count']}}</td>
            </tr>
            @endforeach

</body>
</html>