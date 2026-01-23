@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Roles</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">View Roles</a></li>
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
                        <!-- <div class="pull-left">
                            <h2>View Role</h2>
                        </div> -->
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $role->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12" style="font-weight:normal">
                        <div class="form-group">
                            <strong>Permissions:</strong>
                            <table class="table table-bordered mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Category</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ($permissions as $category => $perms)
                                        <tr>
                                            <td>{{ $category }}</td>
                                            <td>
                                                @foreach ($perms as $perm)
                                                    {{ $perm->description }},
                                                @endforeach
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
  
    <div class="col-sm-3 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection
