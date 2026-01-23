@extends('layouts.main')

@section('content')
<!-- Row starts -->
 
<div class="row gx-4">
    @foreach ($programmes as $key => $prog)
    <div class="col-xl-3 col-sm-6 col-12">
    <div class="card mb-4 bg-primary">
        <div class="card-body">
        <div class="m-0 text-white">
            <div class="fw-semibold mb-3">{{ $prog->session_programme_name }}</div>
            <div class="position-relative">
            <span class="badge bg-secondary-subtle text-body small mb-2">
            <h5 class="m-0"><span class="opacity-20">Weekly absents</span>&nbsp;&nbsp;23</h5>
            </span> <br>
            <span class="badge bg-secondary-subtle text-body small mb-2">
                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i>4 Absents today
            </span>
            <div class=""><span class="badge bg-danger me-1">+20%</span>Compared to
                last week</div>
            <i class="bi bi-bar-chart display-3 opacity-25 position-absolute end-0 top-0 me-2"></i>
            </div>
            <div class="mt-3">
            <div class="small">Last updated on <span class="opacity-50">{{ now('Africa/Dar_es_Salaam') }}</span></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    @endforeach
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12">
    <!-- Card starts -->
    <div class="card mb-4">
        <div class="card-header">
        <h5 class="card-title">Attendance Summary</h5>
        </div>
        <div class="card-body">

        <div class="graph-body auto-align-graph">
            <div id="orders"></div>
        </div>

        </div>
    </div>
    <!-- Card ends -->

    </div>
</div>
<!-- Row ends -->





    <h1>Weekly Attendance Dashboard</h1>
    
    <div>
        <h2>Weekly Attendance</h2>
        <canvas id="weeklyAttendanceChart"></canvas>
    </div>

    <div>
        <h2>Weekly Comparison</h2>
        <table border="1">
            <tr>
                <th>Year</th>
                <th>Week</th>
                <th>Present Difference</th>
                <th>Absent Difference</th>
            </tr>
            @foreach ($weeklyComparison as $comparison)
                <tr>
                    <td>{{ $comparison['year'] }}</td>
                    <td>{{ $comparison['week'] }}</td>
                    <td>{{ $comparison['present_difference'] }}</td>
                    <td>{{ $comparison['absent_difference'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <script src="assets/js/cdns.cloudflare.js"></script>
    <script>
        var ctx = document.getElementById('weeklyAttendanceChart').getContext('2d');
        var weeklyData = @json($weeklyAttendance);
        var labels = [];
        var presentData = [];
        var absentData = [];

        weeklyData.forEach(item => {
            labels.push(`Week ${item.week}, ${item.year}`);
            presentData.push(item.total_present);
            absentData.push(item.total_absent);
        });

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Present',
                        data: presentData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Absent',
                        data: absentData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>

<!-- Row ends -->
@endsection