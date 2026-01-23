@extends('layouts.main')

@section('style')
<style>
.breadcrumb {
    display: flex;
    width: 100%;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

#date {
    position: absolute;
    bottom: 10px;
    /* Adjust as needed */
    right: 15px;
    /* Adjust as needed */
}
</style>
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Course</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Lists</a></li>
                <li class="breadcrumb-item right-align"><a href="#"
                        id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a></li>
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
            @can('course-create')
            <div class="pull-right">
                <a class="btn btn-success mb-2" href="{{ route('courses.create') }}"
                    style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New course</a>
            </div>
            @endcan()
            <div class="card-body">
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Department</th>
                                    <th scope="col" width="280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $key => $course)
                                <tr>
                                    <td>{{ $loop->iteration}}.</td>
                                    <td>{{ $course->courseName }}</td>
                                    <td>{{ $course->courseCode }}</td>
                                    <td>{{ $course->department->departmentName ?? '' }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('courses.show',$course->id) }}"><i
                                                class="fa-solid fa-list"></i> Show</a>
                                        @can('course-edit')
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('courses.edit',$course->id) }}"><i
                                                class="fa-solid fa-pen-to-square"></i> Edit</a>
                                        @endcan
                                        @can('course-delete')
                                        <form method="POST" action="{{ route('courses.destroy', $course->id) }}"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fa-solid fa-trash"></i> Delete</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $courses->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->
@endsection