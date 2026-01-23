@extends('layouts.main')

@section('style')
<style>
    .breadcrumb {
        display: flex;
        width: 100%;
    }
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    #date {
        position: absolute;
        bottom: 10px;
        right: 15px;
    }

    .info-label {
        font-weight: 600;
        color: #333;
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
                <li class="breadcrumb-item">
                    <a href="#">Logged in as <b>{{ auth()->user()->name }}</b></a>
                </li>
                <li class="breadcrumb-item right-align">
                    <a href="#" id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a>
                </li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection


@section('content')

@if (session('pending_message'))
    <div class="alert alert-warning">
        {{ session('pending_message') }}
    </div>
@endif

@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@php
    $student = auth()->user()->student;
@endphp

<h3 class="mb-4">Student Information</h3>

<div class="row g-3">
    <div class="col-md-6">
        <div class="info-label">Force Number:</div>
        <p class="mb-2">{{ $student->force_number }}</p>
    </div>
    <div class="col-md-6">
        <div class="info-label">Registration Number:</div>
        <p class="mb-2">{{ $student->registration_number }}</p>
    </div>
    <div class="col-md-6">
        <div class="info-label">NIDA Number:</div>
        <p class="mb-2">{{ $student->nin }}</p>
    </div>
        <div class="col-md-6">
        <div class="info-label">Phone Number:</div>
        <p class="mb-2">{{ $student->phone }}</p>
    </div>
        <div class="col-md-6">
        <div class="info-label">Bank Name:</div>
        <p class="mb-2">{{ $student->bank_name }}</p>
    </div>
        <div class="col-md-6">
        <div class="info-label">Bank Account Number:</div>
        <p class="mb-2">{{ $student->account_number }}</p>
    </div>
    <div class="col-md-6">
        <div class="info-label">Program:</div>
        <p class="mb-2">{{ $student->sessionProgramme->session_programme_name }}</p>
    </div>

    <div class="col-md-6">
        <div class="info-label">Blood Group:</div>
        <p class="mb-2">{{ $student->blood_group }}</p>
    </div>

    <div class="col-md-6">
        <div class="info-label">Start Date :</div>
        <p class="mb-2">{{ \Carbon\Carbon::parse($student->sessionProgramme->startDate)->format('d F, Y') }}  </p>
    </div>

    <div class="col-md-6">
        <div class="info-label">End Date</div>
        <p class="mb-2">{{ \Carbon\Carbon::parse($student->sessionProgramme->endDate)->format('d F, Y') }}</p>
    </div>

    <div class="col-md-6">
        <div class="info-label">Education Level:</div>
        <p class="mb-2">
          @if ($student->education_level == '4m4')
            Form Four
          @elseif($student->education_level == '4m6')
              Form Six
            @lse
              {{ $student->education_level }}
          @endif
      </p>
    </div>

    <div class="col-md-6">
        <div class="info-label">Study Level:</div>
        <p class="mb-2">{{ $student->programme->studyLevel->description }}</p>
    </div>

    <div class="col-md-6">
        <div class="info-label">Date of Birth:</div>
        <p class="mb-2">{{ \Carbon\Carbon::parse($student->dob)->format('d F, Y') }}</p>
    </div>

    <div class="col-md-6">
        <div class="info-label">Status:</div>
        <p class="mb-2">{{ ucfirst($student->status) }}</p>
    </div>

</div>

@endsection
