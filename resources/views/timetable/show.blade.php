@extends('layouts.main')

@section('content')
    <div class="container">
        <h3>Timetable Details</h3>
        <p><strong>Title:</strong> {{ $timetable->title }}</p>
        <p><strong>Date:</strong> {{ $timetable->date }}</p>
        <p><strong>Details:</strong> {{ $timetable->details }}</p>
    </div>
@endsection
