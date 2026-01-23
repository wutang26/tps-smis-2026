@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Mpango Kazi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Mpango Kazi</li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection

@section('content')
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Edit Task (Mpango Kazi)</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('tasks.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Task Title:</strong>
                                <input type="text" name="title" value="{{ old('title', $task->title) }}" class="form-control" placeholder="Enter task title">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Descriptions:</strong>
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter Descriptions for this Task">{{ old('description', $task->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Task Priority:</strong>
                                <select name="priority" class="form-control">
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Task Due Date:</strong>
                                <input type="date" name="due_date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-4 col-12">
        <div class="card mb-8">
            <div class="card-body">
                <!-- Optional sidebar content -->
            </div>
        </div>
    </div>
</div>
@endsection
