    <!-- Staffs Present -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-1 border border-success rounded-circle me-3">
                        <div id="radial2"></div>
                    </div>
                    <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $staffsCount }}</h2>
                        <p class="m-0 opacity-50">Staffs Present</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <a class="text-primary" href="{{ route('staffs.index') }}">
                        <span>View All</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <div class="text-end">
                        <p class="mb-0 text-success">100%</p>
                        <span class="badge bg-success-subtle text-success small">Today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Students Present -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-1 border border-primary rounded-circle me-3">
                        <div id="radial1"></div>
                    </div>
                    <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $todayStudentReport['present'] }} / {{ $denttotalCount }}</h2>
                        <p class="m-0 opacity-50">Students Present</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    @can('attendance-list')
                    <a class="text-primary" href="/tps-smis/attendences/type/1">
                        <span>View All</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>                        
                    @endcan
                    <div></div>
                    <div class="text-end float-end">
                        <p class="mb-0 text-primary">{{ $todayStudentReport['presentPercent'] }}</p>
                        <span class="badge bg-primary-subtle text-primary small">Today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sick Students (ED) -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-1 border border-info rounded-circle me-3">
                        <div id="radial3"></div>
                    </div>
                    <div class="d-flex flex-column">
                        <!-- <h2 class="lh-1">{{ $todayStudentReport['sick'] }}</h2> -->
                        <h2 class="lh-1">{{ $patientsCount}}</h2>
                        <p class="m-0 opacity-50">Sick Students (ED,Admitted)</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    @can('hospital-list')
                    <a class="text-primary" href="{{ route('hospital.index') }}">
                        <span>View All</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>                        
                    @endcan
                    <div></div>
                    <div class="text-end">
                        <p class="mb-0 text-info">0.0%</p>
                        <span class="badge bg-info-subtle text-info small">Today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Guards & Patrols -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4 bg-primary">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    <div class="p-1 border border-white rounded-circle me-3">
                        <div id="radial4"></div>
                    </div>
                    <div class="d-flex flex-column">
                        <h2 class="m-0 lh-1">{{ $totalStudentsInBeats }}</h2>
                        <p class="m-0 opacity-50">Guards & Patrols</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <a class="text-white" href="{{ url('beats') }}">
                        <span>View All</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <div class="text-end">
                        <p class="mb-0 text-warning">{{ number_format($beatStudentPercentage, 1) }}%</p>
                        <span class="badge bg-danger text-white small">Today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
