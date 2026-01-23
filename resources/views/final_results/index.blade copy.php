@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Final Results</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Final Results Lists</a></li>
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
          <!-- <a class="btn btn-success mb-2" href="{{ route('final_results.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New course</a> -->
      
          <form action="{{ route('final_results.generate') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary" style="float:right !important; margin-right:1%">Generate Final Results</button>
        </form>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Student</th>
                      <th>Semester</th>
                      <th>Course</th>
                      <th>Total Score</th>
                      <th>Grade</th>
                      <th>Actions</th>
                      
                  <!-- <tr>
                      <th scope="col">No</th>
                      <th scope="col">Course Name</th>
                      <th scope="col">Course Code</th>
                      <th scope="col">Department</th>
                      <th scope="col" width="280px">Actions</th>
                  </tr> -->
                  </tr>
              </thead>
              <tbody>  
              @php
                $i=1;
              @endphp                 
              @foreach($finalResults as $finalResult)
                <tr>
                    <!-- <td>{{ ++$i }}</td> -->
                    <td>{{ $finalResult->id }}</td>
                    <td>{{ $finalResult->student->name }}</td>
                    <td>{{ $finalResult->semester->semester_name }}</td>
                    <td>{{ $finalResult->course->course_name }}</td>
                    <td>{{ $finalResult->total_score }}</td>
                    <td>{{ $finalResult->grade }}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('final_results.show',$course->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('final_results.edit',$course->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                          <form method="POST" action="{{ route('final_results.destroy', $final_results->id) }}" style="display:inline">
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