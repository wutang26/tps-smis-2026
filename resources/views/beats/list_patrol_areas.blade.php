@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item active"><a href="/tps-smis/beats">Beat Patrol Areas </a></li>
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
<div class="table-responsive">
  <a style="float:right;"
    href="{{route('beats.downloadPdf', ['company_id' => $company->id, 'beatType' => 2, 'day' => "today"])}}"><button
      title="Download Pdf" class="btn btn-sm btn-success"><i class="bi bi-download"> </i> Download Pdf</button></a>
  <table class="table table-striped truncate m-0">
    <thead>
      <tr>
        <th></th>
        <th>Start Point</th>
        <th>End Point</th>
        <th width="280px">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php  $i = 0;?>
      @foreach ($patrol_areas as $patrol_area)
      <tr>
      <td>{{++$i}}</td>
      <td>{{$patrol_area->start_area}}</td>
      <td>{{$patrol_area->end_area}}</td>
      <td>
        <button class="btn  btn-primary btn-sm" data-bs-toggle="modal"
        data-bs-target="#MoreAbsent{{$patrol_area->id}}">Edit</button>

        @if ($patrol_area->beats->isNotEmpty())
      <a href="{{url('/beats/list-patrol-guards/' . $patrol_area->id)}}"><button
        class="btn btn-sm btn-info">View</button></a>
      <a href="{{ url('/beats/list-patrol/' . $patrol_area->id) }}"><button
        class="btn btn-sm btn-primary">Approve</button></a>
    @endif
        <div class="modal fade" id="MoreAbsent{{$patrol_area->id}}" tabindex="-1"
        aria-labelledby="statusModalLabelMore{{$patrol_area->id}}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="statusModalLabelMore">
            From {{$patrol_area->start_area}} to {{$patrol_area->end_area}}
            </h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{url('/beats/update_patrol_area/' . $patrol_area->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
            <div class="row">
            <div class="col-4">
              <label class="form-label" for="abc">Start </label>
            </div>
            <div class="col-8">
            <select class="form-select" id="abc4" name="start_area" required aria-label="Default select example">
            <option selected disabled value="">start area</option>
              @foreach ($company->areas as $area)
              <option @if ($patrol_area->id == $patrol_area->id) selected @endif value="{{$patrol_area->id}}">{{$patrol_area->start_area}}</option>
              @endforeach
            </select>
            </div>
            </div>
            <div class="row mt-2">
            <div class="col-4">
              <label class="form-label" for="abc">End </label>
            </div>
            <div class="col-8 ">
            <select class="form-select" id="abc4" name="end_area" required aria-label="Default select example">
            <option selected disabled value="">end area</option>
              @foreach ($company->areas as $area)
              <option @if ($patrol_area->id == $patrol_area->id) selected @endif value="{{$patrol_area->id}}">{{$patrol_area->end_area}}</option>
              @endforeach
            </select>
            </div>
            </div>   
            <div class="row mt-2">       
                <div class="col-4 mt-2 mb-2">
                <label class="form-label" for="abc">Guards </label>
                </div>
                <div class="col-8">
                <input type="number" min="2" value="{{$patrol_area->number_of_guards}}" class="form-control" id="number_of_guards" name="number_of_guards"
                value="{{old('number_of_guards')}}">
                </div>
                </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </div>
          </form>
          </div>
        </div>
        </div>
      </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection