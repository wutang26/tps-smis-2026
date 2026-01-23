@extends('layouts.main')

@section('content')
    <h1>Edit Timetable Entry</h1>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('timetable.update', $timetable->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="company">Company:</label>
            <select name="company" id="company" class="form-control">
                <option value="HQ" {{ $timetable->company == 'HQ' ? 'selected' : '' }}>HQ</option>
                <option value="A" {{ $timetable->company == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ $timetable->company == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ $timetable->company == 'C' ? 'selected' : '' }}>C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="day">Day:</label>
            <select name="day" id="day" class="form-control">
                <option value="Monday" {{ $timetable->day == 'Monday' ? 'selected' : '' }}>Monday</option>
                <option value="Tuesday" {{ $timetable->day == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                <option value="Wednesday" {{ $timetable->day == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                <option value="Thursday" {{ $timetable->day == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                <option value="Friday" {{ $timetable->day == 'Friday' ? 'selected' : '' }}>Friday</option>
                <option value="Saturday" {{ $timetable->day == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                <option value="Sunday" {{ $timetable->day == 'Sunday' ? 'selected' : '' }}>Sunday</option>
            </select>
        </div>

        <div class="form-group">
            <label for="time_slot">Time Slot:</label>
            <input type="text" name="time_slot" id="time_slot" class="form-control" value="{{ $timetable->time_slot }}">
        </div>

        <div class="form-group">
            <label for="activity">Activity:</label>
            <input type="text" name="activity" id="activity" class="form-control" value="{{ $timetable->activity }}">
        </div>

        <div class="form-group">
            <label for="venue">Venue:</label>
            <input type="text" name="venue" id="venue" class="form-control" value="{{ $timetable->venue }}">
        </div>

        <div class="form-group">
            <label for="instructor">Instructor:</label>
            <input type="text" name="instructor" id="instructor" class="form-control" value="{{ $timetable->instructor }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('timetable.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
