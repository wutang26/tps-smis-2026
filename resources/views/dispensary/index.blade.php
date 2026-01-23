@extends('layouts.main')

@section('content')
<div class="col-sm-12">

    <!-- Filter Form -->
     <div class="d-flex justify-content-end gap-2">    
    <form method="GET" action="{{ route('dispensary.page') }}" class="d-flex col-sm-4 justify-content-end mb-3" >
        <select name="company_id" class="form-select me-2">
            <option value="">Select Company</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>
    <!-- Statistics Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Statistics</h5>
        </div>
        <div class="card-header">
            <h5 class="card-title">Try this</h5>
        </div>
        <div class="card-body">
<div class="row">
    <div class="col-4 col-md-4">
        <h6>Daily </h6>
        <p>{{ $dailyCount }} </p>
        <a href="{{ route('hospital.viewDetails', ['timeframe' => 'daily', 'company_id' => request('company_id'), 'platoon' => request('platoon')]) }}"
            class="btn btn-info btn-sm">
            View  
        </a>
    </div>
    <div class="col-4 col-md-4">
        <h6>Weekly </h6>
        <p>{{ $weeklyCount }} </p>
        <a href="{{ route('hospital.viewDetails', ['timeframe' => 'weekly', 'company_id' => request('company_id'), 'platoon' => request('platoon')]) }}"
            class="btn btn-info btn-sm">
            View  
        </a>
    </div>
    <div class="col-4 col-md-4">
        <h6>Monthly </h6>
        <p>{{ $monthlyCount }} </p>
        <a href="{{ route('hospital.viewDetails', ['timeframe' => 'monthly', 'company_id' => request('company_id'), 'platoon' => request('platoon')]) }}"
            class="btn btn-info btn-sm">
            View  
        </a>
    </div>
</div>

        </div>
    </div>


    
    <!-- Pie Chart Section -->
    <div class="card">
    <div class="card-header">
        <h5 class="card-title mb-3">
            {{ $date }}
              Distribution</h5>
            <form action="{{route('dispensary.page')}}" method="get" class="col-sm-4 d-flex gap-2">
                <label for="" class="form-label">Monthly</label>
                <div class="col-sm-6">
                    @php use Carbon\Carbon; @endphp
            <select class="form-control" name="date" id="">
                @foreach ($months as $month)
                    <option 
                        value="{{ Carbon::parse($month)->format('Y-m-d') }}" 
                        @if (Carbon::parse($date)->format('F Y') == Carbon::parse($month)->format('F Y')) selected @endif>
                        {{ Carbon::parse($month)->format('F Y') }}
                    </option>
                @endforeach                   
            </select>

                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
    </div>
    <div class="card-body d-flex justify-content-center">
        <canvas id="patientChart" style="max-width: 300px; max-height: 300px;"></canvas>
    </div>
</div>
<!-- Include Chart.js -->
<script src="/tps-smis/resources/assets/js/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('patientChart').getContext('2d');

        var patientData = {!! json_encode($patientDistribution) !!};
        var isCompanySelected = {!! json_encode($isCompanySelected ?? false) !!}; // Ensure it's always defined

        var labels = Object.keys(patientData);
        var data = Object.values(patientData);
        // Colors for companies
        var companyColors = {
            "HQ": "green",
            "A": "red",
            "B": "white",
            "C": "yellow"
        };

        // Assign labels and colors
        var chartLabels = isCompanySelected 
            ? labels.map(platoon => `Platoon ${platoon}`)  // Show platoon stats if a company is selected
            : labels.map(companyId => { 
                return (companyId == 1 ? "HQ" : (companyId == 2 ? "A" : (companyId == 3 ? "B" : "C")));
              });

        var backgroundColors = isCompanySelected 
            ? ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#8E44AD', '#E74C3C', '#3498DB', '#F1C40F', '#2ECC71', '#D35400'] // Random colors for platoons
            : labels.map(companyId => companyColors[(companyId == 1 ? "HQ" : (companyId == 2 ? "A" : (companyId == 3 ? "B" : "C")))]);

        if (labels.length > 0) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Patients',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: '#000000', // Black border
                        borderWidth: 2 // Border thickness
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });
        } else {
            ctx.font = "16px Arial";
            ctx.fillText("No data available", 100, 100);
        }
    });
</script>

@endsection


