@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item "><a href="beats">Beats</a></li>
        <li class="breadcrumb-item active"><a href="beats/search">Search</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<div class="col-sm-12">
  <div class="card-header">
    <h5 class="card-title">Search students to assign for {{$area->name}}</h5>
  </div>
  <div class="card-body">

    <form method="GET" action="{{url('beats/' . $area->id . '/search_students')}}"
      class="d-flex justify-content-between mb-3">
      @csrf
      @method('GET')
      <div class="d-flex">
        <!-- Company Dropdown -->
        <select class="form-select me-2" name="company" required>
          <option value="">Select Company</option>
          @foreach ($companies as $company)
        <option value="{{$company->name}}">{{$company->name}}</option>
      @endforeach
          @error('company')
        <div class="error">{{ $message }}</div>
      @enderror
        </select>

        <!-- Platoon Dropdown -->
        <select class="form-select me-2" name="platoon" required>
          <option value="">Select Platoon</option>
          @for ($i = 1; $i <= 14; ++$i)
        <option value="{{$i}}">{{$i}}</option>
      @endfor
          @error('platoon')
        <div class="error">{{ $message }}</div>
      @enderror
        </select>

        <!-- Name Search -->
        <input type="text" class="form-control me-2" name="fullname" placeholder="Last name(option)">
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
    @if (isset($students))
    @if($students->isNotEmpty())
    <hr>

    @error('arrested_at')
    <div class="error">{{ $message }}</div>
  @enderror
    <div class="table-responsive">
      <form action="{{url('beats/' . $area->id . '/assign_students')}}" method="post">
      @csrf
      @method("POST")
      <div class="mb-3  ">
        <div class="row">
          <div class="col-4">
            <label for="start_at" class="form-label  mt-2">Beat start at : </label>
            <input value=""  class="form-control " type="datetime-local" required name="start_at">            
          </div>
          <div class="col-4">
            <label for="end_at" class="form-label  mt-2">Beat end at : </label>
            <input value="" class="form-control " type="datetime-local" required name="end_at">            
          </div>
        </div>

      
      </div>
      </div>

      <table class="table table-striped truncate m-0">
      <thead>
      <tr>
        <th>Names</th>
      </tr>
      </thead>
      <tbody>
      @foreach($students as $student)
      <tr>
      <td>
      <div class="form-check">
      <input class="form-check-input" name="student_ids[]" type="checkbox" value="{{$student->id}}"
      id="defaultCheck" .{{$student->id}}>
      <label class="form-check-label" for="defaultCheck1">{{$student->first_name}} {{$student->middle_name}}
      {{$student->last_name}}</label>
      </div>
      </td>
      </tr>
    @endforeach
      </tbody>
      </table>
      <div class="d-flex gap-2 justify-content-end">
      <button type="submit" class="btn btn-primary">Assign</button>
      </div>
      </form>
    </div>
  @else
  <p>No students available to assign.</p>
@endif
  @endif
  </div>
</div>
@endsection