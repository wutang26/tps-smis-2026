@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                    <li class="breadcrumb-item active"><a href="#">Reserves</a></li>
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
    <h2>{{ $company->description }} Beats Reserves for {{ $date }}</h2>
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
                        @foreach ($reserves as $reserve)
                            <tr>
                                
                                <td>{{ ++$i }}.</td>
                                <td>{{ $reserve->student->first_name }} {{ $reserve->student->middle_name }} {{ $reserve->student->last_name }}</td>
                                <td>{{ $reserve->student->platoon }}</td>
                                @if($reserve->student->beat_status != 1 )
                                <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('beats.approve-reserve', ['studentId' =>$reserve->student_id]) }}">Release</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('beats.reserve-replacement', ['reserveId' =>$reserve->student_id,'date'=>$reserve->beat_date,'beatReserveId'=>$reserve->id]) }}">Replace</a>
                                </td>
                                @elseif(is_null($reserve->replaced_student_id))
                                    <td>Released</td>
                                @else
                                    <td>Replaced</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>


@endsection