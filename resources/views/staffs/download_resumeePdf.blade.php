<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ public_path('css/main.min.css') }}">
        <link rel="stylesheet" href="{{ public_path('css/custom.css') }}">
        <link rel="stylesheet" href="/tps-smis/resources/assets/fonts/bootstrap/bootstrap-icons.min.css" />
        <link rel="stylesheet" href="/tps-smis/resources/assets/css/main.min.css" />
        <link rel="stylesheet" href="/tps-smis/resources/assets/css/custom.css" />
        <title>CV Document</title>

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

        table {
            width: 100%;
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
        td {
    vertical-align: top;
}

td ul {
    padding-left: 20px; /* Adjust indentation if needed */
}

td ul li {
    margin-bottom: 5px; /* Adds space between list items for better readability */
}
    

        </style>
    </head>
</head>

<body>

    <table style="border-collapse: collapse; border: none;">
        <tr>
            <td style="width: 80%; border: none;">
                <center>
                    <h2> {{$staff->forceNumber}} {{$staff->rank}}
                        {{substr($staff->firstName,0,1)}}.{{substr($staff->middleName,0,1)}} {{$staff->lastName}}</h2>
                </center>
            </td>
            @if($staff->photo)
            <td style="width: 20%; border: none;"> <img src="{{ url('storage/app/public/'.$staff->photo) }}"
                    alt="profile_photo" srcset="" width="100" height="100"></td>
            @endif
        </tr>
    </table>

    <div class="">
        <h3>(A) PERSONAL PARTICULARS</h3>
        <table class="table table-sm table-bordered">
        <tbody>
            <tr>
                <th>Surname</th>
                <td>{{$staff->lastName}}</td>
            </tr>
            <tr>
                <th>First Name</th>
                <td>{{$staff->firstName}}</td>
            </tr>
            <tr>
                <th>Middle Name</th>
                <td>{{$staff->middleName}}</td>
            </tr>
            <tr>
                <th>Sex</th>
                <td>{{$staff->gender}}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{$staff->DoB}}</td>
            </tr>
            <tr>
                <th>Nationality</th>
                <td>{{$staff->nationality}}</td>
            </tr>
            <tr>
                <th>Marital Status</th>
                <td>{{$staff->maritalStatus}}</td>
            </tr>
            <!-- father's details -->
            @php
                    $fatherParticulars = $staff->fatherParticulars == null? null :
                    json_decode($staff->fatherParticulars);
                    @endphp
            <tr>
                <th>Father's Names</th>
                <td>{{$fatherParticulars[0]?? null}}</td>
            </tr>
            <tr>
                <th rowspan="4">Father's place of birth</th>
                <td><strong>Village: </strong>{{$fatherParticulars[1]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Ward: </strong>{{$fatherParticulars[2]?? null}}</td>
            </tr>
            <tr>
                <td><strong>District: </strong>{{$fatherParticulars[3]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Region: </strong>{{$fatherParticulars[4]?? null}}</td>
            </tr>

            <!-- mother's details -->
            @php    
                $motherParticulars = $staff->motherParticulars == null? null :
                json_decode($staff->motherParticulars);//dd($motherParticulars );
            @endphp
            <tr>
                <th>Mother's Names</th>
                <td>{{$motherParticulars[0]?? null}}</td>
            </tr>
            <tr>
                <th rowspan="4">Mother's place of birth</th>
                <td><strong>Village: </strong>{{$motherParticulars[1]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Ward: </strong>{{$motherParticulars[2]?? null}}</td>
            </tr>
            <tr>
                <td><strong>District: </strong>{{$motherParticulars[3]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Region: </strong>{{$motherParticulars[4]?? null}}</td>
            </tr>

            <!-- current parent address -->
            @php
                $parentsAddress = $staff->parentsAddress == null? null :
                json_decode($staff->parentsAddress);
                @endphp
            <tr>
                <th rowspan="4">Parent current address</th>
                <td><strong>Village: </strong>{{$parentsAddress[0]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Ward: </strong>{{$parentsAddress[1]?? null}}</td>
            </tr>
            <tr>
                <td><strong>District: </strong>{{$parentsAddress[2]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Region: </strong>{{$parentsAddress[3]?? null}}</td>
            </tr>

            <tr>
                <th>Place of domicile(District)</th>
                <td>{{ $staff->PoD }}</td>
            </tr>

            <tr>
                <th>Languages</th>
                <td>{{$staff->language}}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{$staff->currentAddress}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$staff->email}}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{$staff->phoneNumber}}</td>
            </tr>
        </tbody>
    </table>
@if ($staff->schools->isNotEmpty())
    <div style="page-break-before: always;"></div>
        <h3>(B) EDUCATION AND TRAINING / ELIMU NA MAFUNZO YA UJUZI</h3>
        @php
    $primarySchools = $staff->schools->where('education_level_id', 1);
@endphp

@if ($primarySchools->isNotEmpty())
    <h3>1. Elimu ya Msingi</h3>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Name of School</th>
                <th>Village</th>
                <th>District</th>
                <th>Year of Admission</th>
                <th>Year of Graduation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($primarySchools as $school)
                <tr>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->village }}</td>
                    <td>{{ $school->district }}</td>
                    <td>{{ $school->admission_year }}</td>
                    <td>{{ $school->graduation_year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@php
    // Filter only secondary education levels (e.g., O-Level = 2, A-Level = 3)
    $secondarySchools = $staff->schools->whereIn('education_level_id', [2, 3]);
@endphp

@if ($secondarySchools->isNotEmpty())
    <h3>2. Elimu ya Sekondari</h3>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Level</th>
                <th>Name of School</th>
                <th>Village</th>
                <th>District</th>
                <th>Year of Admission</th>
                <th>Year of Graduation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($secondarySchools as $school)
                <tr>
                    <td>{{ $school->education_level->name }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->village }}</td>
                    <td>{{ $school->district }}</td>
                    <td>{{ $school->admission_year }}</td>
                    <td>{{ $school->graduation_year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

        @php
    // Filter only college/university level records (education_level_id = 4)
    $collegeSchools = $staff->schools->where('education_level_id', 4);
@endphp

@if ($collegeSchools->isNotEmpty())
    <h3>3. Colleges / Vyuo Alivyosoma</h3>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>College/University</th>
                <th>Duration</th>
                <th>Region/Country</th>
                <th>Award</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($collegeSchools as $school)
                <tr>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->duration }}</td>
                    <td>{{ $school->country }}</td>
                    <td>{{ $school->award }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

        @php
    // Filter other courses, professional exams, and workshops (education_level_id = 5)
    $otherCourses = $staff->schools->where('education_level_id', 5);
@endphp

@if ($otherCourses->isNotEmpty())
    <h4>(C) OTHER COURSES, PROFESSIONAL EXAMINATIONS AND WORKSHOPS ATTENDED</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/NO</th>
                <th style="width: 80px;">Duration</th>
                <th>Theme and Award</th>
                <th>College/Organization</th>
                <th>Venue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($otherCourses as $index => $course)
                <tr>
                    <td>{{ $index + 1 }}.</td>
                    <td>{{ $course->duration }}</td>
                    <td>{{ $course->award }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->venue }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif


       @if ($staff->work_experiences && $staff->work_experiences->isNotEmpty())
    <h3>(D) WORK AND EXPERIENCE</h3>

    <h4>
        CURRENT TITLE / CHEO CHAKO KWA SASA:
        <strong>{{ $staff->rank }}</strong>
    </h4>

    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/NO</th>
                <th style="width: 90px;">Year</th>
                <th>Organization</th>
                <th>Location</th>
                <th>Title</th>
                <th>Duties</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff->work_experiences as $work_experience)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>
                        {{ \Carbon\Carbon::parse($work_experience->start_date)->format('Y') }} -
                        {{ \Carbon\Carbon::parse($work_experience->end_date)->format('Y') }}
                    </td>
                    <td>{{ $work_experience->institution }}</td>
                    <td>{{ $work_experience->address }}</td>
                    <td>{{ $work_experience->job_title }}</td>
                    <td>
                        @php
                            $duties = $work_experience->duties ? json_decode($work_experience->duties) : [];
                        @endphp
                        @if (!empty($duties))
                            <ul class="mb-0">
                                @foreach ($duties as $duty)
                                    <li>{{ $duty }}</li>
                                @endforeach
                            </ul>
                        @else
                            <em>No duties listed</em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif


   @if ($staff->referees && $staff->referees->isNotEmpty())
    <h3>(E) REFEREES / WADHAMINI</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>NAME</th>
                {{-- <th>TITLE</th> --}}
                <th>ORGANIZATION</th>
                <th>ADDRESS</th>
                <th>EMAIL</th>
                <th>PHONE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff->referees as $referee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $referee->referee_fullname }}</td>
                    {{-- <td>{{ $referee->title }}</td> --}}
                    <td>{{ $referee->organization }}</td>
                    <td>{{ $referee->address }}</td>
                    <td>{{ $referee->email_address }}</td>
                    <td>{{ $referee->phone_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No referees available.</p>
@endif
@endif
</div>
<br>
<div>
<center style="display:flex;  text-align: end; gap: 50px;" >
    <span>Signature.........................</span>
    <span>Date......................</span>
</center>

    </div>
    
</body>

</html>