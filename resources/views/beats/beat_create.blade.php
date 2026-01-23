@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="">Beats</a></li>
        <li class="breadcrumb-item active"><a href="">Guards and Patrols</a></li>
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

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

<div class="container">
    <h2 class="mt-4">Beats Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('beats.fillBeats') }}" method="POST" class="my-3">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <input type="date" name="date" min="{{Carbon\Carbon::today()->format('Y-m-d')}}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Generate Beats</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Area</th>
                <th>Students</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beats as $beat)
                <tr>
                    <td>{{ $beat->id }}</td>
                    <td>{{ $beat->beatType->name }}</td>
                    <td>
                        @if($beat->guardArea_id)
                        {{ $beat->guardArea->name ?? ''}}
                        @elseif($beat->patrolArea_id)
                        {{ $beat->patrolArea->name ?? ''}}
                        @endif
                    </td>
                    <td>
                    @php
                        $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                        $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                    @endphp

                        
                        @foreach($students as $student)
                            {{ $student->first_name }} {{ $student->last_name }}<br>
                        @endforeach
                    </td>
                    <td>{{ $beat->date }}</td>
                    <td>{{ $beat->start_at }}</td>
                    <td>{{ $beat->end_at }}</td>
                    <td>
                        <form action="{{ route('beats.destroy', $beat->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection