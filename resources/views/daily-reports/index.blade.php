{{-- @extends('layouts.main') ---use this to manage roles and permissions --}}
@extends('layouts.main')

@section('content')
    <style>
        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .table-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table thead {
            background: #2c3e50;
            color: #fff;
        }

        .report-table th,
        .report-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            text-align: left;
        }

        .report-table tbody tr:hover {
            background: #f8f9fa;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #3498db;
            color: #fff;
        }

        .btn-warning {
            background: #f39c12;
            color: #fff;
        }

        .btn-danger {
            background: #e74c3c;
            color: #fff;
        }

        .btn-sm {
            font-size: 12px;
            padding: 5px 8px;
        }

        .alert-success {
            background: #d4edda;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .container h2 {
            font-size: 25px;
            font-weight: 800;
            color: #111827;
            font-family: 'Poppins', sans-serif;
            text-transform: capitalize;
            margin-bottom: 20px;
            "

        }
    </style>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                Feedback From Clerks and Staffs
            </h2>

            @hasanyrole('Admin|Super Administrator|clerk|staff')
                <a href="{{ route('daily-reports.create') }}" class="btn btn-primary">
                    + Create Report
                </a>
            @endhasanyrole
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-card">
            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Reporting Date</th>
                            <th>Reported By</th>
                            <th>Department or Company</th>
                            <th>Repeated On Site</th>
                            <th>Repeated Date and Site</th>
                            <th>Sick Students</th>
                            <th>Emergency</th>
                            <th>Challenges</th>
                            <th>Suggestions</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>
                                <td>{{ $report->user->name ?? 'N/A' }}</td>

                                {{-- Company --}}
                                <td>{{ $report->company->name ?? 'N/A' }}</td>

                                {{-- Repeated Cases --}}
                                {{-- Repeated Cases --}}
                                <td>
                                    @if (!empty($report->repeated_cases) && is_array($report->repeated_cases))
                                        <ul style="padding-left:15px; margin:0;">
                                            @foreach ($report->repeated_cases as $case)
                                                <li>{{ $case }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>


                                {{-- Assignments --}}
                                {{-- Overloaded Cases --}}
                                <td>
                                    @if (!empty($report->overloaded_cases) && is_array($report->overloaded_cases))
                                        <ul style="padding-left:15px; margin:0;">
                                            @foreach ($report->overloaded_cases as $key => $assignment)
                                                <li>
                                                    {{ $assignment }}
                                                    @if (
                                                        !empty($report->last_assigned_date) &&
                                                            is_array($report->last_assigned_date) &&
                                                            isset($report->last_assigned_date[$key]))
                                                        ({{ \Carbon\Carbon::parse($report->last_assigned_date[$key])->format('d M Y') }})
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>

                                {{-- Sick Students --}}
                                <td>
                                    @if (!empty($report->sick_student_names) && is_array($report->sick_student_names))
                                        <ul style="padding-left:15px; margin:0;">
                                            @foreach ($report->sick_student_names as $key => $name)
                                                <li>
                                                    {{ $name }}
                                                    - {{ $report->sick_student_platoon[$key] ?? '' }}
                                                    - {{ $report->company[$key] ?? '' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>

                                {{-- Emergency Cases --}}
                                <td>
                                    @if (!empty($report->emergency_cases))
                                        <ul>
                                            @foreach ($report->emergency_cases as $case)
                                                <li>{{ $case }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $report->challenges }}</td>
                                <td>{{ $report->suggestions }}</td>

                                {{-- Actions --}}
                                <td>
                                    @hasanyrole('Admin|Super Administrator|clerk|staff')
                                        <a href="{{ route('daily-reports.edit', $report->id) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                    @endhasanyrole

                                    @hasanyrole('Admin|Super Administrator')
                                        <form action="{{ route('daily-reports.destroy', $report->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    @endhasanyrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
