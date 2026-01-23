@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Officer Details</h3>

    <div class="card">
        <div class="card-body">
            <p><strong>Officer ID:</strong> {{ $officer->officer_id }}</p>
            <p><strong>Service Number:</strong> {{ $officer->service_number }}</p>
            <p><strong>Full Name:</strong> {{ $officer->full_name }}</p>
            <p><strong>Rank:</strong> {{ $officer->rank }}</p>
            <p><strong>Contact:</strong> {{ $officer->contact_number ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $officer->email ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $officer->status }}</p>
        </div>
    </div>

    <a href="{{ route('officers.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
