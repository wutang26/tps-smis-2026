@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item active"><a href="/tps-smis/beats">Area </a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@if ($beats->isEmpty())
    No students assigned
@else
    <div class="mb-2">
        Select present Guards for {{$beats[0]->area->name}}
    </div>

    <form action="{{url('/beats/approve')}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            @foreach ($beats as $beat)
                <div class="form-check col-6">
                    <input class="form-check-input"  onclick="updateSelectAll()" name="beat_ids[]" type="checkbox"
                        value="{{$beat->id}}" @if ($beat->status == 1) checked @endif id="defaultCheck" .{{ $beat->student->id}}>
                    <label class="form-check-label" for="defaultCheck1">{{$beat->student->first_name}}
                        {{$beat->student->middle_name}}
                        {{$beat->student->last_name}}</label>
                </div>
            @endforeach
        </div>
        <div class="d-flex gap-2 justify-content-end mt-2">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    <div class="">
        <label class="form-check-label" style="text-decoration:underline; color:blue;">
            <input id="selectAll" class="form-check-input" type="checkbox" onclick="toggleSelectAll(this)">
            Select All
        </label>
    </div>
@endif

<script>
    // JavaScript function to toggle the state of checkboxes
    function toggleSelectAll(source) {
        // Get all checkboxes with the name "student_ids[]"
        var checkboxes = document.getElementsByName('beat_ids[]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked; // Set each checkbox's checked state to match the "Select All" checkbox
        }
    }

    // Function to check the "Select All" box if all checkboxes are selected
    function updateSelectAll() {
        var checkboxes = document.getElementsByName('beat_ids[]');
        var selectAll = document.getElementById('selectAll');
        var allChecked = true;

        // Loop through all checkboxes to check if any is unchecked
        for (var i = 0; i < checkboxes.length; i++) {
            if (!checkboxes[i].checked) {
                allChecked = false;
                break;
            }
        }

        // If all checkboxes are checked, mark the "Select All" checkbox as checked, otherwise uncheck it
        selectAll.checked = allChecked;
    }
</script>
@endsection