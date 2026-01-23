@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                    <li class="breadcrumb-item active"><a href="#">Reserves Replacement</a></li>
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
    <h2>{{ $company->description }} Beats Reserves Replacement for {{ $date }}.</h2>
    <h4>Reserve : {{ $reserve->first_name }} {{ $reserve->last_name }} PLT-{{ $reserve->platoon }}</h4>
    @php
        $i = 0;
    @endphp
    <div class="card-body">
        <div class="table-outer">
            <div class="table-responsive">
                <table class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Names</th>
                            <th>Platoon</th>
                            <th width="280px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <in>

                                <td>{{ ++$i }}.</td>
                                <td>
                                    {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                </td>

                                <td>{{ $student->platoon }}</td>
                                <td>

                                    <button class="btn  btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#More{{$student->id}}">Replace</button>
                                </td>
                                <div class="modal fade" id="More{{$student->id}}" tabindex="-1"
                                    aria-labelledby="statusModalLabelMore{{$student->id}}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="statusModalLabelMore">
                                                    Replacement Reason for {{ $reserve->first_name }} {{ $reserve->last_name }}
                                                    PLT-{{ $reserve->platoon }}<br>
                                                    with {{ $student->first_name }} {{ $student->last_name }}
                                                    PLT-{{ $student->platoon }}
                                                </h5>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="{{ route('beats.replace-reserve', ['reserveId' => $reserve->id, 'studentId' => $student->id, 'date' => $date, 'beatReserveId' => $beatReserveId]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="mb-3">
                                                        <label for="replacement_reason" class="form-label">Reason</label>
                                                        <textarea class="form-control" id="" name="replacement_reason" rows="3"
                                                          placeholder="Type reason here...."  required></textarea>
                                                    </div>
                                                    @error('replacement_reason')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                    </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection