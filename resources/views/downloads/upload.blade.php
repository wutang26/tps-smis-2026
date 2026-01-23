@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary">📤 Upload Document</h2>

    <a href="{{ route('downloads.index') }}" class="btn btn-secondary my-3">
        <i class="fas fa-arrow-left"></i> Back to Download Center
    </a>

    <div class="card shadow-lg border-0 rounded-lg p-4">
        <form action="{{ route('downloads.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Title</label>
                <input type="text" name="title" class="form-control rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Category</label>
                <input type="text" name="category" class="form-control rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Choose File</label>
                <input type="file" name="file" class="form-control rounded-pill" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-upload"></i> Upload Now
            </button>
        </form>
    </div>
</div>
@endsection
