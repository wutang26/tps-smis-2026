@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Roles</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Modify Roles</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')


<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-9 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Create New Role</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $role->name }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <strong>Permission:</strong>
                                <br/>
                            <div class="row"> @foreach($permissions->chunk(ceil($permissions->count() / 4)) as $chunk) <div class="col-md-3"> @foreach($chunk as $permission) <div class="form-check"> <input class="form-check-input" type="checkbox"  name="permission[{{$permission->id}}]"  value="{{ $permission->id }}" id="permission-{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}> <label class="form-check-label" for="permission-{{ $permission->id }}"> {{ $permission->description }} </label> </div> @endforeach </div> @endforeach </div>
                        </div>



                        <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="margin-top:30px">
                            <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>     
    </div>
  
    <div class="col-sm-3 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection