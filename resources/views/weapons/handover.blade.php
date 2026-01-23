@extends('layouts.main')

@section('content')
@if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<div class="container">
    <h2>Handover Weapon: {{ $weapon->serial_number }}</h2>

    <form method="POST" action="{{ route('weapons.handover.store', $weapon->id) }}">
        @csrf

        {{-- Staff Selection --}}
        <div class="mb-3">
            <label for="staff_id" class="form-label fw-semibold">👤 Select Staff</label>
            <select name="staff_id" id="staff_id" class="form-select staff-select" required>
                <option value="">-- Search or Select Staff --</option>
                @foreach(App\Models\Staff::orderBy('firstName')->get() as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->rank }} - 
                        {{ $staff->firstName }}
                        @if(!empty($staff->middleName)) {{ $staff->middleName }} @endif
                         {{ $staff->lastName }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Handover Date --}}
        <div class="form-group mb-3">
            <label><strong>Handover Date</strong></label>
            <input type="datetime-local" name="handover_at" class="form-control" required>
        </div>

        {{-- Expected Return Date --}}
        <div class="form-group mb-3">
            <label><strong>Expected Return Date & Time</strong></label>
            <input type="datetime-local" name="expected_return_at" class="form-control" required>
        </div>

        {{-- Purpose --}}
        <div class="form-group mb-3">
            <label><strong>Purpose </strong></label>
            <textarea name="purpose" class="form-control" required></textarea>
        </div>

        {{-- Remarks --}}
        <!-- <div class="form-group mb-3">
            <label><strong>Additional Remarks</strong></label>
            <textarea name="remarks" class="form-control"></textarea>
        </div> -->

        <button class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

@section('scripts')
<!-- Include jQuery (if not already in your layout) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#staff_id').select2({
        placeholder: "Search staff...",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection
