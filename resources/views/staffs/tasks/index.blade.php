@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Mpango Kazi</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Mpango Kazi Lists</a></li>
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
        <!-- Optional header content -->
      </div>
      <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('tasks.create') }}" style="float:right !important; margin-right:1%">
          <i class="fa fa-plus"></i> Add New Task
        </a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th scope="col"><strong>No</strong></th>
                  <th scope="col"><strong>Task Title</strong></th>
                  <th scope="col"><strong>Priority</strong></th>
                  <th scope="col"><strong>Start Date</strong></th>
                  <th scope="col"><strong>Due Date</strong></th>
                  <th scope="col"><strong>Status</strong></th>
                  <th scope="col"><strong>Created At</strong></th>
                  <th scope="col" width="280px"><strong>Actions</strong></th>
                </tr>
              </thead>
              <tbody>
                @php $i = 0; @endphp
                @foreach ($tasks as $task)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $task->title }}</td>
                  <td>
                    <span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'secondary') }}">
                      {{ ucfirst($task->priority) }}
                    </span>
                  </td>
                  <td>{{ optional($task->start_date)->format('d M Y') ?? '—' }}</td>
                  <td>{{ optional($task->due_date)->format('d M Y') ?? '—' }}</td>
                  <td>{{ $task->status }}</td>
                  <td>{{ $task->created_at->format('d M Y') }}</td>
                  <td>
                    <a class="btn btn-secondary btn-sm" href="{{ route('tasks.assign', $task->id) }}">
                      <i class="fa fa-users"></i> Assign
                    </a>
                    <a href="{{ route('tasks.staff', $task->id) }}" class="btn btn-sm btn-info">
                      View
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ route('tasks.edit', $task->id) }}">
                      <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <form id="deleteForm{{ $task->id }}" method="POST" action="{{ route('tasks.destroy', $task->id) }}" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm"
                        onclick="confirmDelete('deleteForm{{ $task->id }}', 'Task: {{ $task->title }}')">
                        <i class="fa-solid fa-trash"></i> Delete
                      </button>
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
