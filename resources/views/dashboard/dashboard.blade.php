@extends('layouts.main')

@section('style')
    <style>
        /* ===== Chart Container ===== */
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            padding: 0;
            overflow-x: auto;
            box-sizing: border-box;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
            /* default height */
            display: block;
        }

        /* ===== Announcement Images ===== */
        .card-body img {
            width: 30px;
            height: auto;
        }

        /* ===== Headings ===== */
        h2,
        h3 {
            text-align: center;
            margin: 10px 0;
        }

        /* ===== Button Group ===== */
        .btn-group {
            display: flex;
            gap: 10px;
        }

        /* ===== Responsive adjustments for mobile screens ===== */
        @media (max-width: 768px) {
            .chart-container {
                width: 100vw;
                margin: 0 -15px;
            }

            canvas {
                height: 420px !important;
            }

            .btn-group {
                flex-direction: column;
                gap: 5px;
            }

            .btn-group .btn {
                width: 100%;
                padding: 8px 0;
            }

            h2,
            h3 {
                font-size: 1.2em;
            }
        }

        @media (max-width: 480px) {
            canvas {
                height: 450px !important;
            }

            h2,
            h3 {
                font-size: 1em;
            }

            .btn-group .btn {
                font-size: 12px;
                padding: 6px 0;
            }
        }

        .btn-group {
            white-space: nowrap;
            /* prevent wrapping */
            overflow-x: auto;
            /* allow scrolling if needed */
            -webkit-overflow-scrolling: touch;
            /* smooth scroll on iOS */
            flex-wrap: nowrap !important;
            /* override Bootstrap wrap */
        }

        .btn-group .btn {
            flex: 0 0 auto;
            /* keep buttons natural width */
        }
    </style>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
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
    <!-- ===== Dashboard Content Row ===== -->
    <div class="row gx-4" id="dashboardContent">
        @include('dashboard.partials.dashboard_content', compact('denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage'))
    </div>

    <!-- ===== Chart & Announcements Row ===== -->
    <div class="row gx-4">
        <!-- Attendance Chart -->
        <div class="col-xxl-9 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="container">
                        <h2 style="color: #002366;">Trends and Analysis of Student Attendance Patterns</h2>
                        <div class="d-flex justify-content-start gap-2 mb-3 w-100">
                            <button type="button" class="btn btn-primary btn-sm flex-sm-fill flex-md-grow-0"
                                onclick="showDaily()">Daily</button>
                            <button type="button" class="btn btn-secondary btn-sm flex-sm-fill flex-md-grow-0"
                                onclick="showWeekly()">Weekly</button>
                            <button type="button" class="btn btn-success btn-sm flex-sm-fill flex-md-grow-0"
                                onclick="showMonthly()">Monthly</button>
                        </div>

                        <div class="chart-container"
                            style="margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
                            <canvas id="groupedBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="col-xxl-3 col-sm-12 col-12">
            <div class="card mb-4 card-height-420">
                <div class="card-header">
                    <h2 class="card-title">Recent Announcements</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <div class="d-flex flex-column gap-3">
                            @foreach ($recentAnnouncements as $announcement)
                                <div class="pb-3 border-bottom w-100">
                                    <div class="d-flex me-3">
                                        <img style="width: 50px;" src="{{ asset('resources/assets/images/new_blinking.gif') }}"
                                            alt="new gif">
                                        <h3 class="m-0 lh-1 fw-semibold">{{ $announcement->title }}</h3>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <p class="m-0 lh-1 fw-semibold">{{ $announcement->message }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{url('/announcements')}}" class="btn btn-dark mt-3">View All <i
                                class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Chart.js ===== -->
    <script src="assets/js/chart.js"></script>
    <script>
        var data = @json($graphData);
        const daily = data.dailyData;
        const weekly = data.weeklyData;
        const monthly = data.monthlyData;

        function getSuggestedMax(dataset) {
            return Math.max(...dataset.absents, ...dataset.sick, ...dataset.leaves, ...dataset.lockUps) * 1.2;
        }

        function createChartData(dataset) {
            return {
                labels: dataset.labels,
                datasets: [
                    { label: 'Absents', data: dataset.absents, backgroundColor: '#1E4093' },
                    { label: 'Sick', data: dataset.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)' },
                    { label: 'Leaves', data: dataset.leaves, backgroundColor: 'rgba(12, 165, 106, 0.7)' },
                    { label: 'Locked up', data: dataset.lockUps, backgroundColor: 'orange' },
                    { label: 'Absents Trends', data: dataset.absents, type: 'line', fill: false, borderColor: 'rgba(2, 11, 131, 0.7)', tension: 0.1 },
                    { label: 'Sick Trends', data: dataset.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
                    { label: 'Leaves Trend', data: dataset.leaves, type: 'line', fill: false, borderColor: 'rgba(2, 131, 82, 0.7)', tension: 0.1, hidden: true },
                    { label: 'Lock Up Trends', data: dataset.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
                ]
            };
        }

        const ctx = document.getElementById('groupedBarChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: createChartData(daily),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: 0 },
                scales: {
                    x: { stacked: false, title: { display: true, text: 'Dates' }, ticks: { maxRotation: 45, minRotation: 30, autoSkip: true, font: { size: window.innerWidth < 500 ? 10 : 12 } } },
                    y: { stacked: false, title: { display: true, text: 'Counts' }, ticks: { beginAtZero: true, stepSize: 1, callback: v => v.toFixed(0), font: { size: window.innerWidth < 500 ? 10 : 12 } }, suggestedMax: getSuggestedMax(daily) }
                },
                plugins: {
                    legend: { labels: { font: { size: window.innerWidth < 500 ? 10 : 12 } } },
                    tooltip: { bodyFont: { size: window.innerWidth < 500 ? 10 : 12 } }
                },
                barPercentage: 0.6,
                categoryPercentage: 0.7
            }
        });

        function updateChart(dataset, xLabel) {
            chart.data = createChartData(dataset);
            chart.options.scales.x.title.text = xLabel;
            chart.options.scales.y.suggestedMax = getSuggestedMax(dataset);
            chart.update();
        }

        function showDaily() { updateChart(daily, 'Dates'); }
        function showWeekly() { updateChart(weekly, 'Weeks'); }
        function showMonthly() { updateChart(monthly, 'Months'); }

        window.addEventListener('resize', () => {
            chart.options.scales.x.ticks.font.size = window.innerWidth < 500 ? 10 : 12;
            chart.options.scales.y.ticks.font.size = window.innerWidth < 500 ? 10 : 12;
            chart.options.plugins.legend.labels.font.size = window.innerWidth < 500 ? 10 : 12;
            chart.options.plugins.tooltip.bodyFont.size = window.innerWidth < 500 ? 10 : 12;
            chart.update();
        });
    </script>
@endsection