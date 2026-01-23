@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Course Assignments</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">List of Courses for
                        {{ $programme->programmeName }}</a></li>
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
            <div class="pull-right">
                <a class="btn btn-success mb-2" href="{{ route('assign-courses.create', $programme->id ) }}"
                    style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Assign New Courses</a>
            </div>
                @php
                $i=1;
                $j=1;
                @endphp

                <div class="card-body">
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table table-striped truncate m-0">
                                <thead>
                                    <tr>
                                        <th>Semester One</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" width="1%">No</th>
                                        <th scope="col" width="15%">Course Code</th>
                                        <th scope="col" width="50%">Course Name</th>
                                        <th scope="col" width="14%">Course Type</th>
                                        <th scope="col" width="10%">Credit Weight</th>
                                        <th scope="col" width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses1 as $course)
                                    @if ($course->pivot->course_type != 'Optional')
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('assign-courses.edit',$course->id) }}"><i
                                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
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
                                        <th colspan="7">Optional Course(s)</th>
                                    </tr>
                                    @foreach ($courses1 as $course)
                                    @if ($course->pivot->course_type === 'Optional')
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('assign-courses.edit',$course->id) }}"><i
                                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
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
                            <table class="table table-striped truncate m-0">
                            <thead>
                                    <tr>
                                        <th>Semester Two</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" width="1%">No</th>
                                        <th scope="col" width="15%">Course Code</th>
                                        <th scope="col" width="50%">Course Name</th>
                                        <th scope="col" width="14%">Course Type</th>
                                        <th scope="col" width="10%">Credit Weight</th>
                                        <th scope="col" width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses2 as $course)
                                    @if ($course->pivot->course_type != 'Optional')
                                    <tr>
                                        <td>{{$j++}}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('assign-courses.edit',$course->id) }}"><i
                                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
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
                                        <th colspan="7">Optional Course(s)</th>
                                    </tr>
                                    @foreach ($courses2 as $course)
                                    @if ($course->pivot->course_type === 'Optional')
                                    <tr>
                                        <td>{{$j++}}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('assign-courses.edit',$course->id) }}"><i
                                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
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
                </div>
        </div>
    </div>
</div>
<!-- Row ends -->
@endsection