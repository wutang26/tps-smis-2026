@extends('layouts.main')

@section('content')
    <!-- Row starts -->
    <div class="row gx-4" id="dashboardContent">
        @include('dashboard.partials.dashboard_content', compact('denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage'))
    </div>
    <!-- Row ends -->

    <!-- Row starts -->
    <div class="row gx-4">
        <div class="col-xxl-9 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="container">
                        <h2 style="color: #002366;">Trends and Analysis of Student Attendance Patterns</h2>
                        <div class="btn-group mb-3" role="group" aria-label="Filter options">
                            <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
                            <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
                            <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
                        </div>
                        <div class="chart-container"
                            style="margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
                            <canvas id="groupedBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-12 col-12">
            <div class="card mb-4 card-height-420">
                <div class="card-header">
                    <h2 class="card-title">Recent Announcements</h2>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <!-- Announcements start -->
                        <div class="d-flex flex-column gap-3">
                            @foreach ($recentAnnouncements as $announcement)
                                <div class="pb-3 border-bottom w-100">
                                    <div class=" d-flex me-3">
                                        <!-- <i class="bi bi-icon fs-3 text-{{ $announcement->type }}"></i> -->
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
                        <!-- Announcements end -->
                        <a href="{{url('/announcements')}}" class="btn btn-dark">View All <i
                                class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Row ends -->

    <!-- Chart.js -->
    <script src="/tps-smis/resources/assets/js/chart.js"></script>
    <script>
        var data = @json($graphData);
        daily = data.dailyData;
        weekly = data.weeklyData;
        monthly = data.monthlyData;

        const dailyData = {
            labels: daily.dates,
            datasets: [
                { label: 'Absents', data: daily.absents, backgroundColor: '#1E4093' },
                { label: 'Sick', data: daily.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)' },
                { label: 'Locked up', data: daily.lockUps, backgroundColor: 'orange' },
                { label: 'Absents Trends', data: daily.absents, type: 'line', fill: false, borderColor: 'rgba(49, 48, 48, 0.7)', tension: 0.1 },
                { label: 'Sick Trends', data: daily.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
                { label: 'Lock Up Trends', data: daily.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
            ]
        };

        const weeklyData = {
            labels: weekly.weeks,
            datasets: [
                { label: 'Absents', data: weekly.absents, backgroundColor: '#1E4093' },
                { label: 'Sick', data: weekly.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)' },
                { label: 'Locked up', data: weekly.lockUps, backgroundColor: 'orange' },
                { label: 'Absents Trends', data: weekly.absents, type: 'line', fill: false, borderColor: 'rgba(49, 48, 48, 0.7)', tension: 0.1 },
                { label: 'Sick Trends', data: weekly.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
                { label: 'Lock Up Trends', data: weekly.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
            ]
        };

        const monthlyData = {
            labels: monthly.months,
            datasets: [
                { label: 'Absents', data: monthly.absents, backgroundColor: '#1E4093' },
                { label: 'Sick', data: monthly.sick, backgroundColor: 'rgba(255, 0, 0, 0.7)' },
                { label: 'Locked up', data: monthly.lockUps, backgroundColor: 'orange' },
                { label: 'Absents Trends', data: monthly.absents, type: 'line', fill: false, borderColor: 'rgba(49, 48, 48, 0.7)', tension: 0.1 },
                { label: 'Sick Trends', data: monthly.sick, type: 'line', fill: false, borderColor: 'rgba(187, 91, 91, 0.7)', tension: 0.1, hidden: true },
                { label: 'Lock Up Trends', data: monthly.lockUps, type: 'line', fill: false, borderColor: 'rgba(152, 94, 18, 0.7)', tension: 0.1, hidden: true }
            ]
        };

        // Initialize Chart
        const ctx = document.getElementById('groupedBarChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'bar',
            data: dailyData, // Default to daily data
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: false,
                        title: {
                            display: true,
                            text: 'Dates'  // Default label for the X-axis
                        }
                    },
                    y: {
                        stacked: false,
                        title: {
                            display: true,
                            text: 'Counts'  // Default label for the Y-axis
                        },
                        ticks: {
                            beginAtZero: true,
                            padding: 20, // Add padding to the top of the bars
                            // Use callback to remove decimal points
                            callback: function (value) {
                                return value.toFixed(0);  // Rounds the value to the nearest integer
                            }
                        },
                        // Dynamically set the max value of the y-axis to be higher than the highest bar
                        suggestedMax: Math.max(...daily.absents, ...daily.sick, ...daily.lockUps) * 1.5, // 20% more than the highest value
                    }
                },
                layout: {
                    padding: {
                        top: 30, // Adds space at the top of the chart area
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                }
            }
        });


        function updateAxisLabels(dataType) {
            switch (dataType) {
                case 'daily':
                    chart.data = dailyData;
                    chart.options.scales.x.title.text = 'Dates';  // X-axis label for daily data
                    chart.options.scales.y.title.text = 'Counts';  // Y-axis label for daily data
                    break;
                case 'weekly':
                    chart.data = weeklyData;
                    chart.options.scales.x.title.text = 'Weeks';  // X-axis label for weekly data
                    chart.options.scales.y.title.text = 'Counts';  // Y-axis label for weekly data
                    break;
                case 'monthly':
                    chart.data = monthlyData;
                    chart.options.scales.x.title.text = 'Months';  // X-axis label for monthly data
                    chart.options.scales.y.title.text = 'Counts';  // Y-axis label for monthly data
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
    </script>
@endsection