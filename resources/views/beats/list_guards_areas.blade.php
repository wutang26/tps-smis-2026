@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="">Beats</a></li>
        <li class="breadcrumb-item active"><a href="">Area</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession
<!-- <div class="pull-right">
  <a class="btn btn-success mb-2" href="{{ url('students/create') }}"
    style="float:right !important; margin-right:-12px"><i class="fa fa-plus"></i> Generate Beats</a>
</div> -->
@if ($areas->isEmpty())

  <h5>No areas assigned to this Company.</h5>

@else

  <div class="table-responsive">
  <a style="float:right;" href="{{route('beats.downloadPdf',['company_id' => $company->id,'beatType' => 1, 'day'=> "today"])}}"><button title="Download Pdf" class="btn btn-sm btn-success"><i class="bi bi-download"> </i> Download Pdf</button></a>
    <table class="table table-striped truncate m-0">
    <thead>
      <tr>
      <th>No</th>
      <th>Name</th>
      <th>Assigned</th>
      <th width="280px">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php  $i = 0;?>
      @foreach ($areas as $area)
      <tr>
      <td>{{++$i}}</td>
      <td>{{$area->name}}</td>
      <td>
      @if ($area->beats->isNotEmpty())
      Yes
    @else
      No
    @endif
      </td>
      <td>
      <button class="btn  btn-primary btn-sm" data-bs-toggle="modal"
      data-bs-target="#MoreAbsent{{$area->id}}">Edit</button>
      <div class="modal fade" id="MoreAbsent{{$area->id}}" tabindex="-1"
      aria-labelledby="statusModalLabelMore{{$area->id}}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabelMore">
        Edit {{$area->name}} area
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{url('/beats/update/'.$area->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
        <div class="row">
          <div class="col-4">
          <label class="form-label" for="abc">Name </label>
          </div>
          <div class="col-8">
          <input type="text" value="{{$area->name}}" class="form-control" id="name" name="name"
          placeholder="Enter area name" value="{{old('name')}}">
          </div>
        </div>
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror
        <div class="row mt-2">
          <div class="col-4">
          <label class="form-label" for="abc">Company </label>
          </div>
          <div class="col-8">
            <select class="form-select" id="abc4" name="company" required aria-label="Default select example">
            <option selected disabled value="">select company</option>
              @foreach ($companies as $company)
              <option @if ($area->company_id == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
              @endforeach
            </select>
          </div>
        @error('company')
          <div class="error">{{ $message }}</div>
        @enderror
        
        <div class="row mt-2 mb-2">
          <div class="col-4">
          <label class="form-label" for="abc">Guards </label>
          </div>
          <div class="col-8">
          <input type="number" min="2" value="{{$area->number_of_guards}}" class="form-control" id="number_of_guards" name="number_of_guards"
           value="{{old('number_of_guards')}}">
          </div>
        </div>
        @error('number_of_guards')
          <div class="error">{{ $message }}</div>
        @enderror
        <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
        </div>
        </form>
        </div>
      </div>
    </div>
    </div>
      </div>
      @if ($area->beats->isNotEmpty())

      <a href="{{url('/beats/show_guards/' . $area->id)}}"><button class="btn btn-sm btn-info">View</button></a>
      <a href="{{ url('/beats/list-guards/' . $area->id) }}"><button
      class="btn btn-sm btn-primary">Approve</button></a>

    @endif
      </td>
      </tr>
    @endforeach
    </tbody>
    </table>
  </div>
@endif

@endsection