@extends('layouts.main')

@section('content')
<!-- Row starts -->

    
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