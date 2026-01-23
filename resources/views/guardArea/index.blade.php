@extends('layouts.main')

@section('scrumb')
  <!-- Scrumb starts -->
  <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Guard</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Guard Areas</a></li>
      </ol>
    </nav>
    </div>
  </nav>
  <!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')

  <!-- Row starts -->
  <div class="row gx-4">
    <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">

      </div>
      <div class="pull-right">
      <a class="btn btn-success mb-2" href="{{ route('guard-areas.create') }}"
        style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Add New Guard Area</a>
      </div>

      <h1>Guard Areas</h1>
      <div class="table-outer">
      <div class="table-responsive">
        <table class="table table-striped truncate m-0">
        <thead>
          <tr>
          <th>S/No</th>
          <th>Name</th>
          <th>Company </th>
          <th>Campus </th>
          <th>Added By</th>
          <th>Guards</th>
          <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @php
        $i = 0;
    @endphp
          @foreach ($guardAreas as $guardArea)
        <tr>
        <td>{{ ++$i }}.</td>
        <td>{{ $guardArea->name }}</td>
        <td>{{ $guardArea->company->name }}</td>
        <td>{{ $guardArea->company->campus->campusName }}</td>
        <td>{{ $guardArea->addedBy->name }}</td>
        <td>{{ $guardArea->number_of_guards }}</td>
        <td>
        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
        data-bs-target="#statusModal{{ $guardArea->id ?? ''}}">
        More
        </button>
        <form id="deleteForm{{ $guardArea->id }}" action="{{ route('guard-areas.destroy', $guardArea) }}"
        method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" onclick="confirmDelete('deleteForm{{ $guardArea->id }}', 'Guard Area')" type="button">Delete</button>
        </form>
        @include('layouts.sweet_alerts.confirm_delete')
        </td>
        <div class="modal fade" id="statusModal{{  $guardArea->id ?? '' }}" tabindex="-1"
        aria-labelledby="statusModalLabel{{  $guardArea->id ?? '' }}" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="statusModalLabel{{  $timesheet->id ?? ''}}">
          {{ $guardArea->name }} Exceptions
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="mb-2">
          <span>Number of guards: {{ $guardArea->number_of_guards }}</span>
          </div>
          <div class="mb-4">
          <h3> Beat Exceptions </h3>
          </div>
          @if($guardArea->beat_exceptions && $guardArea->beat_exceptions->isNotEmpty())
        <ol>
        @foreach ($guardArea->beat_exceptions as $beat_exception)
      <li>{{ $beat_exception->name }}</li>
    @endforeach
        </ol>
      @else
      <div>
      <h4>No Beat Exceptions</h4>
      </div>
    @endif
          <div class="mb-4 mt-4">
          <h3> Beat Time Exceptions </h3>
          </div>
          @if($guardArea->beat_time_exceptions && $guardArea->beat_time_exceptions->isNotEmpty())
        <ol>
        @foreach ($guardArea->beat_time_exceptions as $beat_time_exception)
      <li>{{ $beat_time_exception->name }}</li>
    @endforeach
        </ol>
      @else
      <div class="mb-4">
      <h4>No Beat Time exceptions</h4>
      </div>
    @endif
          <div class="modal-footer">
          <div class="d-flex gap-2 justify-content-center">
          <a class="btn btn-sm btn-primary"
            href="{{ route('guard-areas.edit', $guardArea) }}">Edit</a>
          </div>
          </div>
          </div>
        </div>
        </div>
        </div>
        </tr>
      @endforeach
        </tbody>
        </table>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- Row ends -->
@endsection