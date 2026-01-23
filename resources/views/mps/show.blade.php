@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/">MPS</a></li>
                <li class="breadcrumb-item active"><a href="/tps-smis/">Lock Up</a></li>
                @if (isset($scrumbName))
                <li class="breadcrumb-item active"><a href="/tps-smis/">{{ $scrumbName }}</a></li>
                @endif
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
@include('layouts.sweet_alerts.index')


@if(isset($mpsStudents))
    @if ($mpsStudents->isNotEmpty())
        <div class="table-outer">
            <div class="table-responsive">
                <table class="table table-striped m-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Days</th>
                            <th>Arested at</th>
                            <th>Released at</th>
                            <th>Imprisoned By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php        $i = 0; ?>
                        @foreach ($mpsStudents as $student)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$student->student->first_name ?? ''}} {{$student->student->last_name ?? ''}}</td>
                                <td>{{$student->days?? '-'}}</td>
                                <td>{{$student->arrested_at}}</td>
                                <td>
                                    @if (!$student->released_at)
                                        Not Released
                                    @else
                                        {{$student->released_at}}
                                    @endif
                                </td>
                                <td>{{$student->staff->name ?? '-'}}</td>
                                <td class="d-flex gap-2">
                                    <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $student->id ?? ''}}">
                                        More
                                    </button>
                                </td>
                                <td>

                                    <div class="modal fade" id="statusModal{{  $student->id ?? '' }}" tabindex="-1"
                                        aria-labelledby="statusModalLabel{{  $student->id ?? '' }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{  $student->id ?? ''}}">
                                                        More Details
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Name: {{ $student->student->first_name }} {{ $student->student->last_name }}</h5>
                                                    <h5>Company: {{ $student->student->company->name ?? ''}} - {{ $student->student->platoon ?? ''}}</h5>
                                                   <h5>Description</h5> <p>{{$student->description}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit MPS information -->
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
     No records founds.
    @endif
@endif
@include('layouts.sweet_alerts.confirm_action')
@endsection