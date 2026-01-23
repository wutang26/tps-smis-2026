@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Vitengo</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">List Vitengo</a></li>
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
          <a class="btn btn-success mb-2" href="{{ route('vitengo.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Kitengo</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col"> Name</th>
                  <th scope="col">Description</th>
                  <th scope="col">Is Active</th>
                  <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody>                   
              @foreach ($vitengo as $key => $kitengo)
                <tr>
                      <td>{{ ++$i }}</td>
                      <td>{{ $kitengo->name }}</td>
                      <td>{{ $kitengo->description }}</td>
                      <td>
                        @if($kitengo->is_active == 1)
                            <label class="badge bg-success" >Yes</label>
                            <!-- Clickable label that triggers SweetAlert -->
                          <label class="badge bg-danger btn-sm" style="cursor:pointer;"
                                onclick="confirmAction('deactivateForm{{ $kitengo->id }}', 'Deactivate Kitengo {{ $kitengo->name }}', 'this item', 'deactivate')">
                            Deactivate
                          </label>


                          <!-- Hidden form to be submitted upon confirmation -->
                          <form id="deactivateForm{{ $kitengo->id }}" action="{{ route('vitengo.deactivate') }}" method="POST" style="display:none;">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="kitengo_id" value="{{ $kitengo->id }}">
                          </form>

                        @else
                            <label class="badge bg-danger">No</label>
                            <label class="badge bg-success btn-sm" style="cursor:pointer;"
                                onclick="confirmAction('activateForm{{ $kitengo->id }}', 'Activate Kitengo {{ $kitengo->name }}', 'this item', 'activate')">
                              Activate
                            </label>
                            <form id="activateForm{{ $kitengo->id }}" action="{{ route('vitengo.activate') }}" method="POST" style="display:none;">
                              @csrf
                              @method('POST')
                              <input type="hidden" name="kitengo_id" value="{{ $kitengo->id }}">
                            </form>
                        @endif
                      </td>
                      <td>
                          <a class="btn btn-info btn-sm" href="{{ route('vitengo.show',$kitengo->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                          <a class="btn btn-primary btn-sm" href="{{ route('vitengo.edit',$kitengo->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form id="deleteForm{{ $kitengo->id }}" method="POST" action="{{ route('vitengo.destroy', $kitengo->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('deleteForm{{ $kitengo->id }}', '{{ addslashes('Kitengo '.$kitengo->name) }}')"

                                ><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                      </td>
                  </tr>
              @endforeach
              </tbody>
            </table>
            {!! $vitengo->links('pagination::bootstrap-5') !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Row ends -->
@endsection