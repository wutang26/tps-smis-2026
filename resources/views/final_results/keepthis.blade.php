<!DOCTYPE html>
<html>
<head>
    <title>Academic Transcript</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
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
            position: relative;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
        }
        .profile-photo {
            position: absolute;
            right: 0;
            top: 40px; /* Adjusted the top position to move the photo down */
            width: 100px;
        }
        .header h1, .header h2, .header p {
            margin: 2px 0;
            font-weight: bold;
        }
        .details, .results {
            margin-bottom: 10px;
        }
        .details table, .results table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td, .results th, .results td {
            border: 2px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        .results th {
            background-color: #f2f2f2;
        }
        .results-container {
            display: flex;
            justify-content: space-between;
        }
        .semester-table {
            width: 48%;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .signature div {
            width: 30%;
            text-align: center;
        }
        .grading-scale, .classification-scale {
            margin-top: 20px;
        }
        .grading-scale table, .classification-scale table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .grading-scale th, .grading-scale td, .classification-scale th, .classification-scale td {
            border: 2px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach($students as $student)
    <div class="container">
        <div class="header">
            <img src="{{ url('resources/assets/images/logo.png') }}" alt="Logo" class="logo">
            <img src="{{ url('storage/app/public/' . $student->photo) }}" alt="Profile Photo" class="profile-photo">
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
            
            <table>
                <thead>
                    <tr>
                        <th>Programme/Course Followed (Accredited by NACTE)</th>
                        <th>Technician Certificate in Police Communication</th>
                    </tr>
                </thead>
            </table>
            
            <table>
                <thead>
                    <tr>
                        <th>NTA Level Awarded</th>
                        <th>National Technical Award (NTA) Level 5</th>
                    </tr>
                </thead>
            </table>
            
            <table>
                <thead>
                    <tr>
                        <th>Overall GPA</th>
                        <th>Class Awarded</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $student->overallGPA }}</td>
                        <td>{{ $student->classAwarded }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="results-container">
            <div class="semester-table">
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
            </div>

            <div class="semester-table">
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
        </div>

        <p>Date of Issue: {{ now()->toDateString() }}</p>

        <div class="signature">
            <div>
                <p>OMARY S. KISALO - ACP</p>
                <p>Chief Instructor</p>
            </div>
            <div>
                <p>Date of Issue</p>
                <p>{{ now()->toDateString() }}</p>
            </div>
        </div>

        <div class="grading-scale">
            <h3>KEY TO GRADING SCALE</h3>
            <table>
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Score Range</th>
                        <th>Points</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A</td>
                        <td>80 – 100</td>
                        <td>4</td>
                        <td>Excellent</td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>65 – 79</td>
                        <td>3</td>
                        <td>Good</td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>50 – 64</td>
                        <td>2</td>
                        <td>Average</td>
                    </tr>
                    <tr>
                        <td>D</td>
                        <td>40 – 49</td>
                        <td>1</td>
                        <td>Poor</td>
                    </tr>
                    <tr>
                        <td>F</td>
                        <td>0 – 39</td>
                        <td>0</td>
                        <td>Failure</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="classification-scale">
            <h3>KEY TO CLASSIFICATION SCALE</h3>
            <table>
                <thead>
                    <tr>
                        <th>Cumulative Average</th>
                        <th>Classification</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>3.5