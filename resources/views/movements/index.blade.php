@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Weapon Movements</h3>
    <a href="{{ route('movements.create') }}" class="btn btn-primary mb-3">Record Movement</a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Movement ID</th>
                <th>Weapon</th>
                <th>Type</th>
                <th>Issued To</th>
                <th>Issued At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $m)
            <tr>
                <td>{{ $m->id }}</td>
                <td>{{ $m->movement_id }}</td>
                <td>{{ $m->weapon->make_model ?? 'N/A' }}</td>
                <td>{{ $m->movement_type }}</td>
                <td>{{ $m->issuedTo->full_name ?? 'N/A' }}</td>
                <td>{{ $m->issue_date_time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
