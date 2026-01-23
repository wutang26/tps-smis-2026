@extends('layouts.main')

@section('scrumb')
<!-- Breadcrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bulk Update Students</li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Breadcrumb ends -->
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

@php
    $successMessage = session('success');
    $importWarnings = session('warnings');
    $importErrors = session('errors');
@endphp

@if ($successMessage)
    <div class="alert alert-success">
        ✅ {{ $successMessage }}
    </div>
@endif

@if (!empty($importWarnings))
    <div class="alert alert-warning">
        <h5>🔔 Warnings:</h5>
        <ul>
            @foreach ($importWarnings as $warning)
                <li>{{ $warning }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (!empty($importErrors))
    <div class="alert alert-danger">
        <h5>⚠️ Errors:</h5>
        <ul>
            @foreach ($importErrors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <h5>🚨 Validation Issue:</h5>
        <ul>
            @foreach ($errors->all() as $validationError)
                <li>{{ $validationError }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="{{ route('studentDownloadSample') }}" class="btn btn-sm btn-success">
        <i class="bi bi-download"></i>&nbsp;Download Sample for Updating Students
    </a>
</div>

<div class="mt-3">
    <p>📄 Please download the sample Excel file and review your student list before submitting. If you encounter any issues, feel free to contact support.</p>
</div>

<div class="mt-4 d-flex justify-content-end">
    <form method="POST" action="{{ url('students/bulk-update-students') }}" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input required type="file" name="students_file" class="form-control form-control-sm" style="height: 30px; width: 60%;">
        <button type="submit" class="btn btn-primary btn-sm" title="Upload by CSV/Excel file" style="height: 30px;">
            <i class="bi bi-upload"></i>&nbsp;Upload Updates
        </button>
    </form>
</div>
@endsection
