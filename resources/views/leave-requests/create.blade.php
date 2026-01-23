@extends('layouts.main')

@section('content')
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📋 Submit Leave Request</h4>
        </div>

        <div class="card-body p-4">
            {{-- Flash Messages --}}
            @foreach (['success', 'info'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'success' ? 'success' : 'warning' }} alert-dismissible fade show" role="alert">
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>🔴 {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $user = auth()->user();
                $student = $user->student ?? null;
                $isStudent = $student !== null;
            @endphp

            <form action="{{ route('leave-requests.store') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf

                @if($isStudent)
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="company_id" value="{{ $student->company_id }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">🏢 Company</label>
                        <input type="text" class="form-control" value="{{ $student->company->name ?? 'N/A' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">🪖 Platoon</label>
                        <input type="text" name="platoon" class="form-control" value="{{ $student->platoon }}" readonly>
                    </div>
                @else
                    {{-- Admin Selects Student --}}
                    <div class="mb-3">
                        <label for="student_id" class="form-label fw-semibold">👤 Select Student</label>
                        <select name="student_id" id="student_id" class="form-select select2" required>
                            <option value="">-- Select Student --</option>
                            @foreach(App\Models\Student::with('user', 'company')->get() as $student)
                                @if($student->user)
                                    <option value="{{ $student->id }}"
                                            data-platoon="{{ $student->platoon }}"
                                            data-company="{{ $student->company->name ?? 'N/A' }}"
                                            data-company-id="{{ $student->company_id }}">
                                        {{ $student->user->name }} ({{ $student->platoon }}) - {{ $student->company->name ?? 'N/A' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="company_id" id="company_id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">🏢 Company</label>
                        <input type="text" id="company_display" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">🪖 Platoon</label>
                        <input type="text" name="platoon" id="platoon_display" class="form-control" readonly>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="location" class="form-label fw-semibold">📌 Location</label>
                    <input type="text" name="location" id="location" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label fw-semibold">📞 Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label fw-semibold">📝 Reason</label>
                    <textarea name="reason" id="reason" rows="4" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="attachments" class="form-label fw-semibold">📎 Attachment (optional)</label>
                    <input type="file" name="attachments" id="attachments" class="form-control">
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-sm">
                        Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery and Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Enable search/filter
        $('#student_id').select2({
            placeholder: 'Search for a student...',
            allowClear: true,
            width: '100%'
        });

        // Auto-fill platoon and company
        $('#student_id').on('change', function () {
            const selected = $(this).find('option:selected');
            $('#platoon_display').val(selected.data('platoon') || '');
            $('#company_display').val(selected.data('company') || '');
            $('#company_id').val(selected.data('company-id') || '');
        });
    });
</script>
@endsection
