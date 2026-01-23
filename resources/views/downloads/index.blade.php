@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap">
        <h2 class="fw-bold text-primary mb-0">📂 Download Center</h2>
        
        <!-- Smaller Upload Button -->
        <a href="{{ route('downloads.upload.page') }}" class="btn btn-primary btn-sm shadow-sm mt-2 mt-md-0">
            <i class="fas fa-upload me-1"></i> Upload
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Stylish Card Grid for Downloads -->
    <div class="row mt-4">
        @foreach($downloads as $download)
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body text-center">
                    @php
                        $extension = pathinfo($download->file_path, PATHINFO_EXTENSION);
                    @endphp

                    <!-- File Preview -->
                    <div class="file-preview mb-3">
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset('storage/' . $download->file_path) }}" class="img-fluid rounded" alt="Preview">
                        @elseif($extension == 'pdf')
                            <i class="fas fa-file-pdf text-danger fa-3x"></i>
                        @elseif(in_array($extension, ['mp4', 'avi', 'mov']))
                            <i class="fas fa-file-video text-info fa-3x"></i>
                        @elseif(in_array($extension, ['doc', 'docx']))
                            <i class="fas fa-file-word text-primary fa-3x"></i>
                        @elseif(in_array($extension, ['xls', 'xlsx']))
                            <i class="fas fa-file-excel text-success fa-3x"></i>
                        @else
                            <i class="fas fa-file-alt text-secondary fa-3x"></i>
                        @endif
                    </div>

                    <!-- File Details -->
                    <h5 class="fw-bold">{{ $download->title }}</h5>
                    <p class="text-muted">{{ $download->category }}</p>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center">
                        <!-- Download Button -->
                        <a href="{{ route('downloads.file', basename($download->file_path)) }}" class="btn btn-success me-2">
                            <i class="fas fa-download"></i> Download
                        </a>

                        @if(auth()->check())
                        <form action="{{ route('downloads.delete', $download->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this document?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $downloads->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
