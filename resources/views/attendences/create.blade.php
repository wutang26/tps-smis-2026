@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/attendences/">Attendances</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Create</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    <h5>Please check present students.</h5>
    <form action="
            @if(isset($attendence))
                {{url('attendences/' . $attendence->id . '/update')}}
            @else
                {{url('attendences/' . $attendenceType->id . '/' . $platoon->id . '/store')}}
            @endif

        " method="POST">
        @csrf
        @method('POST')
        <div class="row">
            @foreach($students as $student)
                <div class="col-12 col-sm-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" onclick="updateSelectAll()" type="checkbox" name="student_ids[]"
                            value="{{$student->id}}" id="student-{{$student->id}}">
                        <label class="form-check-label" for="student-{{$student->id}}">
                            {{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        <input type="text" name="date" value="{{ $date }}" hidden>
        <div class=" mt-4">
            <label class="form-check-label" style="text-decoration:underline; color:blue;">
                <input id="selectAll" class="form-check-input" type="checkbox" onclick="toggleSelectAll(this)">
                Select All
            </label>
        </div>


        <div class="d-flex gap-2 justify-content-end">
            <button id="submit_btn" disabled type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    <style>
        /* Optional: shrink checkbox and label on mobile */
        @media (max-width: 576px) {
            .form-check-input {
                width: 1rem;
                height: 1rem;
            }

            .form-check-label {
                font-size: 0.85rem;
            }
        }
    </style>
    <script>
        // JavaScript function to toggle the state of checkboxes
        function toggleSelectAll(source) {
            // Get all checkboxes with the name "student_ids[]"
            var checkboxes = document.getElementsByName('student_ids[]');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked; // Set each checkbox's checked state to match the "Select All" checkbox
            }
            var submit_btn = document.getElementById('submit_btn');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    submit_btn.disabled = false;
                    break;
                }
                submit_btn.disabled = true;
            }
        }

        // Function to check the "Select All" box if all checkboxes are selected
        function updateSelectAll() {
            var checkboxes = document.getElementsByName('student_ids[]');
            var selectAll = document.getElementById('selectAll');
            var submit_btn = document.getElementById('submit_btn');
            submit_btn.disabled = true;
            // Loop through all checkboxes to check if any is unchecked
            for (var i = 0; i < checkboxes.length; i++) {
                if (!checkboxes[i].checked) {
                    allChecked = false;
                    break;
                }
            }
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    submit_btn.disabled = false;
                    break;
                }
            }

            // If all checkboxes are checked, mark the "Select All" checkbox as checked, otherwise uncheck it
            selectAll.checked = allChecked;
        }
        function updateSubmitBtn() {

        }
    </script>
@endsection