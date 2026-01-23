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
    <title>MPS Report</title>
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
        }

        * {
            pointer-events: none;
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
                <h4>RIPOTI YA MAHABUSU                 {{ \Carbon\Carbon::parse($data['start_date'])->format('d F, Y') }} 
                                                        - {{ \Carbon\Carbon::parse($data['end_date'])->format('d F, Y') }}
                ju</h4>
            </div>
        </div>
        <table class="table-sm table">
            <thead>
                <tr>
                    <th>Kipindi</th>
                    <th>Tarehe</th>
                    <th>Mahabusu</th>
                    <th>Waliotembelewa</th>
                </tr>
            </thead>
            <tbody>
                {{-- Daily --}}
                <tr>
                    <td colspan="4" style="font-weight: bold; background: #eee;">
                        {{ 'Ndani ya siku ' . count($data['dailyCounts']) }}
                    </td>
                </tr>
                @foreach($data['dailyCounts'] as $day)
                <tr>
                    <td></td>
                    <td>{{ \Carbon\Carbon::parse($day['date'])->format('d F, Y') }}</td>
                    <td>{{ $day['mps_count'] }}</td>
                    <td>{{ $day['visitor_count'] }}</td>
                </tr>
                @endforeach

                {{-- Weekly --}}
                <tr>
                    <td colspan="4" style="font-weight: bold; background: #eee;">
                        {{ 'Ndani ya wiki ' . count($data['weeklyCounts']) }}
                    </td>
                </tr>
                @foreach($data['weeklyCounts'] as $week)
                <tr>
                    <td></td>
                    <td>{{ $week['week_start'] }}</td>
                    <td>{{ $week['mps_count'] }}</td>
                    <td>{{ $week['visitor_count'] }}</td>
                </tr>
                @endforeach

                {{-- Monthly --}}
                <tr>
                    <td colspan="4" style="font-weight: bold; background: #eee;">
                        {{ 'Ndani ya miezi ' . count($data['monthlyCounts']) }}
                    </td>
                </tr>
                @foreach($data['monthlyCounts'] as $month)
                <tr>
                    <td></td>
                    <td>{{ $month['month_label'] }}</td>
                    <td>{{ $month['mps_count'] }}</td>
                    <td>{{ $month['visitor_count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <h4>WALIOPO MAHABUSU</h4>
        <table class="table table-responsive table-sm">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Majina</th>
                    <th>Platun</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @foreach ($data['currentLockedUpStudents'] as $student)
                <tr>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ ++$i }}.</td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">
                        {{ $student['student']->force_number ?? '' }} {{ $student['student']->first_name }}
                        {{ $student['student']->last_name }}
                    </td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">
                        {{ $student['student']->company->name }} - {{ $student['student']->platoon }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="page-break"></div>

        <h4>WALIOINGIA MAHABUSU MARA NYINGI ZAIDI</h4>
        <table class="table table-responsive table-sm">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Majina</th>
                    <th>Platun</th>
                    <th>Idadi</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @foreach ($data['topLockedUpStudents'] as $student)
                <tr>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ ++$i }}.</td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">
                        {{ $student['student']->force_number ?? '' }} {{ $student['student']->first_name }}
                        {{ $student['student']->last_name }}
                    </td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">
                        {{ $student['student']->company->name }} - {{ $student['student']->platoon }}
                    </td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ $student['count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <h4>WALIOTEMBELEWA ZAIDI</h4>
        <table class="table table-responsive table-sm">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Majina</th>
                    <th>Platun</th>
                    <th>Idadi</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @foreach ($data['topVisitedStudents'] as $student)
                <tr>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ ++$i }}.</td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">
                        {{ $student['student']->force_number ?? '' }} {{ $student['student']->first_name }}
                        {{ $student['student']->last_name }}
                    </td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">
                        {{ $student['student']->company->name }} - {{ $student['student']->platoon }}
                    </td>
                    <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ $student['count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </center>
</body>
</html>
