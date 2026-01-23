@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a href="#">Weapons</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')


@endsection
