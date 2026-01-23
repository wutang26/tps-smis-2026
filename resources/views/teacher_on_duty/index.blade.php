@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item active"><a href="">NCO On Duty</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')
<div class="row gx-4">
    <div class="col-sm-5 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h4>NCO on Duty</h4>
                <br><br>
                <table class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Company</th>
                            <th scope="col">Start date</th>
                            <th scope="col" width="280px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $j = 0;
                        @endphp
                        @foreach ($teachers as $key => $teacher)
                        <tr>
                            <td>{{++$j}}</td>
                            <td> {{ $teacher->staff->forceNumber }} {{ $teacher->staff->rank }}
                                {{ $teacher->staff->firstName }} </td>
                            <td>{{ $teacher->company->name }}</td>
                            <td>{{ $teacher->start_date }}</td>
                            <td>
                                @can('teacher_on_duty-assign')
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#status{{ $teacher->id ?? ''}}">
                                    Unassign
                                </button>
                                @endcan
                            </td>
                        </tr>
                        <div class="modal fade" id="status{{ $teacher->id ?? ''}}" tabindex="-1"
                            aria-labelledby="statusModalLabel{{  $teacher->id ?? '' }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-info" id="statusModalLabel{{  $teacher->id ?? ''}}">
                                            Unassign {{ $teacher->staff->forceNumber }} {{ $teacher->staff->rank }}
                                            {{ $teacher->staff->user->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4>Started from {{$teacher->start_date}}</h4><br>
                                        <form action="{{ route('teacher_on_duty.unassign', $teacher->id) }}"
                                            method="GET">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="end_date" id="start_date"
                                                    required>
                                            </div>
                                            @can('teacher_on_duty-assign')
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fa-solid fa-plus"></i> Unassign
                                                </button>
                                            </div>
                                            @endcan
                                        </form>
                                    </div>
                                    <div class="modal-footer">

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-7 col-12">
        <div class="card mb-4">
            <div class="card-body">
                @if (Auth::user()->hasPermissionTo('student-create'))
                <div class="col-6 d-flex justify-content-end">
                    @else
                    <div class="d-flex justify-content-end">
                        @endif
                        <form class="d-flex" action="{{route('teacher_on_duty.search')}}" method="GET">
                            @csrf
                            @method("GET")
                            <div class="d-flex gap-2">
                                <label for="">Filter </label>
                                <!-- Name Search -->
                                <input type="text" value="{{ request('name')}}" class="form-control me-2" name="name"
                                    placeholder="name(option)">
                                <!-- Company Dropdown -->
                                <select onchange="this.form.submit()" class="form-select me-2" name="company_id"
                                    >
                                    <option value="" selected disabled>Select Company</option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped truncate m-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Company</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col" width="280px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffs as $key => $staff)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $staff->forceNumber }} {{ $staff->rank }}  {{ $staff->lastName }}</td>
                                <td>{{ $staff->company->name ?? '' }}</td>
                                <td>{{ $staff->email ?? '' }}</td>
                                <td>{{ $staff->phoneNumber }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $staff->id ?? ''}}">
                                        Assign
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="statusModal{{  $staff->id ?? '' }}" tabindex="-1"
                                aria-labelledby="statusModalLabel{{  $staff->id ?? '' }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-info"
                                                id="statusModalLabel{{  $staff->id ?? ''}}">
                                                Assign {{ $staff->forceNumber }} {{ $staff->rank }}
                                                {{ $staff->user->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('teacher_on_duty.store', $staff->id) }}"
                                                method="GET">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="start_date" class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" name="start_date"
                                                        id="start_date" required>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fa-solid fa-plus"></i> Assign
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $staffs->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection