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
                    <li class="breadcrumb-item"><a href="#">Hospital</a></li>
                    <li class="breadcrumb-item active"><a href="#">Report</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 ">
    <div class="btn-group" id="filterButtons" role="group" aria-label="Filter options">
        <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
        <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
        <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
    </div>

        <form class="d-flex gap-2 align-items-end" method="GET" action="{{ route('reports.hospital') }}">
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
        <form action="{{ route('reports.generateHospitalReport') }}" method="GET" target="_blank">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-sm btn-success"><i class="gap 2 bi bi-download"></i>
               Report</button>

        </form>
    </div>
    <!-- <div class="mb-4">
        <h3>Hospital Report for  {{$date}}</h3>
    </div> -->



    <div class="chart-container" style=" padding: 0 10% 0 10%">
        <canvas id="groupedBarChart"></canvas>
    </div>

    <h3>Most students attended the hospital.</h3><br>
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
            @foreach ($mostOccurredStudents as $patient)
                <tr class="txt-danger" style="color:red">
                    <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{++$i}}.</td>
                    <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{ $patient['student']->force_number ?? '' }}
                        {{ $patient['student']->first_name }} {{ $patient['student']->last_name }}</td>
                    <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{ $patient['student']->company->name }} -
                        {{ $patient['student']->platoon }}</td>
                    <td style="{{ $patient['count'] > 2 ? 'color:red' : '' }}">{{ $patient['count']}}</td>
                    <td><a href="{{route('patients.show', $patient['student']->id)}}" class="btn btn-sm btn-info">View</a></td>
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
                { label: 'Treated', data: dailyCounts.total, backgroundColor: '#4B70DD' },
                { label: 'E.D', data: dailyCounts.ED, backgroundColor: '#5C6BC0' },
                { label: 'L.D', data: dailyCounts.LD, backgroundColor: '#BA68C8' },
                { label: 'Admitted', data: dailyCounts.Adm, backgroundColor: '#EF5350' },
                { label: 'Treated Trends', data: dailyCounts.total, type: 'line', fill: false, borderColor: 'rgba(26, 35, 160, 0.7)', tension: 0.1 },
                { label: 'E.D Trends', data: dailyCounts.ED, type: 'line', fill: false, borderColor: 'rgba(58, 45, 133, 0.7)', tension: 0.1, hidden: true },
                { label: 'L.D Trend', data: dailyCounts.LD, type: 'line', fill: false, borderColor: 'rgba(105, 2, 131, 0.7)', tension: 0.1, hidden: true },
                { label: 'Admitted Trends', data: dailyCounts.Adm, type: 'line', fill: false, borderColor: 'rgba(192, 22, 22, 0.7)', tension: 0.1, hidden: true }
            ]
        };

        const weeklyData = {
            labels: weeklyCounts.labels,
            datasets: [
                { label: 'Treated', data: weeklyCounts.total, backgroundColor: '#4B70DD' },
                { label: 'E.D', data: weeklyCounts.ED, backgroundColor: '#5C6BC0' },
                { label: 'L.D', data: weeklyCounts.LD, backgroundColor: '#BA68C8' },
                { label: 'Admitted', data: weeklyCounts.Adm, backgroundColor: '#EF5350' },
                { label: 'Treated Trends', data: weeklyCounts.total, type: 'line', fill: false, borderColor: 'rgba(26, 35, 160, 0.7)', tension: 0.1 },
                { label: 'E.D Trends', data: weeklyCounts.ED, type: 'line', fill: false, borderColor: 'rgba(58, 45, 133, 0.7)', tension: 0.1, hidden: true },
                { label: 'L.D Trend', data: weeklyCounts.LD, type: 'line', fill: false, borderColor: 'rgba(105, 2, 131, 0.7)', tension: 0.1, hidden: true },
                { label: 'Admitted Trends', data: weeklyCounts.Adm, type: 'line', fill: false, borderColor: 'rgba(192, 22, 22, 0.7)', tension: 0.1, hidden: true }
            ]
        };

        const monthlyData = {
            labels: monthlyCounts.labels,
            datasets: [
                { label: 'Treated', data: monthlyCounts.total, backgroundColor: '#4B70DD' },
                { label: 'E.D', data: monthlyCounts.ED, backgroundColor: '#5C6BC0' },
                { label: 'L.D', data: monthlyCounts.LD, backgroundColor: '#BA68C8' },
                { label: 'Admitted', data: monthlyCounts.Adm, backgroundColor: '#EF5350' },
                { label: 'Treated Trends', data: monthlyCounts.total, type: 'line', fill: false, borderColor: 'rgba(26, 35, 160, 0.7)', tension: 0.1 },
                { label: 'E.D Trends', data: monthlyCounts.ED, type: 'line', fill: false, borderColor: 'rgba(58, 45, 133, 0.7)', tension: 0.1, hidden: true },
                { label: 'L.D Trend', data: monthlyCounts.LD, type: 'line', fill: false, borderColor: 'rgba(105, 2, 131, 0.7)', tension: 0.1, hidden: true },
                { label: 'Admitted Trends', data: monthlyCounts.Adm, type: 'line', fill: false, borderColor: 'rgba(192, 22, 22, 0.7)', tension: 0.1, hidden: true }
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
                            callback: function (value) {
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