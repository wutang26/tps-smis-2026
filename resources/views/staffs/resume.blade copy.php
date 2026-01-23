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
<div id="printArea">
    <div class="row gx-4">
        <div class="col-sm-12 col-12">
            <div class="card mb-4 p-4">
                <div class="card-body">
                    <div class="row header">
                        <div class="col-2">
                            @if ($staff->photo)
                            <div class="photo mt-3">
                                <img src="{{ asset($staff->photo) }}" alt="Photo" height="100">
                            </div>
                            @else
                            <h1 style="font-size: 300%;">BJ</h1>
                            @endif
                        </div>
                        <div class="col-10 text-center">
                            <h1>{{ $staff->firstName }} {{ $staff->middleName }} {{ $staff->lastName }}</h1>
                            <div class="bottom-line"></div>
                            <div style="text-align: center; padding-top: 10px;">
                                <p>{{ $staff->email }} | {{ $staff->phoneNumber }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <h2>Personal Information</h2>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>{{$staff->currentAddress}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Location</th>
                                    <td>{{$staff->location}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Religion</th>
                                    <td>{{$staff->religion}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Tribe</th>
                                    <td>{{$staff->tribe}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>{{$staff->currentAddress}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date of Birth:</th>
                                    <td>{{$staff->DoB}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Gender</th>
                                    <td>{{$staff->gender}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Marital Status</th>
                                    <td>{{$staff->maritalStatus}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>

                                </tr>

                                <p><strong>Date of Birth:</strong> {{ $staff->DoB }}</p>
                    <p><strong>Gender:</strong> {{ $staff->gender }}</p>
                    <p><strong>Marital Status:</strong> {{ $staff->maritalStatus }}</p>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>

    @endsection