@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-rms/smis/">MPS</a></li>
                    <li class="breadcrumb-item active"><a href="/tps-smis/">Visitors</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection

@section('content')
@include('layouts.sweet_alerts.index')

    <div style="display: flex; justify-content: flex-end; margin-right: 2px;">
        <a href="{{route('visitors.create')}}"><button class="btn btn-sm btn-success">Add Student</button></a>
    </div>
    @if(isset($mpsVisitors))
        @if ($mpsVisitors->isNotEmpty())
            <div class="table-outer">
                <div class="table-responsive">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Visitor</th>
                                <th>Phone</th>
                                <th>Relation</th>
                                <th>Visted At</th>
                                <th>Welcomed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php        $i = 0; ?>
                            @foreach ($mpsVisitors as $visitor)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$visitor->student->first_name ?? ''}} {{$visitor->student->last_name ?? ''}}</td>
                                <td>{{$visitor->names}}</td>
                                <td>{{$visitor->phone}}</td>
                                <td>{{$visitor->relationship}}</td>
                                <td>{{ $visitor->visited_at }}</td>
                                <td>{{$visitor->staff->last_name}}</td>
                                
                                
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
@endsection