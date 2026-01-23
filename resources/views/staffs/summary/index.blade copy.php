@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('staffs.index') }}">Staff</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Summary</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

<style>
  .status-card.active-card {
    border: 3px solid yellow !important;
  }
  .badge-status {
    padding: 0.4em 0.75em;
    border-radius: 1em;
    font-size: 0.9em;
  }
  .badge-leave { background-color: #ffc107; color: #000; }
  .badge-study { background-color: #17a2b8; color: #fff; }
  .badge-trip { background-color: #6610f2; color: #fff; }
  .badge-dismissed { background-color: #dc3545; color: #fff; }
  .badge-active { background-color: #28a745; color: #fff; }
</style>

<div class="card-header">
  <h5 class="card-title">Staff Summary</h5>
  <p class="card-text">This page provides the staff summary.</p>
</div>

<div class="card-body" style="margin-right: -25px;">
  <div class="row">
    @php
      $cardTypes = [
        ['key' => 'active', 'label' => 'Active', 'color' => 'primary'],
        ['key' => 'leave', 'label' => 'Leave', 'color' => 'success'],
        ['key' => 'study', 'label' => 'Study', 'color' => 'info'],
        ['key' => 'dismissed', 'label' => 'Dismissed', 'color' => 'danger'],
      ];
    @endphp

    @foreach ($cardTypes as $type)
    <div class="col-md-3 mb-3">
      <button class="card bg-{{ $type['color'] }} text-white w-100 status-card"
        data-status="{{ $type['key'] }}"
        onclick="showStaffs('{{ $type['key'] }}')">
        <div class="card-body text-center">
          <h5>{{ $type['label'] }}</h5>
          <p class="fs-4">{{ $stats[$type['key']]->count() ?? 0 }}</p>
        </div>
      </button>
    </div>
    @endforeach
  </div>
</div>

<!-- Filter Form -->
<div class="d-flex justify-content-center my-3">
  <form id="staffFilterForm" class="d-flex flex-nowrap gap-2 align-items-center col-12 col-md-8 col-lg-6">
    <div class="input-group">
      <span class="input-group-text">Name</span>
      <input type="text" class="form-control" name="staff_name" placeholder="Enter staff name">
    </div>
    <div class="input-group">
      <span class="input-group-text">Rank</span>
      <select class="form-control" name="rank" >
        <option value="">Select rank</option>
        <option value="PC">Police Constable (PC)</option>
        <option value="CPL">Corporal (CPL)</option>
        <option value="SGT">Sergeant (SGT)</option>
        <option value="S/SGT">Staff Sergeant (S/SGT)</option>
        <option value="SM">Sergeant Major (SM)</option>
        <option value="A/INSP">Assistant Inspector of Police (A/INSP)</option>
        <option value="INSP">Inspector of Police (INSP)</option>
        <option value="ASP">Assistant Superintendent of Police (ASP)</option>
        <option value="SP">Superintendent of Police (SP)</option>
        <option value="SSP">Senior Superintendent of Police (SSP)</option>
        <option value="ACP">Assistant Commissioner of Police (ACP)</option>
        <option value="SACP">Senior Assistant Commissioner of Police (SACP)</option>
        <!-- <option value="DCP">Deputy Commissioner of Police (DCP)</option>
        <option value="CP">Commissioner of Police (CP)</option>
        <option value="IGP">Inspector General of Police (IGP)</option> -->
      </select>
    </div>
    <input type="hidden" name="status" id="filterStatus" value="">
    <button type="submit" class="btn btn-primary">Filter</button>
  </form>
</div>

<!-- Loader -->
<div class="text-center my-3" id="loading" style="display: none;">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

<!-- Result Section -->
<div id="studentTableContainer" class="mt-4" style="display: none;">
  <h4 id="studentTableTitle" class="mb-3"></h4>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>SNo</th>
          <th>Force Number</th>
          <th>Name</th>
          <th>Designation</th>
          <th>Status</th>
          <th>View</th>
        </tr>
      </thead>
      <tbody id="studentTableBody"></tbody>
    </table>
  </div>

  <div class="d-flex justify-content-end mt-3" id="pagination-container"></div>
</div>
@endsection

@section('scripts')
<script>
  let currentFilterType = 'active';

  const labels = {
    total: "Total Staff",
    active: "Active Staff",
    leave: "Staff on Leave",
    study: "Staff on Study",
    dismissed: "Dismissed Staff"
  };

  const statusIcons = {
    active: `<span class="badge badge-active">✔ Active</span>`,
    leave: `<span class="badge badge-leave">🏖 On Leave</span>`,
    study: `<span class="badge badge-study">📘 Study</span>`,
    trip: `<span class="badge badge-trip">✈ Trip</span>`,
    dismissed: `<span class="badge badge-dismissed">❌ Dismissed</span>`
  };

  function showStaffs(type = 'total', page = 1) {
    currentFilterType = type;
    document.getElementById('filterStatus').value = type;

    // Highlight active card
    document.querySelectorAll('.status-card').forEach(card => {
      card.classList.remove('active-card');
    });
    document.querySelector(`.status-card[data-status="${type}"]`)?.classList.add('active-card');

    const form = document.getElementById('staffFilterForm');
    const formData = new FormData(form);
    formData.append('type', type);

    const params = new URLSearchParams(formData).toString();
    const url = `{{ url('staff/filter') }}?${params}&page=${page}`;

    // Show loader
    document.getElementById('loading').style.display = 'block';

    fetch(url)
      .then(response => response.json())
      .then(data => {
        const staffs = data.staffs.data;
        const container = document.getElementById('studentTableContainer');
        const title = document.getElementById('studentTableTitle');
        const body = document.getElementById('studentTableBody');

        title.textContent = labels[type] ?? "Staff";
        body.innerHTML = '';

        let startIndex = (data.staffs.current_page - 1) * data.staffs.per_page;

        if (staffs.length === 0) {
          body.innerHTML = `
            <tr>
              <td colspan="6" class="text-center text-danger">No staff found</td>
            </tr>
          `;
        } else {
          staffs.forEach((staff, index) => {
            const serialNumber = startIndex + index + 1;
            body.innerHTML += `
              <tr>
                <td>${serialNumber}</td>
                <td>${staff.forceNumber ?? '-'}</td>
                <td>${staff.rank ?? '-'} ${staff.firstName} ${staff.lastName}</td>
                <td>${staff.designation ?? '-'}</td>
                <td>${statusIcons[staff.status] ?? staff.status}</td>
                <td>
                  <a href="/tps-smis/staffs/${staff.id}" class="btn btn-sm btn-outline-primary">View Profile</a>
                </td>
              </tr>
            `;
          });
        }

        renderPagination(data, type);
        container.style.display = 'block';
        document.getElementById('loading').style.display = 'none';
      })
      .catch(err => {
        alert("Failed to load staff data: " + err);
        document.getElementById('loading').style.display = 'none';
      });
  }

  function renderPagination(data, type) {
    const paginationContainer = document.getElementById('pagination-container');
    paginationContainer.innerHTML = `
      <nav>
        <ul class="pagination justify-content-end flex-wrap mb-0">
          ${data.staffs.links.map(link => {
            const page = link.url ? new URL(link.url).searchParams.get('page') : null;
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
        if (page) showStaffs(currentFilterType, parseInt(page));
      });
    });
  }

  document.getElementById('staffFilterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showStaffs(currentFilterType);
  });

  document.addEventListener('DOMContentLoaded', () => {
    showStaffs(currentFilterType);
  });
</script>
@endsection
