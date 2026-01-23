@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Session Programmes</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">List Programme Sessions</a></li>
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
      <div class="pull-right" >
          <a class="btn btn-success mb-2" href="{{ route('session_programmes.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Session</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Session Programme Name</th>
                  <th scope="col">Description</th>
                  <th scope="col">Year</th>
                  <th scope="col">Start Date</th>
                  <th scope="col">End Date</th>
                  <th scope="col">Is Current</th>
                  <th scope="col">Is Active</th>
                  <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody>                   
              @foreach ($session_programmes as $key => $session_p)
                <tr>
                      <td>{{ ++$i }}</td>
                      <td>{{ $session_p->session_programme_name }}</td>
                      <td>{{ $session_p->description }}</td>
                      <td>{{ $session_p->year }}</td>
                      <td>{{ $session_p->startDate }}</td>
                      <td>{{ $session_p->endDate }}</td>
                      <td>
                        @if($session_p->is_current == 1)
                            <label class="badge bg-success">Yes</label>
                        @else
                            <label class="badge bg-danger">No</label>
                        @endif
                      </td>
                      <td>
                        @if($session_p->is_active == 1)
                            <label class="badge bg-success">Yes</label>
                        @else
                            <label class="badge bg-danger">No</label>
                        @endif
                      </td>
                      <td>
                          <a class="btn btn-info btn-sm" href="{{ route('session_programmes.show',$session_p->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                          <a class="btn btn-primary btn-sm" href="{{ route('session_programmes.edit',$session_p->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form id="deleteForm{{ $session_p->id }}" method="POST" action="{{ route('session_programmes.destroy', $session_p->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('deleteForm{{ $session_p->id }}', '{{ $session_p->session_programme_name}} Session')"><i class="fa-solid fa-trash" ></i> Delete</button>
                            </form>
                      </td>
                  </tr>
              @endforeach
              </tbody>
            </table>
            {!! $session_programmes->links('pagination::bootstrap-5') !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Row ends -->
@endsection