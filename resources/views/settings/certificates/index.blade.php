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

  <div class="col-sm-4">
    <div class="card mb-3">
      <div class="card-header">
        <h2 style="margin-left:-5px;">Create New Certificate</h2>        
      </div>
          <form action="{{ route('certificates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 10px;">
                <label for="certificate_name">Certificate Name:</label>
                <input type="text" name="certificate_name" id="certificate_name" class="form-control" required>
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label for="student_photo">Student Photo:</label>
                <select name="student_photo" id="student_photo" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <div class="form-group" style="width: 60%; margin-right: 10px;">
                    <label for="background_image">Certificate Background:</label>
                    <input type="file" name="background_image" id="background_image" class="form-control" accept="image/*" required onchange="previewBackgroundImage(event)">
                </div>
                <div class="form-group" style="width: 40%;">
                    <label>Preview:</label>
                    <div style="border: 1px solid #ddd; padding: 10px;">
                        <img id="background_image_preview" src="#" alt="Preview will appear here" style="max-width: 100%; height: auto; display: none;" />
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: center; margin-bottom: 10px;">
                <button type="submit" class="btn btn-success">Save Certificate</button>
            </div>

        </form>


    </div>
  </div>
  <div class="col-sm-8">
    <div class="card mb-3">
      <div class="card-header">
      <h2>Certificate Management</h2>        
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
                            <img src="{{ url('storage/app/public/' . $certificate->background_image) }}" 
                                alt="Background" 
                                style="max-width: 100px; height: auto; cursor: pointer;" 
                                onclick="viewImage('{{ url('storage/app/public/' . $certificate->background_image) }}')">
                        @else
                            No Image
                        @endif
                    </td>
                    <!-- <td>{{ $certificate->student_photo ? 'Yes' : 'No' }}</td> -->
                    <td>
                          <span class="btn {{ $certificate->status ? 'btn-success' : 'btn-danger' }} btn-sm">
                              {{ $certificate->status ? 'Active' : 'Inactive' }}
                          </span>
                    </td>

                    <td>
                        <a href="{{ route('certificates.edit', $certificate->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
  

<!-- Modal for Larger View -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center; z-index: 9999;">
    <img id="modalImage" src="#" alt="Large Background Image" 
         style="max-width: 30%; height: auto; border: 5px solid white; border-radius: 10px;">
    <span style="position: absolute; top: 20px; right: 20px; color: white; font-size: 30px; cursor: pointer;" onclick="closeModal()">&#10005;</span>
</div>
</div>

<script>
    // Function to show the modal with the selected image
    function viewImage(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc; // Set the modal image src
        modal.style.display = 'flex'; // Display the modal
    }

    // Function to close the modal
    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none'; // Hide the modal
    }
</script>
</div>


<script>
    // Function to show the modal with the selected image
    function viewImage(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc; // Set the modal image src
        modal.style.display = 'flex'; // Display the modal
    }

    // Function to close the modal
    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none'; // Hide the modal
    }
</script>

<script>
    function previewBackgroundImage(event) {
        const fileInput = event.target;
        const preview = document.getElementById('background_image_preview');

        // Check if a file is selected and it is an image
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            // Once the file is loaded, update the preview image's source
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Show the image preview
            };

            reader.readAsDataURL(fileInput.files[0]); // Read the file as a data URL
        }
    }
</script>
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

<!-- Row ends -->
@endsection