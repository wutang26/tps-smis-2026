@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Officers List</h3>
    <a href="{{ route('officers.create') }}" class="btn btn-primary mb-3">Add Officer</a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Officer ID</th>
                <th>Full Name</th>
                <th>Rank</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($officers as $officer)
            <tr>
                <td>{{ $officer->id }}</td>
                <td>{{ $officer->officer_id }}</td>
                <td>{{ $officer->full_name }}</td>
                <td>{{ $officer->rank }}</td>
                <td>{{ $officer->status }}</td>
                <td>
                    <a href="{{ route('officers.edit', $officer) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('officers.show', $officer) }}" class="btn btn-info btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
