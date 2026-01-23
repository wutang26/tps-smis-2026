@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a href="#">Attendances</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')

<div class="d-flex justify-content-end">
    <a href="{{route('reports.generateAttendanceReport')}}" title="Download report" class="btn btn-sm btn-success"><i class="gap 2 bi bi-download"></i>
        Report</a>
</div>
<div class="btn-group mb-3" role="group" aria-label="Filter options">
    <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
    <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
    <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
</div>
<div class="chart-container" style=" padding: 0 10% 0 10%">
    <canvas id="groupedBarChart"></canvas>
</div>


<h3>Most Absent Students</h3><br>
<table class="table table-responsive table-sm">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Names</th>
            <th>Platoon</th>
            <th>Counts</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        @endphp
        
        @foreach ($mostAbsentStudent as $absent)
        <tr class="txt-danger" style="color:red">
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{++$i}}.</td>
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['student']->force_number?? '' }} {{ $absent['student']->first_name }} {{ $absent['student']->last_name }}</td>
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['student']->company->name }} - {{ $absent['student']->platoon }}</td>
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['count']}}</td>
    </tr>
        @endforeach

    </tbody>
</table>



<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Container -->


<script>
// Sample chart data
var dailyCounts = @json($dailyCounts);
var weeklyCounts = @json($weeklyCounts);
var monthlyCounts = @json($monthlyCounts);
const dailyData = {
    labels: dailyCounts.labels,
    datasets: [
        { label: 'Absent', data: dailyCounts.absent, backgroundColor: '#1E4093' },
        { label: 'Kazini', data: dailyCounts.kazini, backgroundColor: 'orange'},
        { label: 'Absent Trends', data: dailyCounts.absent, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
        { label: 'Kazini Trends', data: dailyCounts.kazini, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1 },

    ]
};

const weeklyData = {
    labels: weeklyCounts.labels,
    datasets: [
        { label: 'Absent', data: weeklyCounts.absent, backgroundColor: '#1E4093' },
        { label: 'Kazini', data: weeklyCounts.kazini, backgroundColor: 'orange'},
        { label: 'Absent Trends', data: weeklyCounts.absent, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
        { label: 'Kazini Trends', data: weeklyCounts.kazini, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1 },
    ]
};

const monthlyData = {
    labels: monthlyCounts.labels,
    datasets: [
        { label: 'Absent', data: monthlyCounts.absent, backgroundColor: '#1E4093' },
        { label: 'Kazini', data: monthlyCounts.kazini, backgroundColor: 'orange'},
        { label: 'Absent Trends', data: monthlyCounts.absent, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
        { label: 'Kazini Trends', data: monthlyCounts.kazini, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1 },
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
            padding: { top: 30, bottom: 10, left: 10, right: 100 },
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