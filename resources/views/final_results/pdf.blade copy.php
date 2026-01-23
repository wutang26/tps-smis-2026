<!DOCTYPE html>
<html>
<head>
    <style>
        
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            margin: 0;
            font-size: 14px; /* Reduce font size */
        }
        .header, .details, .results-container, .row {
            width: 100%;
            margin: -20px 0;
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 0px; /* Reduce padding */
            text-align: center;
        }

        p {
            margin: 5px; /* Reduce margin */
        }
        .signature, .date-issue {
            flex: 1;
            text-align: center;
        }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        /* body {
            font-family: Arial, sans-serif;
            padding: 0;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        } */
        .container {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 50;
            top: 2px;
            width: 100px;
        }
        .profile-photo {
            position: absolute;
            right: 50;
            top: 18px; /* Adjusted the top position to move the photo down */
            width: 100px;
        }

        .header, .details, .results-container, .signature {
            margin-bottom: 20px;
        }
        .header h1, .header h2, .header p {
            text-align: center;
            margin: 0;
        }
        .details table, .results-container table, .grading-scale table, .classification-scale table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: px;
            text-align: left;
        }
        .results-container {
            display: table;
            width: 100%;
            margin-top:-50px !important;
        }
        .semester-table {
            display: table-cell;
            width: 49%;
            /* display: inline-block; */
        }
        .sem1{
            padding-right: 2 ;
        }
        
        .sem2{
            padding-left: 2 ;
        }

        .signature {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .grading-scale, .classification-scale {
            width: 50%;
            /* display: inline-block; */
            vertical-align: center;
            position:relative;
            margin: auto;
            border-collapse: collapse;
            justify-content: center;
            font-size: 18px; /* Reduce font size */
        }

        .gradin{
            top:100;
        }
        .classif{
            top:120;
        }

        .bottom-container {
            width: 100%;
        }
        .col {
            display: inline-block;
            width: 49%;
            vertical-align: top;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }


        h3 {
            margin: 0;
            padding: 5px; /* Reduce padding */
            font-size: 18px; /* Reduce header font size */
        }


        .row {
            display: flex;
            justify-content: space-between;
        }
        .col {
            flex: 1;
            text-align: center;
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
            <p>This is to certify that {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }} whose details are specified in the table below attained the grades against respective modules shown in the table for Examination results.</p>
            <table>
                <thead>
                    <tr>
                        <th width="35%">Award Holder</th>
                        <th width="10%">Birth Date</th>
                        <th width="5%">Sex</th>
                        <th width="20%">Registration No</th>
                        <th width="15%">Date of Entry</th>
                        <th width="15%">Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>{{ $student->admittedStudent->registration_number ?? ''}}</td>
                        <td>{{ $student->admittedStudent->admitted_date ?? ''}}</td>
                        <td>{{ $student->admittedStudent->completion_date ?? ''}}</td>
                    </tr>
                    <tr>
                        <th colspan="3">Programme/Course Followed (Accredited by NACTE)</th>
                        <td colspan="3">{{ $student->admittedStudent->programme->programmeName ?? ''}}</td>
                    </tr>
                    <tr>
                        <th colspan="3">NTA Level Awarded</th>
                        <td colspan="3">{{ $student->admittedStudent->programme->studyLevel->description ?? ''}}</td>
                    </tr>
                    <tr>
                        <th colspan="3">Overall GPA: {{ $student->overallGPA ?? ''}}</th>
                        <td colspan="3">Class Awarded: {{ $student->classAwarded ?? ''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <h4>EXAMINATION RESULTS</h4>

        <div class="results-container">
            <div class="semester-table sem1">
                <h4>Semester I Results</h4>
                <table style="margin-top: -15px">
                    <thead>
                        <tr>
                            <th width="10%">Module Code</th>
                            <th width="30%">Module Name</th>
                            <th width="5%">Units</th>
                            <th width="5%">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->finalResults as $result)
                            @if($result->semester_id == 1)
                                <tr>
                                    <td>{{ $result->course->courseCode ?? ''}}</td>
                                    <td>{{ $result->course->courseName ?? ''}}</td>
                                    <td>{{ $result->course->programmes->first()->pivot->credit_weight ?? '' }}</td>
                                    <td>{{ $result->grade ?? ''}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2"><b>Semester I GPA:</b></td>
                            <td colspan="2">{{ $student->semesterOneGPA }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="semester-table sem2">
                <h4>Semester II Results</h4>
                <table style="margin-top: -15px">
                    <thead>
                        <tr>
                            <th>Module Code</th>
                            <th>Module Name</th>
                            <th>Units</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->finalResults as $result)
                            @if($result->semester_id == 2)
                                <tr>
                                    <td>{{ $result->course->courseCode ?? ''}}</td>
                                    <td>{{ $result->course->courseName ?? ''}}</td>
                                    <td>{{ $result->course->programmes->first()->pivot->credit_weight ?? '' }}</td>
                                    <td>{{ $result->grade ?? ''}}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2"><b>Semester II GPA:</b></td>
                            <td colspan="2">{{ $student->semesterTwoGPA }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <div class="bottom-container">
        <div class="col text-left signature">
            <p>OMARY S. KISALO - ACP</p>
            <p>Chief Instructor</p>
        </div>
        <div class="col text-right date-issue">
            <p>{{ now()->toDateString() }}</p>
            <p>Date of Issue</p>
        </div>
    </div>
</div>

    </div>

    <div style="page-break-after: always;"></div>
    <center>
    <div class="back">
        <div class="grading-scale gradin">
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

        <div class="classification-scale classif">
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
                        <td>3.5 – 4.0</td>
                        <td>First Class</td>
                    </tr>
                    <tr>
                        <td>3.0 – 3.4</td>
                        <td>Second Class</td>
                    </tr>
                    <tr>
                        <td>2.0 – 2.9</td>
                        <td>Pass</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </center>

        <div style="page-break-after: always;"></div>
        @endforeach

</body>

</html>
