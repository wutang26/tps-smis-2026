
@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
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
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

<div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">

    <!-- Filter Buttons -->
    <div class="btn-group" id="filterButtons" role="group" aria-label="Filter options">
        <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
        <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
        <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
    </div>

    <!-- Date Filter Form -->
    <form class="d-flex gap-2 align-items-end" method="GET" action="{{ route('reports.index') }}">
        <div class="input-group">
            <span class="input-group-text">Start Date</span>
            <input type="date" class="form-control" name="start_date"
                value="{{ request('start_date') }}"
                max="{{ \Carbon\Carbon::yesterday()->toDateString() }}">
        </div>
        <div class="input-group">
            <span class="input-group-text">End Date</span>
            <input type="date" class="form-control" name="end_date"
                value="{{ request('end_date') }}"
                max="{{ \Carbon\Carbon::today()->toDateString() }}">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Report Download -->

    <form class="d-flex gap-2 align-items-end" action="{{ route('reports.generateAttendanceReport') }}" method="get">
     @if(!request('end_date'))
    <form class="d-flex gap-2 align-items-end" action="{{ route('reports.generateAttendanceReport') }}" method="get" target="_blank">

        <select class="form-control" name="company_id" id="company_id" required style="min-width: 200px;">
            <option value="" selected disabled>Company</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->description }}</option>
            @endforeach
        </select>
        <button title="Download report" type="submit" class="btn btn-success" style="min-width: 120px;">
            <i class="bi bi-download me-1"></i> Report
        </button>
    </form>
    @endif
</div>

<div class="chart-container" style="padding: 0 10% 0 10%">
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
        @php $i = 0; @endphp
        @foreach ($mostAbsentStudent as $absent)
            <tr class="txt-danger" style="color:red">
                <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ ++$i }}.</td>
                <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['student']->force_number ?? '' }} {{ $absent['student']->first_name }} {{ $absent['student']->last_name }}</td>
                <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['student']->company->name ?? '' }} - {{ $absent['student']->platoon ?? '' }}</td>
                <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['count'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="/tps-smis/resources/assets/js/chart.js"></script>
<script>
    const data = @json($graphData);
    const daily = data.dailyData || { labels: [], absents: [], sick: [], leaves: [], lockUps: [] };
    const weekly = data.weeklyData || { labels: [], absents: [], sick: [], leaves: [], lockUps: [] };
    const monthly = data.monthlyData || { labels: [], absents: [], sick: [], leaves: [], lockUps: [] };
    const leaves_monthly_count = data.monthly || [];
    const isEmptyLabels = (data) => {
  return !data || !data.labels || data.labels.length === 0;
};
const onlyDailyAvailable = isEmptyLabels(weekly) && isEmptyLabels(monthly);
    
    // Hide Weekly and Monthly buttons if not available
    window.onload = function () {
        if (onlyDailyAvailable) {
            document.querySelectorAll('button.btn-secondary, button.btn-success').forEach(btn => {
                btn.style.display = 'none';
            });
        }
    };

    // Daily chart config (bars hidden if only daily data)
    const dailyData = {
        labels: daily.labels || [],
        datasets: [
            { label: 'Absents', data: daily.absents, backgroundColor: '#1E4093', hidden: onlyDailyAvailable },
            { label: 'Sick', data: daily.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)', hidden: onlyDailyAvailable },
            { label: 'Leaves', data: daily.leaves, backgroundColor: 'rgba(12, 165, 106, 0.7)', hidden: onlyDailyAvailable },
            { label: 'Locked up', data: daily.lockUps, backgroundColor: 'orange', hidden: onlyDailyAvailable },
            { label: 'Absents Trends', data: daily.absents, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
            { label: 'Sick Trends', data: daily.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
            { label: 'Leaves Trend', data: daily.leaves, type: 'line', fill: false, borderColor: 'rgba(2, 131, 82, 0.7)', tension: 0.1, hidden: true },
            { label: 'Lock Up Trends', data: daily.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
        ]
    };

    // Weekly chart config
    const weeklyData = weekly ? {
        labels: weekly.labels || [],
        datasets: [
            { label: 'Absents', data: weekly.absents, backgroundColor: '#1E4093' },
            { label: 'Sick', data: weekly.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)' },
            { label: 'Leaves', data: weekly.leaves, backgroundColor: 'rgba(12, 165, 106, 0.7)' },
            { label: 'Locked up', data: weekly.lockUps, backgroundColor: 'orange' },
            { label: 'Absents Trends', data: weekly.absents, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
            { label: 'Sick Trends', data: weekly.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
            { label: 'Leaves Trend', data: weekly.leaves, type: 'line', fill: false, borderColor: 'rgba(2, 131, 82, 0.7)', tension: 0.1, hidden: true },
            { label: 'Lock Up Trends', data: weekly.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
        ]
    } : null;

    // Monthly chart config
    const monthlyData = monthly ? {
        labels: monthly.labels || [],
        datasets: [
            { label: 'Absents', data: monthly.absents, backgroundColor: '#1E4093' },
            { label: 'Sick', data: monthly.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)' },
            { label: 'Leaves', data: monthly.leaves, backgroundColor: 'rgba(12, 165, 106, 0.7)' },
            { label: 'Locked up', data: monthly.lockUps, backgroundColor: 'orange' },
            { label: 'Absents Trends', data: monthly.absents, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
            { label: 'Sick Trends', data: monthly.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
            { label: 'Leaves Trend', data: monthly.leaves, type: 'line', fill: false, borderColor: 'rgba(2, 131, 82, 0.7)', tension: 0.1, hidden: true },
            { label: 'Lock Up Trends', data: monthly.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
        ]
    } : null;


    const ctx = document.getElementById('groupedBarChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'bar',
        data: dailyData,
        options: {
            responsive: true,
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
                    ticks: {
                        stepSize: 1,
                        beginAtZero: true,
                        callback: function (value) {
                            return value.toFixed(0);
                        }
                    },
                         suggestedMax: Math.max(...daily.absents, ...daily.sick, ...daily.lockUps, ...daily.leaves) * 1.2, // 20% more than the highest value
                }
            },
            layout: {
                padding: { top: 30, bottom: 10, left: 10, right: 10 }
            }
        }
    });

        function updateAxisLabels(dataType) {
            switch (dataType) {
                case 'daily':
                    chart.data = dailyData;
                    chart.options.scales.x.title.text = 'Dates';  // X-axis label for daily data
                    chart.options.scales.y.title.text = 'Counts';  // Y-axis label for daily data
                    chart.options.scales.y.suggestedMax = getSuggestedMax(daily);
                    break;
                case 'weekly':
                    chart.data = weeklyData;
                    chart.options.scales.x.title.text = 'Weeks';  // X-axis label for weekly data
                    chart.options.scales.y.title.text = 'Counts';  // Y-axis label for weekly data
                    chart.options.scales.y.suggestedMax = getSuggestedMax(weekly);
                    break;
                case 'monthly':
                    chart.data = monthlyData;
                    chart.options.scales.x.title.text = 'Months';  // X-axis label for monthly data
                    chart.options.scales.y.title.text = 'Counts';  // Y-axis label for monthly data
                    chart.options.scales.y.suggestedMax = getSuggestedMax(monthly);
                    break;
                default:
                    break;
            }

            chart.update();  // Update the chart to reflect the new labels and data
        }
        function showDaily() {
            chart.data = dailyData;
            updateAxisLabels('daily');
        }
        function showWeekly() {
            chart.data = weeklyData;
            updateAxisLabels('weekly');
        }
        function showMonthly() {
            chart.data = monthlyData;
            updateAxisLabels('monthly');
        }

        function getSuggestedMax(data) {
            return Math.max(...data.absents, ...data.sick, ...data.lockUps, ...data.leaves) * 1.2;
        }

    // Hide buttons if weekly or monthly data is empty
    document.addEventListener('DOMContentLoaded', function () {
        if (!weekly.labels?.length) {
            document.querySelector("button.btn-secondary").style.display = 'none';
        }
        if (!monthly.labels?.length) {
            document.querySelector("button.btn-success").style.display = 'none';
        }
    });
</script>
@endsection
