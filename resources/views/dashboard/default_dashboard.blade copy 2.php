@extends('layouts.main')

@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-xl-3 col-sm-6 col-12">
    <div class="card mb-4 bg-primary">
        <div class="card-body">
        <div class="m-0 text-white">
            <div class="fw-semibold mb-3">New Orders</div>
            <div class="position-relative">
            <h2 class="m-0">300</h2>
            <span class="badge bg-secondary-subtle text-body small mb-2">
                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i>8 new orders
            </span>
            <div class=""><span class="badge bg-danger me-1">+28%</span>Compared to
                last week</div>
            <i class="bi bi-box-seam display-3 opacity-25 position-absolute end-0 top-0 me-2"></i>
            </div>
            <div class="mt-3">
            <div class="small">Last updated on <span class="opacity-50">Jan 10, 6:30:59 AM</span></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
    <div class="card mb-4 bg-primary">
        <div class="card-body">
        <div class="m-0 text-white">
            <div class="fw-semibold mb-3">Orders Delivered</div>
            <div class="position-relative">
            <h2 class="m-0">600</h2>
            <span class="badge bg-secondary-subtle text-body small mb-2">
                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i>4 orders delivered
            </span>
            <div class=""><span class="badge bg-danger me-1">+20%</span>Compared to
                last week</div>
            <i class="bi bi-bar-chart display-3 opacity-25 position-absolute end-0 top-0 me-2"></i>
            </div>
            <div class="mt-3">
            <div class="small">Last updated on <span class="opacity-50">Jan 12, 8:20:30 AM</span></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
    <div class="card mb-4 bg-primary">
        <div class="card-body">
        <div class="m-0 text-white">
            <div class="fw-semibold mb-3">Orders Pending</div>
            <div class="position-relative">
            <h2 class="m-0">900</h2>
            <span class="badge bg-secondary-subtle text-body small mb-2">
                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i>5 pending orders
            </span>
            <div class=""><span class="badge bg-danger me-1">+36%</span>Compared to
                last week</div>
            <i class="bi bi-sticky display-3 opacity-25 position-absolute end-0 top-0 me-2"></i>
            </div>
            <div class="mt-3">
            <div class="small">Last updated on <span class="opacity-50">Jan 14, 9:45:35 AM</span></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
    <div class="card mb-4 bg-primary">
        <div class="card-body">
        <div class="m-0 text-white">
            <div class="fw-semibold mb-3">Orders Cancelled</div>
            <div class="position-relative">
            <h2 class="m-0">200</h2>
            <span class="badge bg-light-subtle text-danger small mb-2">
                <i class="bi bi-exclamation-circle-fill me-1 text-warning"></i>7 orders cancelled
            </span>
            <div class=""><span class="badge bg-danger me-1">+39%</span>Compared to last week</div>
            <i class="bi bi-wallet2 display-3 opacity-25 position-absolute end-0 top-0 me-2"></i>
            </div>
            <div class="mt-3">
            <div class="small">Last updated on <span class="opacity-50">Jan 18, 9:29:59 AM</span></div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12">
    <!-- Card starts -->
    <div class="card mb-4">
        <div class="card-header">
        <h5 class="card-title">Orders Summary</h5>
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


















<h1>Session Programmes Dashboard</h1>
    
    <div>
        <h2>Session Programme Summaries</h2>
        <ul>
            @foreach ($programmes as $programme)
                <li>
                    <strong>Name:</strong> {{ $programme->session_programme_name }}<br>
                    <strong>Description:</strong> {{ $programme->description }}<br>
                    <strong>Year:</strong> {{ $programme->year }}<br>
                    <strong>Start Date:</strong> {{ $programme->startDate }}<br>
                    <strong>End Date:</strong> {{ $programme->endDate }}<br>
                    <strong>Is Current:</strong> {{ $programme->is_current ? 'Yes' : 'No' }}<br>
                    <hr>
                </li>
            @endforeach
        </ul>
    </div>

    <div>
        <h2>Comparison Graph</h2>
        <canvas id="programmeComparisonChart"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script>
        var ctx = document.getElementById('programmeComparisonChart').getContext('2d');
        var programmeNames = @json($programmes->pluck('programme_name'));
        var programmeYears = @json($programmes->pluck('year'));
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: programmeNames,
                datasets: [{
                    label: 'Programmes by Year',
                    data: programmeYears,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
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