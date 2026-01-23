@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/mps/">MPS</a></li>
                <!-- <li class="breadcrumb-item active" aria-current="page"><a href="#"></a></li> -->
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
{{$mpsStudents}}
@endsection