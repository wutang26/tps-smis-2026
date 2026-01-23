@extends('layouts.main')

@section('style')
</head>
  <!-- Choices.js CSS -->
  <link rel="stylesheet" href="/tps-smis/resources/assets/css/choices.min.css" />
  
  <style>
    /* Optional: Make Choices.js input full width */
    .choices__inner {
      min-height: 38px; /* align with Bootstrap input height */
    }
    .bscrumb {
      background-color: #f8f9fa;
      margin-right: 25px;
      border-bottom: 1px solid #dee2e6;
    }
    .card-header {
      /* background-color: #007bff; */
      /* color: white; */
    }
    .card-body {
      padding: 20px;
    }

    .filter-container{
      margin-top: 20px;
      margin-bottom: 20px;
    }
    
    .diagonal-badge {
      position: absolute;
      top: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.6); /* semi-transparent or use a theme color */
      color: #fff;
      font-weight: bold;
      font-size: 0.7rem;
      padding: 4px 36px;
      transform: rotate(45deg);
      transform-origin: top right;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 10;
    }

    .inline-badge {
      background-color: rgba(255, 255, 255, 0.2); /* subtle contrast */
      color: #fff;
      font-size: 0.65rem;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 4px;
      z-index: 1;
    }


  </style>

@endsection

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Intake History</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Intake Management Summary</a></li>
      </ol>
    </nav>
  </div>
  </nav>
@endsection

@section('content')
<div class="card mb-4" style="margin-right: 0px;">
  <div class="card-header">
    <h5 class="card-title">Intake Management Summary - {{ $active_session }}</h5>
    <p class="card-text">This page provides a summary of the intake history of students.</p>
  </div>

  <div class="card-body" style="margin-right: 0px;">
    <div class="row" style="margin-right: -10px;">
      @php
        $cardTypes = [
          ['key' => 'totalEnrolled', 'label' => 'Enrolled Students', 'color' => 'primary'],
          ['key' => 'currentStudents', 'label' => 'Current Students', 'color' => 'info'],
          ['key' => 'dismissed', 'label' => 'Dismissed Students', 'color' => 'danger'],
          ['key' => 'verified', 'label' => 'Verified Students', 'color' => 'success'],
        ];
      @endphp

      @foreach ($cardTypes as $type)
      <div class="col-md-3">
          <button class="card bg-{{ $type['color'] }} text-white w-100 filter-card position-relative" data-type="{{ $type['key'] }}">
            <div class="card-body text-center position-relative">
              <span class="inline-badge position-absolute top-0 end-0 me-2 mt-2 d-none">Active</span>
              <h5>{{ $type['label'] }}</h5>
              <p class="fs-4">{{ $stats[$type['key']]->count() ?? 0 }}</p>
            </div>
          </button>
      </div>
      @endforeach
    </div>

    <!-- Result Section -->
    
    <div class="row gx-4" style="margin-right: -25px;">
      <center>
      <div class="col-sm-10">
          <div class="form-group">
            <div class="container filter-container">
                <h5 class="card-title">Filter Students</h5>
                <p class="card-text">Use the filters below to narrow down the student intake history.</p>
                <form id="enrolled-filters" class="row g-3">
                  <div class="col-md-4" data-filter-group="company">
                    <label for="company_id" class="form-label">Company</label>
                    <select name="company_id" class="form-select select2">
                      <option disabled selected>-- Select Company --</option>
                      @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->description }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-4" data-filter-group="entry_region">
                    <label for="entry_region" class="form-label">Entry Region</label>
                    <select id="mySelect" class="form-select" name="entry_region[]" multiple>
                      @foreach ($regions as $region)
                        <option value="{{ $region }}">{{ strtoupper($region) }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-4" data-filter-group="education_level">
                    <label for="study_level" class="form-label">Educational Level</label>
                    <select name="education_level" class="form-select select2">
                      <option disabled selected>-- Choose Education Level --</option>
                      <option value="DARASA LA SABA" {{ old('education_level', 'default_value') == 'DARASA LA SABA' ? 'selected' : '' }}>Darasa la Saba</option>
                      <option value="KIDATO CHA NNE" {{ old('education_level', 'default_value') == 'KIDATO CHA NNE' ? 'selected' : '' }}>Form Four</option>
                      <option value="KIDATO CHA SITA" {{ old('education_level', 'default_value') == 'KIDATO CHA SITA' ? 'selected' : '' }}>Form Six</option>
                      <option value="ASTASHAHADA" {{ old('education_level', 'default_value') == 'ASTASHAHADA' ? 'selected' : '' }}>Certificate</option>
                      <option value="STASHAHADA" {{ old('education_level', 'default_value') == 'STASHAHADA' ? 'selected' : '' }}>Diploma</option>
                      <option value="SHAHADA" {{ old('education_level', 'default_value') == 'SHAHADA' ? 'selected' : '' }}>Bachelor Degree</option>
                      <option value="MASTERS" {{ old('education_level', 'default_value') == 'MASTERS' ? 'selected' : '' }}>Masters</option>
                      <option value="PhD" {{ old('education_level', 'default_value') == 'PhD' ? 'selected' : '' }}>PhD</option>
                  </select>
                  </div>

                  <div class="col-md-4" data-filter-group="age_range">
                    <label for="age_range" class="form-label">Age Range</label>
                    <select name="age_range" class="form-select select2">
                      <option disabled selected>-- Select Age Range --</option>
                      <option value="0-20">01–20</option>
                      <option value="21-25">21–25</option>
                      <option value="26-30">26–30</option>
                      <option value="31-45">31–45</option>
                      <option value="46-60">46–60</option>
                      <option value="60+">60+</option>
                    </select>
                  </div>
                  <div class="col-md-4" data-filter-group="dismissal_reason">
                    <label for="dismissal_reason" class="form-label">Dismissal Reason</label>
                    <select name="dismissal_reason" class="form-select select2">
                      <option disabled selected>-- Select Dismissal Reason --</option>
                      @foreach ($terminationReasons as $category => $reasons)
                        <optgroup label="{{ ucfirst($category) }}">
                          @foreach ($reasons as $reason)
                            <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                          @endforeach
                        </optgroup>
                      @endforeach
                    </select>
                  </div>
                </form>
            </div>
          </div>
      </div>
      </center>
      

      <div class="col-sm-12">
        <div class="card-body">
          <span>Trend and Analysis Pattern</span>
          <button id="resetFiltersBtn" class="btn btn-outline-secondary btn-sm">
            🔄 Reset Filters
          </button>
        </div>

      </div>
      <div class="col-sm-12">
        <div class="card-body" style="padding-right: -20px !important; ">
          
          
        <div id="studentTableContainer" class="mt-1" style="display: none;">
          <h4 id="studentTableTitle" class="mb-3"> </h4>
          <div class="table-responsive">
            <table class="table table-striped table-bordered text-center align-middle">
              <thead class="table-dark">
                <tr>
                  <th>SNo</th>
                  <th>Force No.</th>
                  <th>Name</th>
                  <th>Region</th>
                  <th>Education Level</th>
                  <th>Age</th>
                  <th>Status</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody id="studentTableBody"></tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-3" id="pagination-container">
              <!-- Pagination links will render here -->
          </div>
      </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')

<!-- Choices.js -->
<script src="/tps-smis/resources/assets/js/choices.min.js"></script>

<script>
  // Filter Mapping
  const filterMap = {
    totalEnrolled: ['entry_region', 'education_level', 'age_range'],
    currentStudents: ['company', 'entry_region', 'education_level', 'age_range'],
    dismissed: ['company', 'entry_region', 'education_level', 'dismissal_reason', 'age_range'],
    verified: ['company', 'education_level', 'age_range']
  };

  function toggleFilterFields(cardType) {
    const allFields = document.querySelectorAll('[data-filter-group]');
    const activeFields = filterMap[cardType] || [];

    allFields.forEach(field => {
      const group = field.getAttribute('data-filter-group');
      if (activeFields.includes(group)) {
        field.classList.remove('d-none');
      } else {
        field.classList.add('d-none');
      }
    });
  }

  // Trigger on Card Click
  document.querySelectorAll('.filter-card').forEach(card => {
    card.addEventListener('click', function () {
      const type = this.getAttribute('data-type');
      currentFilterType = type;

      // Highlight selected card
      document.querySelectorAll('.filter-card').forEach(c => {
        c.classList.remove('border', 'border-3', 'border-light', 'shadow');
      });
      this.classList.add('border', 'border-3', 'border-light', 'shadow');

      // Show relevant filters
      toggleFilterFields(type);

      // Fetch filtered data
      showStudents(type, 1);
    });
  });
  // Trigger card end


  //SHowing active card
  document.querySelectorAll('.filter-card').forEach(card => {
    card.addEventListener('click', function () {
      const type = this.getAttribute('data-type');
      currentFilterType = type;

      document.querySelectorAll('.filter-card').forEach(c => {
        c.classList.remove('border', 'border-3', 'border-light', 'shadow');
        c.querySelector('.inline-badge')?.classList.add('d-none');
      });

      this.classList.add('border', 'border-3', 'border-light', 'shadow');
      this.querySelector('.inline-badge')?.classList.remove('d-none');

      toggleFilterFields(type);
      showStudents(type, 1);
    });
  });

  ////End of showing active card

  document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('mySelect');
    if (element) {
      new Choices(element, {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: 'Select some regions',
        searchPlaceholderValue: 'Search regions',
        shouldSort: false,
      });
    }
      // Show default filters for totalEnrolled
  toggleFilterFields('totalEnrolled');

    // Initial load
    showStudents(currentFilterType);
  });

  let currentFilterType = 'totalEnrolled';

  const labels = {
    totalEnrolled: "Total Enrolled Students",
    currentStudents: "Current Students",
    dismissed: "Dismissed Students",
    verified: "Verified Students"
  };


  function showStudents(type, page = 1) {
  currentFilterType = type;

  const sessionId = document.getElementById('programmeSession')?.value || '';
  const form = document.getElementById('enrolled-filters');
  const formData = new FormData(form);
  const params = new URLSearchParams();

  for (const [key, value] of formData.entries()) {
    if (key.endsWith('[]')) {
      params.append(key, value);
    } else {
      params.append(key, value);
    }
  }

  const baseUrl = "{{ url('students/filter') }}";

  fetch(`${baseUrl}?type=${type}&page=${page}&session_id=${sessionId}&${params.toString()}`)
    .then(response => response.json())
    .then(data => {
      // Update table, chart, etc.
      
        const students = data.students.data;
        const title = document.getElementById('studentTableTitle');
        const body = document.getElementById('studentTableBody');
        const container = document.getElementById('studentTableContainer');

        title.textContent = labels[type] ?? "Students";
        body.innerHTML = '';
        container.style.display = 'block';

        let startIndex = (data.students.current_page - 1) * data.students.per_page;

        if (students.length === 0) {
          body.innerHTML = `
            <tr>
              <td colspan="6" class="text-center text-muted">No students found for selected filters.</td>
            </tr>`;
        } else {
          students.forEach((student, index) => {
            const serialNumber = startIndex + index + 1;

            let statusBadge = student.status === 'approved'
              ? `<span class="badge bg-success">✅ Verified</span>`
              : student.status === 'pending'
              ? `<span class="badge bg-warning text-dark">⏳ Pending</span>`
              : `<span class="badge bg-secondary">❔ Unknown</span>`;

            body.innerHTML += `
              <tr>
                <td>${serialNumber}</td>
                <td>${student.force_number ?? '-'}</td>
                <td>${student.first_name} ${student.middle_name} ${student.last_name}</td>
                <td>${student.entry_region}</td>
                <td>${student.education_level}</td>
                <td>${student.age}</td>
                <td>${statusBadge}</td>
                <td>
                  <a href="{{ url('students') }}/${student.id}" class="btn btn-sm btn-outline-primary">View Profile</a>
                </td>
              </tr>`;
          });
        }

        renderPagination(data.students, type);
        updateChart(data.summary);
        container.scrollIntoView({ behavior: 'smooth' });
    })
    .catch(() => alert("Could not load student data."));
}


  function renderPagination(paginator, type) {
    const paginationContainer = document.getElementById('pagination-container');
    if (!paginationContainer) return;

    paginationContainer.innerHTML = `
      <nav aria-label="Student pagination">
        <ul class="pagination justify-content-end flex-wrap mb-0">
          ${paginator.links.map(link => {
            const page = link.url ? new URL(link.url, window.location.origin).searchParams.get('page') : null;
            const label = link.label.replace(/&laquo;/g, '«').replace(/&raquo;/g, '»');

            return `
              <li class="page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${page}">${label}</a>
              </li>
            `;
          }).join('')}
        </ul>
      </nav>
    `;

    paginationContainer.querySelectorAll('.page-link').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) showStudents(type, parseInt(page));
      });
    });
  }

  function updateChart(summary) {
    if (typeof studentChart !== 'undefined') {
      studentChart.data.datasets[0].data = [
        summary.active,
        summary.dismissed,
        summary.verified
      ];
      studentChart.update();
    }
  }

  // Trigger filter changes
  document.querySelectorAll('#enrolled-filters select').forEach(select => {
    select.addEventListener('change', () => showStudents(currentFilterType, 1));
  });

  // Filter card click behavior
  document.querySelectorAll('.filter-card').forEach(card => {
    card.addEventListener('click', function () {
      document.querySelectorAll('.filter-card').forEach(c => {
        c.classList.remove('border', 'border-3', 'border-light', 'shadow');
      });
      this.classList.add('border', 'border-3', 'border-light', 'shadow');

      const type = this.getAttribute('data-type');
      showStudents(type, 1);
    });
  });

  // Reset filters
  document.getElementById('resetFiltersBtn')?.addEventListener('click', () => {
    const form = document.getElementById('enrolled-filters');
    form.reset();

    document.querySelectorAll('.select2').forEach(select => {
      $(select).val('').trigger('change');
    });

    showStudents(currentFilterType, 1);
  });
</script>


<script>
    function updateEntryRegions(sessionId) {
      const regions = regionCache[sessionId] || [];

      const select = document.getElementById('mySelect');
      select.innerHTML = '<option value="">All</option>';

      regions.forEach(region => {
        const option = document.createElement('option');
        option.value = region;
        option.textContent = region.toUpperCase();
        select.appendChild(option);
      });

      if (select.choicesInstance) {
        select.choicesInstance.destroy();
      }

      select.choicesInstance = new Choices(select, {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: 'Select entry regions',
        searchPlaceholderValue: 'Search regions',
        shouldSort: false,
      });
    }
</script>
@endsection
