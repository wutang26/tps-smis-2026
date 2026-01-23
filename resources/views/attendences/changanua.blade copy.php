@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/attendences/">Today Attendence</a></li>
                    <li class="breadcrumb-item active"><a href="#">Mchanganuo</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    <h5>Please check absent students.</h5>

    <div class="table-responsive">
        <form action="{{ route('attendences.storeMchanganuo', ['attendenceId' => $attendence->id]) }}" method="post">
            @csrf
            @method("POST")
            <div class="d-flex gap-2">
                <label for="">Select category</label>
                <select style="width: 10%;" name="type" id="" class="form-control">
                    <option value="" disabled selected>select</option>
                    <option value="mess">Mess</option>
                    <option value="sentry">Sentry</option>
                    <option value="off">Off</option>
                    <option value="safari">Safari</option>
                </select>
            </div>

            <div class="row d-flex" style="width: 100%; position: relative;">
                <div class="col-2" style="width: 40%">
                    <div class="card">
                        <div class="card-body">
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
                                            <input class="form-check-input" name="student_ids[]" type="checkbox"
                                                value="{{$student->id}}" id="defaultCheck" .{{$student->id}}>
                                            <label class="form-check-label" for="defaultCheck1">{{$student->first_name}}
                                                {{$student->middle_name}} {{$student->last_name}}</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
                <div class="col-2" style="width: 45%; position: fixed; right: 1%;">
                <div class="card">
                <div class="card-body">
                        <div id="mess">
                            <h4>Mess Students</h4>
                        </div>

                        <div id="sentry" class="mt-4">
                            <h4>Sentry Students</h4>
                        </div>

                        <div id="off" class="mt-4">
                            <h4>Off Students</h4>
                        </div>

                        <div id="safari" class="mt-4">
                            <h4>Safari Students</h4>
                        </div>
                    </div>
                    </div>
                </div>
            </div></br>



            <div class="d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <script>
            function moveItem(){
                
            }
        document.getElementById('moveItemBtn').addEventListener('click', function() {
            // Get the checkbox item to move (Item 1 in this case)
            const itemToMove = document.getElementById('item1').parentElement;  // Get the parent div of item1

            // Get the destination div
            const destinationDiv = document.getElementById('destination-div');

            // Append the item to the destination div
            destinationDiv.appendChild(itemToMove);
        });
    </script>

@endsection