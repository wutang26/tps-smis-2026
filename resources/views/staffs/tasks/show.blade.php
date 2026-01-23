@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Mpango Kazi</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">View Mpango Kazi (Assigned Staff)</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Staff Assigned to: <strong>{{ $task->title }}</strong></h3>
    <a class="btn btn-primary" href="{{ route('tasks.index') }}">← Back</a>
  </div>

  <!-- Scrollable Region Cards -->
  <div class="mb-4 overflow-auto">
    <div class="d-flex flex-nowrap gap-2 pb-2">
        @foreach($regionMap as $id => $name)
        <a href="{{ route('tasks.staff', ['task' => $task->id, 'region_id' => $id]) }}" class="text-decoration-none">
            <div class="card border-{{ $selectedRegion == $id ? 'primary' : 'light' }} shadow-sm" style="min-width: 160px;">
            <div class="card-body py-2 px-3 text-center">
                <h6 class="mb-1" style="font-size: 0.95rem;">{{ $name }}</h6>
                <span class="badge bg-{{ $selectedRegion == $id ? 'success' : 'primary' }}">
                {{ $selectedRegion == $id ? 'Selected' : 'View' }}
                </span>
            </div>
            </div>
        </a>
        @endforeach
    </div>
  </div>


  <!-- Staff Table -->
  @forelse($grouped as $regionName => $staffGroup)
    <div class="card mb-3">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span>Region: {{ $regionName }}</span>

    <form method="GET" action="{{ route('tasks.staff.export', $task->id) }}" class="mb-0">
        <input type="hidden" name="region_id" value="{{ $selectedRegion }}">
        <button type="submit" class="btn btn-primary btn-sm">
        📥 Export to Excel
        </button>
    </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
          <thead>
            <tr>
              <th>Force Number</th>
              <th>Rank</th>
              <th>Full Name</th>
              <th>Designation</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($staffGroup as $member)
              <tr>
                <td>{{ $member->forceNumber }}</td>
                <td>{{ $member->rank }}</td>
                <td>{{ $member->firstName }} {{ $member->middleName }} {{ $member->lastName }}</td>
                <td>{{ $member->designation }}</td>
                <td>
                  <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'danger' }}">
                    {{ ucfirst($member->status) }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @empty
    @if($selectedRegion)
      <div class="alert alert-warning">No staff assigned in this region.</div>
    @endif
  @endforelse
</div>
@endsection
