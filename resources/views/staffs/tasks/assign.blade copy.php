@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Mpango Kazi</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Assign Staff</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

<div class="row gx-4">
  <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5>Assign Staff to Task: <strong>{{ $task->title }}</strong></h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('tasks.assign.store', $task->id) }}">
          @csrf

          <div class="row mb-3">
            <div class="col-md-6">
              <label><strong>Start Time:</strong></label>
              <input type="datetime-local" name="start_time" class="form-control">
            </div>
            <div class="col-md-6">
              <label><strong>End Time:</strong></label>
              <input type="datetime-local" name="end_time" class="form-control">
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Name</th>
                  <th>Designation</th>
                  <th>Rank</th>
                  <th>Status</th>
                  <th>Region</th>
                  <th>District</th>
                </tr>
              </thead>
              <tbody>
                @foreach($staff as $member)
                <tr>
                  <td>
                    <input type="checkbox" name="staff_ids[]" value="{{ $member->id }}">
                  </td>
                  <td>{{ $member->name }}</td>
                  <td>{{ $member->designation }}</td>
                  <td>{{ $member->rank }}</td>
                  <td>
                    <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'danger' }}">
                      {{ ucfirst($member->status) }}
                    </span>
                  </td>
                  <td>
                    <input type="text" name="regions[{{ $member->id }}]" class="form-control" placeholder="Region">
                  </td>
                  <td>
                    <input type="text" name="districts[{{ $member->id }}]" class="form-control" placeholder="District">
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fa fa-paper-plane"></i> Assign Selected Staff
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
