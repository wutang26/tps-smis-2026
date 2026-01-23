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
    <h5>Changanua</h5>

    <div class="table-responsive">
        <form action="{{ route('attendences.storeMchanganuo', ['attendenceId' => $attendence->id]) }}" method="post">
            @csrf
            @method("POST")
            <div class="d-flex gap-2">
                <input class="form-check-input" type="radio" name="type" value="mess">
                <label for="">Mess</label>

                <input class="form-check-input" type="radio" name="type" value="sentry">
                <label for="">Sentry</label>

                <input class="form-check-input" type="radio" name="type" value="off">
                <label for="">Off</label>

                <!-- <input class="form-check-input" type="radio" name="type" value="safari">
                <label for="">Safari</label> -->
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
                                        <tr class="item">
                                            <td>
                                                <div class="form-check">
                                                    <input id="item-child" class="form-check-input" name="student_ids[]"
                                                        type="checkbox" value="{{$student->id}}" id="defaultCheck"
                                                        .{{$student->id}}>
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
                <div class="col-2" style="width: 45%; right: 1%; overflow: auto; ">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('attendences.storeMchanganuo', ['attendenceId' => $attendence->id]) }}"
                                method="POST">
                                @csrf
                                @method('POST')

                                <div id="mess">
                                    <div class="mb-4">
                                        <h4>Mess Students</h4>
                                    </div>
                                    @foreach ($mess_students as $student)
                                        <div class="form-check">
                                            <input id="item-child" class="form-check-input" name="student_ids[]" type="checkbox"
                                                checked value="{{$student->id}}" id="defaultCheck" .{{$student->id}}>
                                            <label class="form-check-label" for="defaultCheck1">{{$student->first_name}}
                                                {{$student->middle_name}} {{$student->last_name}}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="sentry" class="mt-4">
                                    <div class="mb-4">
                                        <h4>Sentry Students</h4>
                                    </div>
                                    @foreach ($sentry_students as $student)
                                        <div class="form-check">
                                            <input id="item-child" class="form-check-input" name="student_ids[]" type="checkbox"
                                                checked value="{{$student->id}}" id="defaultCheck" .{{$student->id}}>
                                            <label class="form-check-label" for="defaultCheck1">{{$student->first_name}}
                                                {{$student->middle_name}} {{$student->last_name}}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="off" class="mt-4">
                                    <div class="mb-4">
                                        <h4>Off Students</h4>
                                    </div>
                                    @foreach ($off_students as $student)
                                        <div class="form-check">
                                            <input id="item-child" class="form-check-input" name="student_ids[]" type="checkbox"
                                                checked value="{{$student->id}}" id="defaultCheck" .{{$student->id}}>
                                            <label class="form-check-label" for="defaultCheck1">{{$student->first_name}}
                                                {{$student->middle_name}} {{$student->last_name}}</label>
                                        </div>
                                    @endforeach
                                </div>

                                
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div></br>



            <!-- <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div> -->
        </form>
        <script>

            document.querySelectorAll('#item-child').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    let selectedCategoryRadio = document.querySelector('.form-check-input[name="type"]:checked');

                    if (this.checked) {
                        //let studentLabel = this.nextElementSibling; // get the sibling label
                        let categoryDiv = document.getElementById(selectedCategoryRadio.value);

                        let checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.className = 'form-check-input';
                        checkbox.name = selectedCategoryRadio.value + '_student_ids[]'
                        checkbox.value = this.value
                        checkbox.checked = true;
                        //checkbox.disabled = true


                        // Create the label element
                        let checkboxLabel = document.createElement('label');
                        checkboxLabel.className = 'form-check-label';
                        checkboxLabel.innerText = this.nextElementSibling.innerText;
                        // Create a new div for the student
                        let newStudentDiv = document.createElement('div');
                        newStudentDiv.className = 'students';
                        newStudentDiv.innerHTML = this.parentNode.innerHTML;

                        // Add the new div to the mess section
                        categoryDiv.appendChild(checkbox);
                        categoryDiv.appendChild(checkboxLabel);
                        let breakElement = document.createElement('br');
                        categoryDiv.appendChild(breakElement);

                        // Optionally remove the checkbox and label from the form
                        this.parentNode.remove();
                    }
                });
            });

        </script>

@endsection