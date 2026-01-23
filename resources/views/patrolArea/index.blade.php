@extends('layouts.main')

@section('scrumb')
  <!-- Scrumb starts -->
  <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Patrol</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Patrol Areas</a></li>
      </ol>
    </nav>
    </div>
  </nav>
  <!-- Scrumb ends -->

@endsection
@section('content')
  @session('success')
    <div class="alert alert-success alert-dismissible " role="alert">
    {{ $value }}
    </div>
  @endsession
  <!-- Row starts -->
  <div class="row gx-4">
    <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">

      </div>
      <div class="pull-right">
      <a class="btn btn-success mb-2" href="{{ route('patrol-areas.create') }}"
        style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Patrol Area</a>
      </div>
      <h1>Patrol Areas</h1>
      <div class="table-outer">
      <div class="table-responsive">
        <table class="table table-striped truncate m-0">
        <thead>
          <tr>
          <th>S/No</th>
          <th>Start Area</th>
          <th>End Area</th>
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
          @foreach ($patrolAreas as $patrolArea)
        <tr>
        <td>{{ ++$i }}.</td>
        <td>{{ $patrolArea->start_area }}</td>
        <td>{{ $patrolArea->end_area }}</td>
        <td>{{ $patrolArea->company->name }}</td>
        <td>{{ $patrolArea->campus->campusName }}</td>
        <td>{{ $patrolArea->addedBy->name }}</td>
        <td>{{ $patrolArea->number_of_guards }}</td>
        <td>
        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
        data-bs-target="#statusModal{{ $patrolArea->id ?? ''}}">
        More
        </button>
        <form id="deleteForm{{ $patrolArea->id }}" action="{{ route('patrol-areas.destroy', $patrolArea) }}"
        method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" onclick="confirmDelete('deleteForm{{ $patrolArea->id }}','Patrol Area')" type="button">Delete</button>
        </form>
        @include('layouts.sweet_alerts.confirm_delete')
        </td>

        <div class="modal fade" id="statusModal{{  $patrolArea->id ?? '' }}" tabindex="-1"
        aria-labelledby="statusModalLabel{{  $patrolArea->id ?? '' }}" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="statusModalLabel{{  $patrolArea->id ?? ''}}">
          {{ $patrolArea->start_area }} to {{ $patrolArea->end_area }} Exceptions
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="mb-2">
          <span>Number of guards: {{ $patrolArea->number_of_guards }}</span>
          </div>
          <div class="mb-4">
          <h3> Beat Exceptions </h3>
          </div>
          @if($patrolArea->beat_exceptions && $patrolArea->beat_exceptions->isNotEmpty())
        <ol>
        @foreach ($patrolArea->beat_exceptions as $beat_exception)
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
          @if($patrolArea->beat_exceptions && $patrolArea->beat_exceptions->isNotEmpty())
        <ol>
        @foreach ($patrolArea->beat_time_exceptions as $beat_time_exception)
      <li>{{ $beat_time_exception->name }}</li>
    @endforeach
        </ol>
      @else
      <div>
      <h4>No Beat Exceptions</h4>
      </div>
    @endif
          <div class="modal-footer">
          <div class="d-flex gap-2 justify-content-center">
          <a class="btn btn-sm btn-primary"
            href="{{ route('patrol-areas.edit', $patrolArea) }}">Edit</a>
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

  <!-- Include SweetAlert2 CDN -->
@endsection