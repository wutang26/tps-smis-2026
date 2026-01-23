@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Excuse Types</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Excuse Type Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
  <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">
        
      </div>
      <div class="pull-right" >
          <!-- <h5 class="card-title" style="float:left !important; margin-left:1%">Programme Management</h5> -->
          <a class="btn btn-success mb-2" href="{{ route('excuse_types.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Excuse Type</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Excuse Type</th>
                    <th scope="col">Descriptions</th>
                    <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody> 
              @php
              $i = 0;
              @endphp                  
              @foreach ($excuseTypes as $key => $excuseType)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $excuseType->excuseName }}</td>
                    <td>{{ $excuseType->description }}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('excuse_types.show',$excuseType->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('excuse_types.edit',$excuseType->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                          <form method="POST" action="{{ route('excuse_types.destroy', $excuseType->id) }}" style="display:inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
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