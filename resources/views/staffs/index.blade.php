@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Staffs</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Staff Lists</a></li>
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
    @php
        $i = 0;
    @endphp
    <!-- Row starts -->
    <div class="row gx-4">
        <div class="col-sm-12">
            <div class="row flex-nowrap overflow-auto align-items-center g-2 justify-content-between">
                <!-- Left: Upload Staff -->
                <div class="d-flex col-auto gap-2">
                    @can('student-create')
                    <a href="{{ route('uploadStaff') }}" class="btn btn-sm btn-primary">Upload Staff</a>
                    @endcan

                    @can('student-edit')
                    <a href="{{ route('updateStaffs') }}" class="btn btn-sm btn-secondary">Update Staff(s)</a>
                    @endcan
                </div>

                <!-- Center: Filter Form -->
                <div class="col-auto mx-auto">
                    <form class="d-flex flex-nowrap gap-2 overflow-auto" action="{{ route('staff.search') }}" method="GET"
                        style="white-space: nowrap;">
                        @csrf
                        @method("GET")
                        <label class="d-flex align-items-center m-0">Filter</label>
                        <input type="text" name="name" value="{{ old('name', request('name')) }}"
                            class="form-control form-control-sm flex-shrink-0" style="width: 120px;"
                            placeholder="Name (optional)" autocapitalize="on">
                        <select name="company_id" onchange="this.form.submit()"
                            class="form-select form-select-sm flex-shrink-0" style="width: 140px;">
                            <option value="" selected disabled>Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- Right: Create New Staff -->
                <div class="d-flex col-auto gap-2 justify-content-end">
                    @can('student-create')
                        <a class="btn btn-success btn-sm flex-shrink-0" href="{{ route('staffs.create') }}">
                            <i class="fa fa-plus"></i> Create New Staff
                        </a>
                    @endcan
                </div>
            </div>
        
            <!-- Search container -->
            <div class="card mt-3">
            <div class="card-body">
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">PF Number</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Rank</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col" width="280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffs as $key => $staff)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $staff->forceNumber }}</td>
                                        <td>{{ $staff->firstName }} {{ $staff->middleName }} {{ $staff->lastName }}</td>
                                        <td>{{ $staff->rank }}</td>
                                        <td>{{ $staff->company->name ?? '' }}</td>
                                        <td>{{ $staff->department->departmentName ?? '' }}</td>
                                        <td>{{ $staff->phoneNumber }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('staffs.show', $staff->id) }}"><i
                                                    class="fa-solid fa-list"></i> Show</a>
                                            <a class="btn btn-primary btn-sm" href="{{ route('staffs.edit', $staff->id) }}"><i
                                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
                                            <form id="delete-staff-{{ $staff->id }}" method="POST"
                                                action="{{ route('staffs.destroy', $staff->id) }}" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('delete-staff-{{ $staff->id }}', '{{ $staff->forceNumber.' '. $staff->rank.' '. $staff->firstName.' '. $staff->lastName }}')"><i
                                                        class="fa-solid fa-trash"></i>
                                                    Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $staffs->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
     <script>
  function confirmDelete(formId, itemTitle) {
    Swal.fire({
      title: 'Delete "' + itemTitle + '"?',
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(formId).submit();
      }
    });
  }
</script>

@endsection