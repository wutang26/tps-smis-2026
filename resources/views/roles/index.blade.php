@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Roles</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Roles List</a></li>
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
  <div class="col-sm-8 col-12">
    <div class="card mb-3">
      <div class="card-header">
        <!-- <h5 class="card-title">Roles Management</h5> -->
      </div>
        <div class="pull-right" >      
            @can('role-create')
                <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Role</a>
            @endcan
        </div>
      
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Name</th>
                  <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody>                   
              @foreach ($roles as $key => $role)
                <tr> 
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                        @can('role-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        @endcan

                        @can('role-delete')
                        <form id="deleteForm{{ $role->id }}" method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('deleteForm{{ $role->id }}', 'Role {{ $role->name}}')"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
              @endforeach
              </tbody>
            </table>

            
{!! $roles->links('pagination::bootstrap-5') !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-4 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
  </div>
</div>
<!-- Row ends -->

@endsection
