@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Student Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Registered Courses for You</a></li>
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
                @if (session('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
            </div>
                @php
                $i=1;
                $j=1;
                @endphp

                <div class="card-body">
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered truncate m-0">
                                <thead>
                                    <tr>
                                        <th>Semester One</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Course Type</th>
                                        <th scope="col">Credit Weight</th>
                                        <th scope="col" width="280px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                    @if ($course->pivot->course_type != 'optional' && $course->pivot->semester_id == 1)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                
                                <tbody>
                                    <tr>
                                        <th colspan="7">Optional Course(s)</th>
                                    </tr>
                                    @foreach ($courses as $course)
                                    @if ($course->pivot->course_type === 'optional' && $course->pivot->semester_id == 1)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('assign-courses.destroy', $course->id ) }}"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash"></i> Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="table-outer" style="margin-top:20px">
                        <div class="table-responsive">
                        @php
                            $semesterId = 2; // The specific semester you want to filter by
                            $filteredCourses = $courses->filter(function ($course) use ($semesterId) {
                                return $course->pivot->semester_id == $semesterId;
                            });
                        @endphp

                        @if($filteredCourses->isEmpty())
                            <p>There are no courses registered for semester Two!</p>
                        @else
                            <table class="table table-striped truncate m-0">
                                <thead>
                                    <tr>
                                        <th>Semester Two</th>
                                    </tr>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Course Code</th>
                                        <th scope="col">Course Type</th>
                                        <th scope="col">Credit Weight</th>
                                        <th scope="col" width="280px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($filteredCourses as $course)
                                    @if ($course->pivot->course_type != 'optional')
                                    <tr>
                                        <td>{{$j++}}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('assign-courses.destroy', $course->id ) }}"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash"></i> Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                <tbody>
                                    <tr>
                                        <th>Optional Course(s)</th>
                                    </tr>
                                    @foreach ($filteredCourses as $course)
                                    @if ($course->pivot->course_type === 'optional')
                                    <tr>
                                        <td>{{$j++}}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('assign-courses.destroy', $course->id ) }}"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash"></i> Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<!-- Row ends -->
@endsection