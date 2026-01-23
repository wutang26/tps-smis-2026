@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                <li class="breadcrumb-item active"><a href="">Exchange</a></li>
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
  <div class="mb-4">
@if ($beat->beatType_id == 1)
<h4>Guard Area : {{$beat->guardArea->name}}</h4>
@else
<h4>Patrol Area: {{$beat->patrolArea->start_area}} - {{$beat->patrolArea->end_area}}</h4>
@endif    
  </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="m-0">
                <form method="POST" action="{{ route('beats.exchange', $beat->id) }}">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label for="">Exchange someone here</label>
                    </div>
                    
                    <select name="current_students[]" class="form-control" multiple="multiple">
                    @foreach ($beat_students as $student)
                        <option value="{{ $student->id }}" >
                            {{ $student->first_name }} {{ $student->last_name }}, Gender: {{ $student->gender }}-PLT{{ $student->platoon }}
                        </option>
                        @endforeach   
                    </select>

                    <label class="form-label" for="abc4">Beats students to exchange</label>
                    <select name="exchange_students[]" class="form-control" multiple="multiple">
                        @foreach ($beatsToExchange as $value => $student)
                        <option value="{{ json_encode($student) }}" >
                            {{ $student['student']->first_name }} {{ $student['student']->last_name }}, Gender: {{ $student['student']->gender }}-PLT{{ $student['student']->platoon }}
                        </option>
                        @endforeach
                    </select>    
                    <div class="mt-2">
                        <button type="submit" class="btn btn-sm btn-primary">Exchange</button>
                    </div>                
                </form>
            </div>
        </div>
    </div>

@endsection