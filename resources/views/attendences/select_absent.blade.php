@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/attendences/">Today Attendence</a></li>
                    <li class="breadcrumb-item"><a href="#">Students</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
<h5>Please check absent students.</h5>
<div class="table-responsive">
    <form action="{{url('attendences/store-absents/' . $attendence_id.'/'.$date)}}" method="post">
        @csrf
        @method("POST")
        <table class="table table-striped truncate m-0">
            <thead>
                <tr>
                    <th>Names</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" @for ($i = 0; $i < count($absent_student_ids); ++$i)
                                @if($absent_student_ids[$i] == $student->id) checked @endif @endfor name="student_ids[]"
                                type="checkbox" value="{{$student->id}}" id="defaultCheck" .{{$student->id}}>
                            <label class="form-check-label" for="defaultCheck1">{{$student->first_name}}
                                {{$student->middle_name}} {{$student->last_name}}</label>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

    <script>
        // JavaScript function to toggle the state of checkboxes
        function toggleSelectAll(source) {
            // Get all checkboxes with the name "item"
            var checkboxes = document.getElementsByName('item');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked; // Set each checkbox's checked state to match the "Select All" checkbox
            }
        }
    </script>
    @endsection