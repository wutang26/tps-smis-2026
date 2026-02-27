@extends('layouts.main')

@section('content')
    <style>
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 25px;
        }

        .form-card {
            background: #fff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 25px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #34495e;
            font-size: 14px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #dcdcdc;
            padding: 10px 12px;
            font-size: 14px;
            transition: 0.2s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
            outline: none;
        }

        .add-btn {
            margin-top: 5px;
            padding: 6px 12px;
            background: #3498db;
            color: #fff;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            border: none;
            transition: 0.2s ease;
        }

        .add-btn:hover {
            background: #2563eb;
        }

        .submit-btn {
            background: linear-gradient(135deg, #28a745, #218838);
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            transition: 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .alert-success {
            background: #d4edda;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .row-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:768px) {
            .form-grid,
            .row-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Center & reduce Sick Students width */
        .sick-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Center Sick Add button */
        .sick-wrapper .add-btn {
            display: block;
            margin: 10px auto 0 auto;
        }

        /* Center submit button */
        .submit-centered {
            width: 300px;
            display: block;
            margin: 0 auto;
        }
    </style>

    <a href="{{ route('daily-reports.index') }}" style="display:inline-block; margin-bottom:15px;">&larr; Back</a>

    <div class="container py-4">
        <h2 class="page-title">Edit Report Beat / Patrol</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('daily-reports.update', $report->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-grid">

                    <!-- Date -->
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="report_date" class="form-control" value="{{ $report->report_date }}" required>
                    </div>

                    <!-- Department -->
                    <div class="form-group">
                        <label>Department / Company</label>
                        <select name="company" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ (isset($report->company) && $report->company == $company->id) ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Repeated Students -->
                    <div class="form-group">
                        <label>Repeated Students</label>
                        <div id="repeated-cases-container">
                            @if(!empty($report->repeated_cases) && is_array($report->repeated_cases))
                                @foreach($report->repeated_cases as $case)
                                    <input type="text" name="repeated_cases[]" class="form-control mb-2" value="{{ $case }}">
                                @endforeach
                            @else
                                <input type="text" name="repeated_cases[]" class="form-control mb-2" placeholder="Enter student name or case">
                            @endif
                        </div>
                        <button type="button" class="add-btn" onclick="addInput('repeated-cases-container','repeated_cases[]')">+ Add More</button>
                    </div>

                    <!-- Assignments -->
                    <div class="form-group">
                        <label>Assignments & Last Date</label>
                        <div id="overloaded-cases-container">
                            @if(!empty($report->overloaded_cases) && is_array($report->overloaded_cases))
                                @foreach($report->overloaded_cases as $key => $assignment)
                                    <div class="assignment-row" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:6px;">
                                        <input type="text" name="overloaded_cases[]" class="form-control" value="{{ $assignment }}">
                                        <input type="date" name="last_assigned_date[]" class="form-control" value="{{ $report->last_assigned_date[$key] ?? '' }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="assignment-row" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:6px;">
                                    <input type="text" name="overloaded_cases[]" class="form-control" placeholder="Assignment">
                                    <input type="date" name="last_assigned_date[]" class="form-control">
                                </div>
                            @endif
                        </div>
                        <button type="button" class="add-btn" onclick="addAssignmentRow()">+ Add More</button>
                    </div>

                    <!-- Sick Students -->
                    <div class="form-group full-width sick-wrapper">
                        <label>Sick Students</label>
                        <div id="sick-students-container">
                            @if(!empty($report->sick_student_names) && is_array($report->sick_student_names))
                                @foreach($report->sick_student_names as $key => $name)
                                    <div class="sick-row" style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:10px; margin-bottom:6px;">
                                        <input type="text" name="sick_student_names[]" class="form-control" value="{{ $name }}">
                                        <select name="sick_student_platoon[]" class="form-control">
                                            <option value="">-- Select Platoon --</option>
                                            @foreach($platoons as $platoon)
                                                <option value="{{ $platoon->id }}" {{ (isset($report->sick_student_platoon[$key]) && $report->sick_student_platoon[$key] == $platoon->id) ? 'selected' : '' }}>
                                                    {{ $platoon->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="company[]" class="form-control">
                                            <option value="">-- Select Company --</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ (isset($report->company[$key]) && $report->company[$key] == $company->id) ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            @else
                                <div class="sick-row" style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:10px; margin-bottom:6px;">
                                    <input type="text" name="sick_student_names[]" class="form-control" placeholder="Enter Student Name">
                                    <select name="sick_student_platoon[]" class="form-control">
                                        <option value="">-- Select Platoon --</option>
                                        @foreach ($platoons as $platoon)
                                            <option value="{{ $platoon->id }}">{{ $platoon->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="company[]" class="form-control">
                                        <option value="">-- Select Company --</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="add-btn" onclick="addSickRow()">+ Add More</button>
                    </div>

                    <!-- Vitengo & Challenges -->
                    <div class="row-grid full-width">
                        <div class="form-group">
                            <label>Vitengo</label>
                            <div id="vitengo-container">
                                @if(!empty($report->vitengo_cases) && is_array($report->vitengo_cases))
                                    @foreach($report->vitengo_cases as $vitengo)
                                        <input type="text" name="vitengo_cases[]" class="form-control mb-2" value="{{ $vitengo }}">
                                    @endforeach
                                @else
                                    <input type="text" name="vitengo_cases[]" class="form-control mb-2" placeholder="Enter Kitengo / Student Name">
                                @endif
                            </div>
                            <button type="button" class="add-btn" onclick="addVitengoRow()">+ Add More</button>
                        </div>

                        <div class="form-group">
                            <label>Challenges Faced</label>
                            <textarea name="challenges" class="form-control" rows="3">{{ $report->challenges }}</textarea>
                        </div>
                    </div>

                    <!-- Suggestions & Emergency -->
                    <div class="row-grid full-width" style="margin-top:10px;">
                        <div class="form-group">
                            <label>Suggestions</label>
                            <textarea name="suggestions" class="form-control" rows="4">{{ $report->suggestions }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Emergency Cases</label>
                            <div id="emergency-container">
                                @if(!empty($report->emergency_cases) && is_array($report->emergency_cases))
                                    @foreach($report->emergency_cases as $case)
                                        <input type="text" name="emergency_cases[]" class="form-control mb-2" value="{{ $case }}">
                                    @endforeach
                                @else
                                    <input type="text" name="emergency_cases[]" class="form-control mb-2" placeholder="Enter Emergency Case">
                                @endif
                            </div>
                            <button type="button" class="add-btn" onclick="addEmergencyRow()">+ Add More</button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="form-group full-width" style="margin-top:15px;">
                        <button type="submit" class="submit-btn submit-centered">
                            Update Report
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Template for Sick Student Row -->
    <template id="sick-student-template">
        <div class="sick-row" style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:10px; margin-bottom:6px;">
            <input type="text" name="sick_student_names[]" class="form-control" placeholder="Enter Student Name">
            <select name="sick_student_platoon[]" class="form-control">
                <option value="">-- Select Platoon --</option>
                @foreach ($platoons as $platoon)
                    <option value="{{ $platoon->id }}">{{ $platoon->name }}</option>
                @endforeach
            </select>
            <select name="company[]" class="form-control">
                <option value="">-- Select Company --</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
    </template>

    <script>
        function addInput(containerId, name) {
            let container = document.getElementById(containerId);
            let input = document.createElement('input');
            input.type = 'text';
            input.name = name;
            input.className = 'form-control mb-2';
            input.placeholder = 'Enter value';
            container.appendChild(input);
        }

        function addAssignmentRow() {
            const container = document.getElementById('overloaded-cases-container');
            const row = document.createElement('div');
            row.className = 'assignment-row';
            row.style.display = 'grid';
            row.style.gridTemplateColumns = '1fr 1fr';
            row.style.gap = '10px';
            row.style.marginBottom = '6px';
            row.innerHTML = `
                <input type="text" name="overloaded_cases[]" class="form-control" placeholder="Assignment">
                <input type="date" name="last_assigned_date[]" class="form-control">
            `;
            container.appendChild(row);
        }

        function addSickRow() {
            const container = document.getElementById('sick-students-container');
            const template = document.getElementById('sick-student-template');
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
        }

        function addVitengoRow() {
            const container = document.getElementById('vitengo-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'vitengo_cases[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'Enter Kitengo / Student Name';
            container.appendChild(input);
        }

        function addEmergencyRow() {
            const container = document.getElementById('emergency-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'emergency_cases[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'Enter Emergency Case';
            container.appendChild(input);
        }
    </script>
@endsection