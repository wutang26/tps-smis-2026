@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Lists</a></li>
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
          <a class="btn btn-success mb-2" href="{{ route('semesters.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Semester</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead> 
                <tr> 
                  <th scope="col">#</th> 
                  <th scope="col">Semester Name</th> 
                  <th scope="col">Actions</th> 
                </tr> 
              </thead>
              <tbody>                   
                @foreach($semesters as $semester)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $semester->semester_name }}</td>

                    <!-- <td> <a href="{{ route('semesters.edit', $semester->id) }}" class="btn btn-warning">Edit</a> 
                    <form action="{{ route('semesters.destroy', $semester->id) }}" method="POST" class="d-inline"> 
                      @csrf 
                      @method('DELETE') 
                      <button type="submit" class="btn btn-danger">Delete</button> 
                    </form> 
                  </td> -->

                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('semesters.show',$semester->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('semesters.edit',$semester->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                          <form id="deleteForm{{ $semester->id }}" method="POST" action="{{ route('semesters.destroy', $semester->id) }}" style="display:inline">
                              @csrf
                              @method('DELETE')
                              <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('deleteForm{{ $semester->id }}', '{{ $semester->semester_name }} ')"><i class="fa-solid fa-trash"></i> Delete</button>
                          </form>
                    </td>
                </tr>
              @endforeach
              </tbody>
            </table>
            <!-- Paginate -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Row ends -->
@endsection