<!DOCTYPE html>
<html>
<head>
    <title>Academic Transcript</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .container {
            border: 5px solid #000;
            padding: 20px;
            box-sizing: border-box;
            width: 90%;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1, .header h2, .header p {
            margin: 2px 0;
        }
        .details, .results {
            margin-bottom: 10px;
        }
        .details table, .results table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td, .results th, .results td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 12px;
        }
        .results th {
            background-color: #f2f2f2;
        }
    </style>
</head>
@foreach($students as $student)
<body>
    <div class="container">
        <div class="header">
            <h1>THE UNITED REPUBLIC OF TANZANIA</h1>
            <h2>MINISTRY OF HOME AFFAIRS</h2>
            <h2>TANZANIA POLICE FORCE</h2>
            <h2>TANZANIA POLICE SCHOOL - MOSHI</h2>
            <p>NATIONAL TECHNICAL AWARD (NTA)</p>
            <p>ACADEMIC TRANSCRIPT</p>
        </div>

        <div class="details">
            <p>This is to certify that {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }} whose details are specified in the table below attained the grades against respective modules shown in the table for examination results.</p>
            <table>
                <thead>
                    <tr>
                        <th>Award Holder</th>
                        <th>Birth Date</th>
                        <th>Sex</th>
                        <th>Registration No</th>
                        <th>Date of Entry</th>
                        <th>Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->birthdate }}</td>
                        <td>{{ $student->sex }}</td>
                        <td>{{ $student->registration_number }}</td>
                        <td>{{ $student->date_of_entry }}</td>
                        <td>{{ $student->completion_date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="results">
            <h3>EXAMINATION RESULTS</h3>
            <h4>Semester I Results</h4>
            <table>
                <thead>
                    <tr>
                        <th>Module Code</th>
                        <th>Module Name</th>
                        <th>Units</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->semesterOneResults as $result)
                    <tr>
                        <td>{{ $result->module_code }}</td>
                        <td>{{ $result->module_name }}</td>
                        <td>{{ $result->units }}</td>
                        <td>{{ $result->grade }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Semester I GPA: {{ $student->semesterOneGPA }}</p>
            
            <h4>Semester II Results</h4>
            <table>
                <thead>
                    <tr>
                        <th>Module Code</th>
                        <th>Module Name</th>
                        <th>Units</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->semesterTwoResults as $result)
                    <tr>
                        <td>{{ $result->module_code }}</td>
                        <td>{{ $result->module_name }}</td>
                        <td>{{ $result->units }}</td>
                        <td>{{ $result->grade }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Semester II GPA: {{ $student->semesterTwoGPA }}</p>
        </div>
        
        <p>Overall GPA: {{ $student->overallGPA }}</p>
        <p>Class Awarded: {{ $student->classAwarded }}</p>
        <p>Date of Issue: {{ now()->toDateString() }}</p>
        <div style="page-break-after: always;"></div>
    </div>
</body>
@endforeach
</html>
