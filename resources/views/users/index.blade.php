@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">User List</a></li>
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
      <div class="col-12 d-flex justify-content-center">
          <form class="d-flex" action="{{route('users.search')}}" method="GET">
              @csrf
              @method("GET")
              <div class="d-flex gap-2">
                <label for="">Filter </label>
                  <!-- Name Search -->
                  <input type="text" value="{{ request('name')}}" class="form-control me-2" name="name"
                      placeholder="Name(Optional)">
                  <!-- Company Dropdown -->
                  <select onchange="this.form.submit()" class="form-select me-2" name="role"
                      >
                      <option value="" selected disabled>Select Role</option>
                      @foreach ($roles as $role)
                      <option value="{{ $role->id }}"
                          {{ request('role') == $role->id ? 'selected' : '' }}>
                          {{ $role->name }}
                      </option>
                      @endforeach
                  </select>
              </div>
          </form>
      </div>

      <div class="pull-right" >
          <!-- <h5 class="card-title" style="float:left !important; margin-left:1%">Users Management</h5> -->
          <a class="btn btn-success mb-2" href="{{ route('users.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New User</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Roles</th>
                  <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody>                   
              @foreach ($users as $key => $user)
                <tr>
                      <td>{{ ++$i }}</td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>
                        @if(!empty($user->getRoleNames()))
                          @foreach($user->getRoleNames() as $v)
                            <label class="badge bg-success">{{ $v }}</label>
                          @endforeach
                        @endif
                      </td>
                      <td>
                          <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                          <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form id="deleteForm{{ $user->id }}" method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('deleteForm{{ $user->id }}', 'User {{ $user->name }}')"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                      </td>
                  </tr>
              @endforeach
              </tbody>
            </table>
            {!! $users->links('pagination::bootstrap-5') !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Row ends -->
@endsection