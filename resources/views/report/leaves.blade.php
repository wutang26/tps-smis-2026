@extends('layouts.main')
@section('style')
<style>
/* Enhance mobile view without affecting large screens */
@media (max-width: 768px) {

    /* Adjust chart container for mobile */
    .chart-container {
        padding: 15px;
        overflow-x: auto;
        /* Prevent horizontal overflow */
        height: 400px;
        /* Set a fixed height for better visibility */
    }

    canvas {
        width: 100% !important;
        /* Ensure the canvas fills the container */
    }

    /* Resize images for announcements */
    .card-body img {
        width: 30px;
    }

    /* Center-align text and reduce spacing */
    h2,
    h3 {
        text-align: center;
    }

    /* Buttons stack vertically */
    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn-group .btn {
        margin-bottom: 10px;
    }
}
</style>
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a href="#">Leaves</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3">

    <!-- Left: Filter Group Buttons -->
    <div class="btn-group" id="filterButtons" role="group" aria-label="Filter options">
        <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
        <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
        <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
    </div>

    <!-- Center: Filter Form (inline) -->
    <form method="GET" action="{{ route('reports.leaves') }}" class="d-flex  gap-2 ">
        <!-- Start Date -->
        <div class="input-group">
            <span class="input-group-text">Start Date</span>
            <input type="date" class="form-control" name="start_date"
                   value="{{ request('start_date') }}"
                   max="{{ \Carbon\Carbon::yesterday()->toDateString() }}">
        </div>

        <!-- End Date -->
        <div class="input-group">
            <span class="input-group-text">End Date</span>
            <input type="date" class="form-control" name="end_date"
                   value="{{ request('end_date') }}"
                   max="{{ \Carbon\Carbon::today()->toDateString() }}">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <form action="{{ route('reports.generateLeavesReport') }}" method="get">
        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
        <!-- Right: Report Download Button -->
        <button type="submit" class="btn btn-sm btn-success">
            <i class="bi bi-download me-1"></i> Report
        </button>
    </form>

</div>


<div class="chart-container" style=" padding: 0 10% 0 10%">
    <canvas id="groupedBarChart"></canvas>
</div>


<h3>Most student leaves occurred</h3><br>
<table class="table table-responsive table-sm">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Names</th>
            <th>Platoon</th>
            <th>Counts</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        @endphp
        @foreach ($leaveRequests as $student)
        <tr class="txt-danger" style="color:red">
            <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{++$i}}.</td>
            <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ $student['student']->force_number?? '' }}
                {{ $student['student']->first_name }} {{ $student['student']->last_name }}</td>
            <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ $student['student']->company->name }} -
                {{ $student['student']->platoon }}</td>
            <td style="{{ $student['count'] > 2 ? 'color:red' : '' }}">{{ $student['count']}}</td>
            <td><a href="{{route('leave-requests.show', $student['student']->id)}}" class="btn btn-sm btn-info">View</a>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

<!-- Include Chart.js -->
      <script src="/tps-smis/resources/assets/js/chart.js"></script>

<!-- Chart Container -->


<script>
// Sample chart data
var dailyCounts = @json($dailyCounts);
var weeklyCounts = @json($weeklyCounts);
var monthlyCounts = @json($monthlyCounts);
const dailyData = {
    labels: dailyCounts.labels,
    datasets: [{
            label: 'Returned',
            data: dailyCounts.returned,
            backgroundColor: 'rgba(76, 175, 80, 0.6)'
        },
        {
            label: 'On Leave',
            data: dailyCounts.on_leave,
            backgroundColor: 'rgba(255, 193, 7, 0.5)'
        },
        {
            label: 'Late',
            data: dailyCounts.late,
            backgroundColor: 'rgba(244, 67, 54, 0.5)'
        },

        {
            label: 'Returned Trends',
            data: dailyCounts.returned,
            type: 'line',
            fill: false,
            borderColor: 'rgba(91, 187, 142, 0.63)',
            tension: 0
        },
        {
            label: 'On Leave Trends',
            data: dailyCounts.on_leave,
            type: 'line',
            fill: false,
            borderColor: 'rgba(91, 187, 142, 0.63)',
            tension: 0,
            hidden:true
        },
        {
            label: 'Late Trend',
            data: dailyCounts.late,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 131, 82, 0.7)',
            tension: 0,
            hidden:true
        },

    ]
};

const weeklyData = {
    labels: weeklyCounts.labels,
    datasets: [{
            label: 'Returned',
            data: weeklyCounts.returned,
            backgroundColor: 'rgba(76, 175, 80, 0.6)'
        },
        {
            label: 'On Leave',
            data: weeklyCounts.on_leave,
            backgroundColor: 'rgba(255, 193, 7, 0.5)'
        },
        {
            label: 'Late',
            data: weeklyCounts.late,
            backgroundColor: 'rgba(244, 67, 54, 0.5)'
        },
        {
            label: 'Returned Trends',
            data: weeklyCounts.returned,
            type: 'line',
            fill: false,
            borderColor: 'rgba(91, 187, 142, 0.63)',
            tension: 0
        },
        {
            label: 'On Leave Trends',
            data: weeklyCounts.on_leave,
            type: 'line',
            fill: false,
            borderColor: 'rgba(91, 187, 142, 0.63)',
            tension: 0,
            hidden:true
        },
        {
            label: 'Late Trend',
            data: weeklyCounts.late,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 131, 82, 0.7)',
            tension: 0,
            hidden:true
        },
    ]
};

const monthlyData = {
    labels: monthlyCounts.labels,
    datasets: [{
            label: 'Returned',
            data: monthlyCounts.returned,
            backgroundColor: 'rgba(76, 175, 80, 0.6)'
        },
        {
            label: 'On Leave',
            data: monthlyCounts.on_leave,
            backgroundColor: 'rgba(255, 193, 7, 0.5)'
        },
        {
            label: 'Late',
            data: monthlyCounts.late,
            backgroundColor: 'rgba(244, 67, 54, 0.5)'
        },
        {
            label: 'Returned Trends',
            data: monthlyCounts.returned,
            type: 'line',
            fill: false,
            borderColor: 'rgba(91, 187, 142, 0.63)',
            tension: 0
        },
        {
            label: 'On Leave Trends',
            data: monthlyCounts.on_leave,
            type: 'line',
            fill: false,
            borderColor: 'rgba(219, 169, 17, 0.5)',
            tension: 0,
            hidden:true 
        },
        {
            label: 'Late Trend',
            data: monthlyCounts.late,
            type: 'line',
            fill: false,
            borderColor: 'rgba(131, 2, 2, 0.56)',
            tension: 0,
            hidden:true
        },
    ]
};

// Initialize chart with default data
const ctx = document.getElementById('groupedBarChart').getContext('2d');
let chart = new Chart(ctx, {
    type: 'bar',
    data: dailyData,
    options: {
        responsive: true,
        layout: {
            padding: {
                top: 30,
                bottom: 10,
                left: 10,
                right: 100
            },
            height: 200
        },
        scales: {
            x: {
                stacked: false,
                title: {
                    display: true,
                    text: 'Dates'
                }
            },
            y: {
                stacked: false,
                title: {
                    display: true,
                    text: 'Counts'
                },
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return value.toFixed(0);
                    }
                },
                suggestedMax: 10
            }
        }
    }
});

// Update chart data and axis labels
function updateAxisLabels(dataType) {
    let currentData;
    switch (dataType) {
        case 'daily':
            currentData = dailyData;
            chart.options.scales.x.title.text = 'Dates';
            break;
        case 'weekly':
            currentData = weeklyData;
            chart.options.scales.x.title.text = 'Weeks';
            break;
        case 'monthly':
            currentData = monthlyData;
            chart.options.scales.x.title.text = 'Months';
            break;
        default:
            return;
    }

    // Update data
    chart.data = currentData;
    chart.options.scales.y.title.text = 'Counts';

    // Dynamically calculate suggestedMax
    const barData = currentData.datasets.slice(0, 4).flatMap(ds => ds.data);
    chart.options.scales.y.suggestedMax = Math.max(...barData) * 1.5;

    chart.update();
}

// View switching functions
function showDaily() {
    updateAxisLabels('daily');
}

function showWeekly() {
    updateAxisLabels('weekly');
}

function showMonthly() {
    updateAxisLabels('monthly');
}
// Automatically load daily view on page load
window.addEventListener('DOMContentLoaded', () => {
    showDaily();
});
</script>

@endsection