@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Certificates</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Certificate Setup</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
<!-- Row starts -->
<style>
  .form-group {
        padding: 10px; /* Adds padding inside all form fields */
    }
</style>
<div class="row gx-4">
  <div class="col-sm-4">
    <div class="card mb-3">
      <div class="card-header">
        <h2 style="margin-left:-5px;">Create New Certificate</h2>
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <script>
            // Auto-dismiss success and error messages after 5 seconds
            setTimeout(() => {
                const successMessage = document.getElementById('success-message');
                const errorMessage = document.getElementById('error-message');

                if (successMessage) {
                    successMessage.style.display = 'none'; // Hide success message
                }

                if (errorMessage) {
                    errorMessage.style.display = 'none'; // Hide error message
                }
            }, 5000); // 5000 milliseconds = 5 seconds
        </script>

        
      </div>
        <form action="{{ route('certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="certificate_name">Certificate Name:</label>
                <input type="text" name="certificate_name" id="certificate_name" class="form-control" value="{{ old('certificate_name', $certificate->certificate_name) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $certificate->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="student_photo">Student Photo:</label>
                <select name="student_photo" id="student_photo" class="form-control" required>
                    <option value="1" {{ $certificate->student_photo ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$certificate->student_photo ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" {{ $certificate->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$certificate->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <div class="form-group" style="width: 60%; margin-right: 10px;">
                    <label for="background_image">Certificate Background:</label>
                    <input type="file" name="background_image" id="background_image" class="form-control" accept="image/*" onchange="previewBackgroundImage(event)">
                </div>
                <div class="form-group" style="width: 40%;">
                    <label>Current:</label>
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img id="background_image_preview" 
                            src="{{ $certificate->background_image ? asset('storage/app/public/' . $certificate->background_image) : '#' }}" 
                            alt="Background" 
                            style="max-width: 100%; height: auto; display: {{ $certificate->background_image ? 'block' : 'none' }};">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: center; margin-bottom: 10px;">
                <button type="submit" class="btn btn-success">Update Certificate</button>
            </div>
        </form>

    </div>
  </div>
  <div class="col-sm-8">
    <div class="card mb-3">
      <div class="card-header">
      <h2>Certificate Management</h2>
        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
      </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/No</th>
                    <th>Certificate Name</th>
                    <th>Description</th>
                    <th>Background Photo</th>
                    <!-- <th>Student Photo</th> -->
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @php
              $i=1;
            @endphp
            @foreach ($certificates as $certificate)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $certificate->certificate_name }}</td>
                    <td>{{ $certificate->description }}</td>
                    <td>
                        @if ($certificate->background_image)
                            <img src="{{ url('storage/app/public/' . $certificate->background_image) }}" alt="Background" style="max-width: 100px; height: auto;">
                        @else
                            No Image
                        @endif
                    </td>
                    <!-- <td>{{ $certificate->student_photo ? 'Yes' : 'No' }}</td> -->
                    <td>{{ $certificate->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('certificates.edit', $certificate->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                
            @php
              $i++;
            @endphp
            @endforeach
            </tbody>
        </table>

    </div>
  </div>
</div>

<script>
    function previewBackgroundImage(event) {
        const fileInput = event.target; // The file input element
        const previewImage = document.getElementById('background_image_preview'); // The preview image element

        // Check if a file is selected
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            // Load the selected file and set it as the preview image
            reader.onload = function(e) {
                previewImage.src = e.target.result; // Set the preview image's src attribute
                previewImage.style.display = 'block'; // Ensure the image is visible
            };

            reader.readAsDataURL(fileInput.files[0]); // Read the file as a data URL
        }
    }
</script>

<!-- Row ends -->
@endsection