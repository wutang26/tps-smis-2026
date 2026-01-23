@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Departments</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Departments Lists</a></li>
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
          <a class="btn btn-success mb-2" href="{{ route('departments.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Department</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Department Name</th>
                    <th scope="col">Department Descriptions</th>
                    <th scope="col">Status</th>
                    <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody> 
              @php
              $i = 0;
              @endphp                  
              @foreach ($departments as $key => $dep)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $dep->departmentName }}</td>
                    <td>{{ $dep->description }}</td>
                    <td>
                      @if($dep->is_active == 1)
                          <label class="badge bg-success">Active</label>
                      @else
                          <label class="badge bg-danger">Inactive</label>
                      @endif
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('departments.show',$dep->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('departments.edit',$dep->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                          <form id="deleteForm{{ $dep->id }}" method="POST" action="{{ route('departments.destroy', $dep->id) }}" style="display:inline">
                              @csrf
                              @method('DELETE')
                              <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('deleteForm{{ $dep->id }}', 'Department of {{ $dep->departmentName}}')"><i class="fa-solid fa-trash"></i> Delete</button>
                          </form>
                    </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Row ends -->
@endsection