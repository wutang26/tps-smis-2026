@extends('layouts.main')

@section('style')
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    background-color: #f9f9f9;
}

.card {
    border: none;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
}

.header {
    background-color: rgba(210, 219, 228, 0.45);
    /* text-align: center; */
    margin-bottom: 20px;
    padding: 50px 0 50px 0;
    border-bottom: 2px solid #eee;
}

.header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.header p {
    color: #7f8c8d;
    margin-bottom: 2px;
}

.photo img {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.section {
    margin-top: 30px;
    padding: 15px 20px;
    border-left: 5px solid #2c3e50;
    background-color: #fdfdfd;
    border-radius: 8px;
}

.section h2 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2c3e50;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
    margin-bottom: 15px;
}

.section p {
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.section p strong {
    color: #34495e;
}

@media only screen and (min-width: 576px) {
    #pfno {
        margin-left: 12.5% !important;
        background-color: red;
    }
}

@media only screen and (max-width: 600px) {
    .abcd {
        font-size: 15px !important;
    }
}

.bottom-line {
    border-bottom: 2px solid rgba(61, 91, 122, 0.4);
    margin: 0 10% 0 10%;
    width: 80%;
}
</style>
@endsection

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Staffs</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Staff Curriculum Vitae (CV)</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection


@section('content')
@include('layouts.sweet_alerts.index')
<div class="d-flex justify-content-center">
    <h2>{{$staff->forceNumber}} {{$staff->rank}} {{substr($staff->firstName,0,1)}}.{{substr($staff->middleName,0,1)}}
        {{$staff->lastName}} - CV</h2>
</div>
<div class="d-flex justify-content-between">
    <a href="{{route('staff.create-cv', ['staffId' => $staff->id])}}" class="btn btn-secondary">Edit</a>
    <a href="{{route('staff.generateResumeePdf', ['staffId' => $staff->id])}}" class="btn btn-success">Print</a>
</div>
<div class="" style="padding: 0 15% 0 15%">
    <h3>(A) PERSONAL PARTICULARS</h3><br><br>
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

    <h3>(B) EDUCATION AND TRAINING / ELIMU NA MAFUNZO YA UJUZI</h3><br><br>
    <h3>1. Elimu ya Msingi</h3><br><br>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Name of school</th>
                <th>Village</th>
                <th>District</th>
                <th>Year of Admission</th>
                <th>Year of graduation</th>
            </tr>
        </thead>
        <tbody>
            @if ($staff->schools)
            @foreach ($staff->schools as $school)
            @if ($school->education_level_id == 1)
            <tr>
                <td>{{ $school->name }}</td>
                <td>{{ $school->village }}</td>
                <td>{{ $school->district }}</td>
                <td>{{ $school->admission_year }}</td>
                <td>{{ $school->graduation_year }}</td>
            </tr>
            @endif
            @endforeach
            @endif
        </tbody>
    </table>

    <h3>2. Elimu ya Sekondari</h3><br><br>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Name of school</th>
                <th>Village</th>
                <th>District</th>
                <th>Year of Admission</th>
                <th>Year of graduation</th>
            </tr>
        </thead>
        <tbody>
            @if ($staff->schools)
            @foreach ($staff->schools as $school)
            @if ($school->education_level_id == 2 || $school->education_level_id == 3)
            <tr>
                <td>{{$school->education_level->name}}</td>
                <td>{{ $school->name }}</td>
                <td>{{ $school->village }}</td>
                <td>{{ $school->district }}</td>
                <td>{{ $school->admission_year }}</td>
                <td>{{ $school->graduation_year }}</td>
            </tr>
            @endif
            @endforeach
            @endif
        </tbody>
    </table>

    <h3>3. Colleges/Vyuo alivyosoma</h3><br><br>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>College/University</th>
                <th>Duration</th>
                <th>Region/country</th>
                <th>Award</th>
            </tr>
        </thead>
        <tbody>
            @if ($staff->schools)
            @foreach ($staff->schools as $school)
            @if ($school->education_level_id == 4)
            <tr>
                <td>{{ $school->name }}</td>
                <td>{{ $school->duration }}</td>
                <td>{{ $school->country }}</td>
                <td>{{ $school->award }}</td>
            </tr>
            @endif
            @endforeach
            @endif
        </tbody>
    </table>

    <h3>(C) OTHER COURSES, PROFESSIONAL EXAMINATION AND WORKSHOP ATTENDED</h3><br><br>
    <h4>CURRENT TITLE / CHEO CHAKO KWA SASA <strong>{{$staff->rank}}</strong></h4><br><br>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <td></td>
                <td>Duration</td>
                <td>Theme and Award</td>
                <td>College/Organization</td>
                <td>Venue</td>
            </tr>

        </thead>
        <tbody>
            @if ($staff->schools)
            @foreach ($staff->schools as $school)
            @if ($school->education_level_id == 5)
            <tr>
                <td></td>
                <td>{{ $school->duration }}</td>
                <td>{{ $school->award }}</td>
                <td>{{ $school->name }}</td>
                <td>{{ $school->venue }}</td>
            </tr>
            @endif
            @endforeach
            @endif
        </tbody>
    </table>

    <h3>(D) WORK AND EXPERIENCE</h3><br><br>

    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <td>S/N</td>
                <td>Year</td>
                <td>Organization</td>
                <td>Location</td>
                <td>Title</td>
                <td>Duties</td>
            </tr>

        </thead>
        <tbody>
            @php
            $i = 0;
            @endphp
            @if ($staff->work_experiences)
            @foreach ($staff->work_experiences as $work_experience)
            <tr>
                <td>{{++$i}}</td>
                <td>{{ substr($work_experience->start_date, 0, 4)}} - {{ substr($work_experience->end_date, 0, 4)}}</td>
                <td>{{ $work_experience->institution }}</td>
                <td>{{ $work_experience->address }}</td>
                <td>{{ $work_experience->job_title }}</td>
                <td>
                    @php
                    $duties = $work_experience->duties == null? null :
                    json_decode($work_experience->duties);
                    @endphp
                    <ul>
                        @foreach ($duties as $duty)
                        <li>{{ $duty }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <br>
    <h3>(E) REFEREES/WADHAMINI</h3>
    <br><br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>S/N</td>
                <th>NAME</th>
                <th>TITLE</th>
                <th>ORGANIZATION</th>
                <th>ADDRESS</th>
                <th>EMAIL</th>
                <th>PHONE</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i = 0;
            @endphp
            @foreach ($staff->referees as $referee)
            <tr>
                <td>{{++$i}}</td>
                <td>{{$referee->referee_fullname}}</td>
                <td>{{$referee->title}}</td>
                <td>{{$referee->organization}}</td>
                <td>{{$referee->address}}</td>
                <td>{{$referee->email_address}}</td>
                <td>{{$referee->phone_number}}</td>


            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection