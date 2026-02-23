@extends('layouts.main')

@section('content')

<style>
/* ===== PAGE LAYOUT ===== */
.page-wrapper {
    max-width: 1100px;
    margin: auto;
}

.page-title {
    font-size: 1.9rem;
    font-weight: 600;
    color: #1e293b;
}

/* ===== CARD DESIGN ===== */
.form-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 35px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.06);
    transition: 0.3s ease;
}

.form-card:hover {
    transform: translateY(-4px);
}

/* ===== GRID SYSTEM ===== */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.full-width {
    grid-column: span 2;
}

/* ===== FORM ELEMENTS ===== */
.form-group label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    font-size: 0.95rem;
    transition: 0.3s;
    background: #f8fafc;
}

.form-control:focus {
    border-color: #2563eb;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    outline: none;
}

textarea.form-control {
    min-height: 110px;
    resize: vertical;
}

/* ===== BUTTONS ===== */
.btn-primary-modern {
    background: #2563eb;
    color: white;
    border: none;
    padding: 12px 22px;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-primary-modern:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.btn-outline-modern {
    border: 1px solid #cbd5e1;
    padding: 12px 22px;
    border-radius: 10px;
    background: white;
    font-weight: 500;
    color: #475569;
    transition: 0.3s ease;
}

.btn-outline-modern:hover {
    background: #f1f5f9;
}

/* ===== ALERT ===== */
.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 12px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* ===== RESPONSIVE ===== */
@media(max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: span 1;
    }
}
</style>

<div class="container py-5 page-wrapper">

    <h2 class="page-title mb-4">Edit Daily Patrol Report</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('daily-reports.update', $report->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-card">

            <div class="form-grid">

                <div class="form-group">
                    <label>Date</label>
                    <input type="date"
                           name="report_date"
                           class="form-control"
                           value="{{ old('report_date', $report->report_date) }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Department / Unit</label>
                    <input type="text"
                           name="department"
                           class="form-control"
                           value="{{ old('department', $report->department) }}">
                </div>

                <div class="form-group full-width">
                    <label>Repeated Patrol Cases</label>
                    <textarea name="repeated_cases"
                              class="form-control">{{ old('repeated_cases', $report->repeated_cases) }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Overloaded Trainees</label>
                    <textarea name="overloaded_cases"
                              class="form-control">{{ old('overloaded_cases', $report->overloaded_cases) }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Sick Trainees</label>
                    <textarea name="sick_cases"
                              class="form-control">{{ old('sick_cases', $report->sick_cases) }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Emergency Cases</label>
                    <textarea name="emergency_cases"
                              class="form-control">{{ old('emergency_cases', $report->emergency_cases) }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Challenges</label>
                    <textarea name="challenges"
                              class="form-control">{{ old('challenges', $report->challenges) }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Suggestions for Next Day</label>
                    <textarea name="suggestions"
                              class="form-control">{{ old('suggestions', $report->suggestions) }}</textarea>
                </div>

            </div>

            <div style="margin-top: 30px; display:flex; gap:15px;">
                <button type="submit" class="btn-primary-modern">
                    Update Report
                </button>

                <a href="{{ route('daily-reports.index') }}"
                   class="btn-outline-modern">
                    Cancel
                </a>
            </div>

        </div>
    </form>

</div>

@endsection