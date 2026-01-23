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

<div class="custom-tabs-container">

    <!-- Nav tabs start -->
    <ul class="nav nav-tabs" id="customTab2" role="tablist">
        @foreach ($companies as $company)
        <li class="nav-item" role="presentation">
            <a @if($companies[0]->id == $company->id) class="nav-link active" @else class="nav-link " @endif
                id="tab-one{{$company->id}}" data-bs-toggle="tab" href="#one{{$company->id}}" role="tab"
                aria-controls="oneA"
                aria-selected="true">{{$company->description}}</a>
        </li>

        @endforeach

    </ul>
    <div class="tab-content h-300">
        @foreach ($companies as $company)
        <div @if($companies[0]->id == $company->id) class="tab-pane fade show active" @else class="tab-pane fade show "
            @endif id="one{{$company->id}}" role="tabpanel">
            <div class="d-flex  justify-content-end">
                <a href="">
                    <button title="Download report" class="btn btn-sm btn-success"><i class="gap 2 bi bi-download"></i>
                        Report</button>
                </a>
            </div>
            @php

            $totalAbsent = 0;
            $totalMps = 0;
            $totalLeave = 0;
            $totalSick = 0;
            $total_present = 0;
            $total_absent = 0;
            $total_sentry = 0;
            $total_safari = 0;
            $total_off = 0;
            $total_lockUp = 0;
            $total_kazini = 0;
            $total_messy = 0;
            $total_sick = 0;
            $total_male = 0;
            $total_female = 0;
            $grand_total = 0;

            foreach ($company->platoons as $platoon){
            $total_present += $platoon->attendences->first()->present ?? 0;  
            $total_absent += $platoon->attendences->first()->absent ?? 0;
            $total_sentry += $platoon->attendences->first()->sentry ?? 0;
            $total_safari += $platoon->attendences->first()->safari ?? 0;
            $total_off += $platoon->attendences->first()->off ?? 0;
            $total_lockUp += $platoon->attendences->first()->lockUp ?? 0;
            $total_kazini += $platoon->attendences->first()->kazini ?? 0;
            $total_messy += $platoon->attendences->first()->mess ?? 0;
            $total_sick += $platoon->attendences->first()->sick ?? 0;
            $total_male += $platoon->attendences->first()->male ?? 0;
            $total_female += $platoon->attendences->first()->female ?? 0;
            $grand_total += $platoon->attendences->first()->total ?? 0;
            }
            @endphp
            <div class="table-responsive" style="margin: 0 18% 0 18%;">
                <table class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th>Platoon</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Sentry</th>
                            <th>Mess</th>
                            <th>Sick</th>
                            <th>Leave</th>
                            <th>Lock Up</th>
                            <th>Kazini</th>
                            <th>ME</th>
                            <th>KE</th>
                            <th>Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        @foreach ($company->platoons as $platoon)
                        <tr>
                            <td>{{$platoon->company->name}} - {{$platoon->name}}</td>
                            <td>{{$platoon->attendences[0]->present?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->absent?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->sentry?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->mess?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->sick?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->safari?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->lockUp?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->kazini?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->male?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->female?? '-'}}</td>
                            <td>{{$platoon->attendences[0]->total?? '-'}}</td>
                        </tr>
                        @endforeach
                        <tr style="font-weight:bold;">
                            <td><strong>JUMLA</strong></td>
                            <td>{{$total_present}}</td>
                            <td>{{$total_absent}}</td>
                            <td>{{$total_sentry}}</td>
                            <td>{{$total_messy}}</td>
                            <td>{{$total_sick}}</td>
                            <td>{{$total_safari}}</td>
                            <td>{{$total_lockUp}}</td>
                            <td>{{$total_kazini}}</td>
                            <td>{{$total_male}}</td>
                            <td>{{$total_female}}</td>
                            <td>{{$grand_total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="btn-group mb-3" role="group" aria-label="Filter options">
    <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
    <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
    <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
</div>
<div class="chart-container" style=" padding: 0 10% 0 10%">
    <canvas id="groupedBarChart"></canvas>
</div>


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