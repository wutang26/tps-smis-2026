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
        <div class="card mb-4 card-height-420">
            <div class="card-header">
                <h5 class="card-title">Grouped Bar Graph</h5>
            </div>
            <div class="card-body">

            <div class="graph-body auto-align-graph">
                
            
                <div id="sales"></div>
            </div>

            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4 card-height-420">
        <div class="card-header">
        <h5 class="card-title">Events per Coy</h5>
        </div>
        <div class="card-body">

        <div class="d-flex flex-column justify-content-between h-100">

            <!-- Transactions starts -->
            <div class="d-flex flex-column gap-3">
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-primary-subtle rounded-5 me-3">
                <i class="bi bi-twittr fs-3 text-primary"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah </p>
                <h3 class="m-0 lh-1 fw-semibold">159</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-info-subtle rounded-5 me-3">
                <i class="bi bi-xbx fs-3 text-info"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">36</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-danger-subtle rounded-5 me-3">
                <i class="bi bi-youtbe fs-3 text-danger"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">23</h3>
                </div>
            </div>
            </div>
            <!-- Transactions ends -->

            <a href="javascript:void(0)" class="btn btn-dark">View All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        </div>
    </div>
    </div>  
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4" >
    <div class="col-xxl-12">
    <div class="card" style="height: 150px !important">
        <div class="card-body">
        
        </div>
    </div>
    </div>
</div>
<!-- Row ends -->
 
<script>
    document.getElementById('sessionProgramme').addEventListener('change', function() {
        var selectedProgrammeId = this.value;
        var url = "{{ route('dashboard.content') }}?session_programme_id=" + selectedProgrammeId;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('dashboardContent').innerHTML = html;
            })
            .catch(error => console.error('Error loading data:', error));
    });
</script>
@endsection