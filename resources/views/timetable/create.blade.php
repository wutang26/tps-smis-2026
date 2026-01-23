@extends('layouts.main')

@section('content')
    <h1>Add Timetable Entry</h1>

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

    <form action="{{ route('timetable.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="company">Company:</label>
            <select name="company" id="company" class="form-control">
                <option value="HQ">HQ</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="day">Day:</label>
            <select name="day" id="day" class="form-control">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>

        <div class="form-group">
            <label for="time_slot">Time Slot:</label>
            <select name="time_slot" id="time_slot" class="form-control">
                <option value="08:00 AM - 10:00 AM">08:00 AM - 10:00 AM</option>
                <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM (Tea Break)</option>
                <option value="11:00 AM - 1:00 PM">11:00 AM - 1:00 PM</option>
                <option value="1:00 PM - 2:00 PM">1:00 PM - 2:00 PM (Lunch Break)</option>
                <option value="2:00 PM - 4:00 PM">2:00 PM - 4:00 PM</option>
                <option value="4:00 PM - 6:00 PM">4:00 PM - 6:00 PM (Fatique)</option>
            </select>
        </div>

        <!-- Activity Input (Dropdown + Dynamic Input) -->
        <div class="form-group">
            <label for="activity">Activity:</label>
            <select name="activity" id="activity" class="form-control">
                <option value="">Select Activity</option>
                @foreach ($activities as $activity)
                    <option value="{{ $activity->name }}">{{ $activity->name }}</option>
                @endforeach
            </select>
            <input type="text" name="new_activity" id="new_activity" class="form-control mt-2" placeholder="Enter new activity (if not in list)">
        </div>

        <!-- Venue Input (Dropdown + Dynamic Input) -->
        <div class="form-group">
            <label for="venue">Venue:</label>
            <select name="venue" id="venue" class="form-control">
                <option value="">Select Venue</option>
                @foreach ($venues as $venue)
                    <option value="{{ $venue->name }}">{{ $venue->name }}</option>
                @endforeach
            </select>
            <input type="text" name="new_venue" id="new_venue" class="form-control mt-2" placeholder="Enter new venue (if not in list)">
        </div>

        <!-- Instructor Input (Dropdown + Dynamic Input) -->
        <div class="form-group">
            <label for="instructor">Instructor:</label>
            <select name="instructor" id="instructor" class="form-control">
                <option value="">Select Instructor</option>
                @foreach ($instructors as $instructor)
                    <option value="{{ $instructor->name }}">{{ $instructor->name }}</option>
                @endforeach
            </select>
            <input type="text" name="new_instructor" id="new_instructor" class="form-control mt-2" placeholder="Enter new instructor (if not in list)">
        </div>
        <div class="mb-3"> 
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('timetable.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
