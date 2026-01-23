@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                <li class="breadcrumb-item active"><a href="">Edit</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<!-- @session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession -->
@if ($beat->beatType_id == 1)
@else
<h4>Patrol Area: {{$beat->patrolArea->start_area}} - {{$beat->patrolArea->end_area}}</h4>
@endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="m-0">
                <form method="POST" action="{{ route('beat.update', $beat->id) }}">
                    @csrf
                    @method('POST')
                    <label for="">Replace someone here</label>
                    <select name="replace_students[]" class="form-control" multiple="multiple">
                    @foreach ($stud as $value => $student)
                        <option value="{{ $student->id }}" >
                            {{ $student->first_name }} {{ $student->last_name }}, Gender: {{ $student->gender }}-PLT{{ $student->platoon }}
                        </option>
                        @endforeach   
                    </select>

                    <label class="form-label" for="abc4">Eligible students</label>
                    <select name="students[]" class="form-control" multiple="multiple">

                        @foreach ($eligible_students as $value => $student)
                        <option value="{{ $student->id }}" >
                            {{ $student->first_name }} {{ $student->last_name }}, Gender: {{ $student->gender }}-PLT{{ $student->platoon }}
                        </option>
                        @endforeach
                    </select>    
                    <div class="mt-2">
                        <button type="submit" class="btn btn-sm btn-primary">Replace</button>
                    </div>                
                </form>
            </div>
        </div>
    </div>

@endsection