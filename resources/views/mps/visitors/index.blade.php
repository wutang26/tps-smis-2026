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
@can('mps-create')
<div style="display: flex; justify-content: flex-end; margin-right: 2px;" class="mb-2">
    <a href="{{route('visitors.create')}}"><button class="btn btn-sm btn-success">Add Student</button></a>
</div>
@endcan
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach ($mpsVisitors as $visitor)
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{$visitor->student->first_name ?? ''}} {{$visitor->student->last_name ?? ''}}</td>
                    <td>{{$visitor->names}}</td>
                    <td>{{$visitor->phone}}</td>
                    <td>{{$visitor->relationship}}</td>
                    <td>{{ $visitor->visited_at }}</td>
                    <td>{{$visitor->staff->last_name}}</td>
                    @if($visitor->created_at && $visitor->created_at->gt(now()->subHours(2)))
                    <td class="d-flex gap-2">
                        
                        @can('mps-edit')
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#statusModal{{ $visitor->id ?? ''}}">
                            Edit
                        </button>
                        @endcan
                        @can('mps-delete')
                        <form id="deleteForm-{{ $visitor->id }}" action="{{ route('visitors.destroy', $visitor) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmDelete('deleteForm-{{ $visitor->id }}', ' visitor {{ $visitor->names }} informations')">
                                Delete
                            </button>
                        </form>

                        @endcan
                        <div class="modal fade" id="statusModal{{  $visitor->id ?? '' }}" tabindex="-1"
                            aria-labelledby="statusModalLabel{{  $visitor->id ?? '' }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel{{  $student->id ?? ''}}">
                                            Visitor Details for {{ $visitor->student->first_name }}
                                            {{ $visitor->student->last_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{url('/visitors/update/' . $visitor->id)}}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <div class="mb-3">
                                                <label for="visitor_name" class="form-label">Visitor Name</label>
                                                <input class="form-control" value="{{ $visitor->names }}" type="text"
                                                    required name="visitor_name">
                                            </div>
                                            @error('visitor_name')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                            <div class="mb-3">
                                                <label for="visitor_phone" class="form-label">Visitor Phone</label>
                                                <input class="form-control" value="{{ $visitor->phone }}" type="text"
                                                    required name="visitor_phone">
                                            </div>
                                            @error('visitor_phone')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                            <div class="mb-3">
                                                <label for="visitor_relation" class="form-label">Visitor
                                                    Relation</label>
                                                <input class="form-control" value="{{ $visitor->relationship }}"
                                                    type="text" required name="visitor_relation">
                                            </div>
                                            @error('visitor_relation')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                            <div class="mb-3">
                                                <label for="visted_at" class="form-label">Visited At</label>
                                                <input class="form-control" value="{{ $visitor->visited_at }}"
                                                    type="datetime-local" required name="visited_at">
                                            </div>
                                            @error('visited_at')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                            <div style="display: flex; justify-content: flex-end; margin-right: 2px;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </td>
                    @endif
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