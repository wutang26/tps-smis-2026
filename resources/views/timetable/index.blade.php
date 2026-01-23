@extends('layouts.main')

@section('content')

<div class="container mt-4">
    <center><h4>TPS MOSHI TIMETABLE</h4></center>

    <!-- Filter by Company -->
    <div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ route('timetable.index') }}" class="d-flex align-items-center">
    <label for="company" class="me-2 fw-bold">Select Company:</label>
    <select name="company" id="company" class="form-select" onchange="this.form.submit()">
        <option value="">-- Select Company --</option>
        @foreach($companies as $company)
            <option value="{{ $company }}" {{ $selectedCompany == $company ? 'selected' : '' }}>{{ $company }}</option>
        @endforeach
    </select>
</form>

<!-- Show Buttons Only If User is Admin -->
@if(auth()->check() && auth()->user()->role === 'admin')
            <div>
                <a href="{{ route('timetable.generate') }}" class="btn btn-info">
                    <i class="fas fa-sync"></i> Generate Timetable Automatically
                </a>
                <a href="{{ route('timetable.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Timetable Entry
                </a>
            </div>
        @endif

<div class="text-center mb-3 mt-4">
    <a href="{{ route('timetable.generate') }}" class="btn btn-info">
        <i class="fas fa-sync"></i> Generate Timetable Automatically
    </a>
</div>



        <!-- Timetable Entry Button -->
        <a href="{{ route('timetable.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Timetable Entry
        </a>
    </div>

    <!-- Timetable Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Time Slot</th>
                    @foreach ($daysOfWeek as $day)
                        <th class="text-center">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $time)
                    <tr>
                        <td><strong>{{ $time }}</strong></td>
                        @foreach ($daysOfWeek as $day)
                            @php
                                $entry = $timetable->where('day', $day)->where('time_slot', $time)->first();
                            @endphp
                            <td class="text-center">
                                @if ($entry)
                                    <strong>{{ $entry->activity }}</strong> <br>
                                    📍 <small>{{ $entry->venue }}</small> <br>
                                    👨‍🏫 <small>{{ $entry->instructor }}</small> <br>
                                    <a href="{{ route('timetable.edit', $entry->id) }}" class="btn btn-sm btn-warning mt-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('timetable.destroy', $entry->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mt-1" onclick="return confirm('Are you sure?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Download PDF Button -->
    <div class="text-center mt-3">
    <a href="{{ route('timetable.exportPDF', ['company' => $selectedCompany]) }}" class="btn btn-success">
        <i class="fas fa-download"></i> Download PDF
    </a>
</div>

</div>

@endsection
