@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="">Time Sheet</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Create</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    @session('success')
        <div class="alert alert-success alert-dismissible " role="alert">
            {{ $value }}
        </div>
    @endsession

    <form action="{{ route('timesheets.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="row gx-4">
            <div class="col-sm-6 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Time(hours)</label>
                            <input type="number" class="form-control" id="hours" name="hours" required min="1"
                                value="{{old('hours')}}">
                        </div>
                        @error('hours')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required
                                value="{{old('date', \Carbon\Carbon::now()->format('Y-m-d') )}}"
                                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        @error('date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!-- Existing Task -->
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card mb-2">
                        <div class="card-body" id="taskContainer">
                            <div class="m-0">
                                
                                    <div class="d-flex gap-2 mb-2 task-row" data-index="0">
                                        <label for="">Task </label>
                                        <input value="{{ old('tasks[]') }}"
                                            style="width: 93%" class="form-control" type="text" name="tasks[]"
                                            id="task-0">
                                        <button type="button" class="btn btn-danger delete-task-btn"
                                            onclick="deleteTask(0)">Delete</button>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button id="addTaskButton" style="margin-right: 30px;" type="button" class="btn btn-primary mb-3">Add
                Task</button>
        </div>
        <div class="row">
            <div class="col-sm-12 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Description </label>
                            <textarea class="form-control" id="description" name="description"
                                placeholder="Describe your tasks here....">{{ old('description') }}</textarea>

                        </div>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-6 text-left">

                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <div id="taskContainer">
    <!-- Existing tasks will be rendered here -->
</div>


<script>
    // Function to delete a task row
    function deleteTask(index) {
        const taskRow = document.querySelector(`.task-row[data-index="${index}"]`);
        if (taskRow) {
            taskRow.remove(); // This removes the entire div from the DOM
            updateTaskIndexes(); // Update task indexes after deletion
            checkAndDisableDeleteButton(); // Recheck the delete button status
        }
    }

    // Get the button and task container elements
    const addTaskButton = document.getElementById('addTaskButton');
    const taskContainer = document.getElementById('taskContainer');

    // Function to add a new task input field
    addTaskButton.addEventListener('click', function () {
        const taskCount = document.querySelectorAll('.task-row').length; // Get the next task index

        // Create a new row for the new task
        const newTaskRow = document.createElement('div');
        newTaskRow.classList.add('d-flex', 'gap-2', 'mb-2', 'task-row');
        newTaskRow.setAttribute('data-index', taskCount); // Assign a unique index to the new row

        // Add HTML structure for the task input and delete button
        newTaskRow.innerHTML = `
            <label for="">Task</label>
            <input class="form-control" type="text" name="tasks[]" id="task-${taskCount}">
            <button type="button" class="btn btn-danger delete-task-btn" onclick="deleteTask(${taskCount})">Delete</button>
        `;

        // Append the new task row to the task container
        taskContainer.appendChild(newTaskRow);

        // Recheck the delete button state (disable if only one task)
        checkAndDisableDeleteButton();
    });

    // Function to update task indexes after deletion
    function updateTaskIndexes() {
        const taskRows = document.querySelectorAll('.task-row');
        taskRows.forEach((taskRow, index) => {
            taskRow.setAttribute('data-index', index); // Reassign data-index
            taskRow.querySelector('input').id = `task-${index}`; // Update input id
            taskRow.querySelector('button').setAttribute('onclick', `deleteTask(${index})`); // Update delete button
        });
    }

    // Function to check and disable the delete button if only one task is left
    function checkAndDisableDeleteButton() {
        const taskRows = document.querySelectorAll('.task-row');
        const deleteButtons = document.querySelectorAll('.delete-task-btn');

        // If only one task is left, disable the delete button for that task
        if (taskRows.length === 1) {
            deleteButtons.forEach(button => {
                button.disabled = true; // Disable all delete buttons
            });
        } else {
            // Enable the delete buttons for all tasks
            deleteButtons.forEach(button => {
                button.disabled = false;
            });
        }
    }

    // Run the check on initial load to ensure proper state
    document.addEventListener('DOMContentLoaded', checkAndDisableDeleteButton);
</script>

@endsection