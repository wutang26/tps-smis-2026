@extends('layouts.main')

@section('style')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<style>
.back{
  border-radius: 30% !important;
}
.profile-header {
    background-image: url('/tps-smis/resources/assets/images/profile/bg-profile.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 200px;
    position: relative;
}

.profile-header img {
    position: absolute;
    bottom: -50px;
    left: 20px;
    border-radius: 50%;
    border: 5px solid white;
}

.profile-header .profile-info {
    position: absolute;
    bottom: 20px;
    left: 150px;
    color: white;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
}
</style>

@endsection
@section('content')

<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
          <div class="card-body back">
            <div class="profile-header"> 
              <img src="/tps-smis/resources/assets/images/profile/avatar.jpg" alt="Profile Picture" />
            </div>

            <div class="d-flex justify-content-end mt-3">
              <!-- <button class="btn btn-danger me-2">Edit Profile</button> -->
              <button class="btn btn-success">Active</button> 
            </div>
          </div>
        </div>
    </div>
</div>

<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body">
                    <!-- Custom tabs start -->
                    <div class="custom-tabs-container">

                      <!-- Nav tabs start -->
                      <ul class="nav nav-tabs" id="customTab2" role="tablist">
                        <li class="nav-item" role="presentation">
                          <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                            aria-controls="oneA" aria-selected="true"><i class="bi bi-person me-2"></i> My Personal
                            Details</a>
                        </li>
                      </ul>
                      <!-- Nav tabs end -->

                      

                @if (count($errors) > 0)
                  <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                  </div>
                @endif
                <form action="{{ route('students.profile_complete', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                      <!-- Tab content start -->
                      <div class="tab-content h-300">
                        <div class="tab-pane fade show active" id="oneA" role="tabpanel">

                          <!-- Row starts -->
                          <div class="row gx-4">
                            <div class="col-sm-9 col-12">
                              <div class="card border mb-3">
                                <div class="card-body">

                                  <!-- Row starts -->
                                  <div class="row gx-4">
                                    <div class="col-sm-3 col-12">
                                      <!-- Form field start -->    
                                        <div class="mb-3">
                                        <label class="form-label" for="abc4">Education Level</label>
                                        <div class="input-group">
                                        <span class="input-group-text">
                                          <i class="bi bi-person"></i>
                                        </span>
                                        <select class="form-select" id="education_level" name="education_level" aria-label="Default select example">
                                            <option selected="" disabled>-- Choose Education Level</option>
                                            <option value="std7" {{ old('education_level', 'default_value') == 'std7' ? 'selected' : '' }}>Darasa la Saba</option>
                                            <option value="4m4" {{ old('education_level', 'default_value') == '4m4' ? 'selected' : '' }}>Form Four</option>
                                            <option value="4m6" {{ old('education_level', 'default_value') == '4m6' ? 'selected' : '' }}>Form Six</option>
                                            <option value="Certificate" {{ old('education_level', 'default_value') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                            <option value="Diploma" {{ old('education_level', 'default_value') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Degree" {{ old('education_level', 'default_value') == 'Degree' ? 'selected' : '' }}>Bachelor Degree</option>
                                            <option value="Masters" {{ old('education_level', 'default_value') == 'Masters' ? 'selected' : '' }}>Masters</option>
                                            <option value="PhD" {{ old('education_level', 'default_value') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        </select>
                                        </div>
                                        </div>
                                        @error('education_level')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                      <!-- Form field end -->
                                    </div>
                                    
                                    <div class="col-sm-3 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="home_region" class="form-label">Home Region</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                          </span>
                                          <input type="text" class="form-control" id="home_region" name="home_region">
                                        </div>
                                      </div>
                                        @error('home_region')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                      <!-- Form field end -->

                                    </div>
                                    <div class="col-sm-2 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-phone"></i>
                                          </span>
                                          <input type="text" class="form-control" id="phone" name="phone">
                                        </div>

                                      </div>
                                        @error('phone')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                      <!-- Form field end -->

                                    </div>

                                    <div class="col-sm-2 col-12">
                                      <!-- Form field start -->    
                                        <div class="mb-3">
                                        <label class="form-label" for="abc4">Company</label>
                                        <div class="input-group">
                                        <span class="input-group-text">
                                          <i class="bi bi-person"></i>
                                        </span>
                                        <select class="form-select" name="company" id="abc4" required aria-label="Default select example">
                                            <option >Select company</option>
                                            <option value="HQ">HQ</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                        </div>
                                        </div>
                                        @error('company')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                      <!-- Form field end -->
                                    </div>

                                    <div class="col-sm-2 col-12">
                                      <!-- Form field start -->    
                                        <div class="mb-3">
                                        <label class="form-label" for="abc4">Platoon</label>
                                        <div class="input-group">
                                        <span class="input-group-text">
                                          <i class="bi bi-person"></i>
                                        </span>
                                        <select class="form-select" name="platoon" id="abc4" aria-label="Default select example">
                                            <option selected="">Select platoon</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                        </div>
                                        </div>
                                        @error('platoon')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                      <!-- Form field end -->
                                    </div>
                                    <div class="col-sm-12 col-12">
                                        <!-- Form field start -->
                                        <!-- <div class="m-0"> -->
                                          <!-- <label class="form-label" for="abt">About </label> -->
                                            <!-- <span class="input-group-text">
                                              <i class="bi bi-filter-circle"></i>
                                            </span> -->

                                            <div id="next-of-kin-container">
                                                <label class="form-label" for="abt">Next of Kin Details </label>
                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="margin-bottom:0px;">
                                                    <button  type="button" class="btn btn-primary btn-sm mt-2 mb-3" onclick="addNextOfKin()"><i class="fa-solid fa-floppy-disk"></i> Add Next of Kin</button>
                                                </div>
                                                <div class="next-of-kin">

<div class="row">

    <div class="col-sm-4 col-12">

      <!-- Form field start -->
      <div class="mb-3">
      <label for="next_kin_names_${index }" class="form-label">Next of kin Name</label>
      <div class="input-group">
      <span class="input-group-text">
      <i class="bi bi-person"></i>
      </span>
      <input type="text" class="form-control" name="next_of_kin[${index }][name]">
      </div>

      </div>
      <!-- Form field end -->

    </div>
    <div class="col-sm-2 col-12">

      <!-- Form field start -->
      <div class="mb-3">
      <label for="next_kin_phone_${index }" class="form-label">Next of kin Phone</label>
      <div class="input-group">
      <span class="input-group-text">
      <i class="bi bi-phone"></i>
      </span>
      <input type="text" class="form-control" name="next_of_kin[${index }][phone]">
      </div>

      </div>
      <!-- Form field end -->

    </div>
    
    <div class="col-sm-3 col-12">

      <!-- Form field start -->
      <div class="mb-3">
      <label for="next_kin_relationship_${index }" class="form-label">Next of kin Relationship</label>
      <div class="input-group">
      <span class="input-group-text">
      <i class="bi bi-about"></i>
      </span>
      <input type="text" class="form-control" name="next_of_kin[${index }][relationship]">
      </div>

      </div>
      <!-- Form field end -->

    </div>

    
    <div class="col-sm-2 col-12">

      <!-- Form field start -->
      <div class="mb-3">
      <label for="next_kin_address_${index }" class="form-label">Next of kin Address</label>
      <div class="input-group">
      <span class="input-group-text">
      <i class="bi bi-address"></i>
      </span>
      <input type="text" class="form-control" name="next_of_kin[${index }][address]">
      </div>

      </div>
      <!-- Form field end -->

    </div>
    
    <div class="col-sm-1 col-12">
      <div class="mb-3">
          <label for="button" class="form-label">Action</label>
          <button type="button"  class="form-control" onclick="removeNextOfKin(this)" style="display:inline">Remove</button>
      </div>
    </div>
</div>
                                              </div>

                                        <!-- Form field end -->
                                    </div>

                                  </div>
                                  <!-- Row ends -->

                                </div>
                              </div>
                              </div>
                              </div>
                            
                            <div class="col-sm-3 col-12">
                              <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="photo">Upload Photo</label>
                                        <input type="file" class="form-control" name="photo" id="photo" onchange="previewAndResizePhoto()">
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <img id="photo-preview" src="{{ $student->photo ? Storage::url($student->photo) : '' }}" alt="Photo Preview" width="150" height="180">
                                    </div>
                                    @error('photo')
                                        <div class="error">{{ $message }}</div>
                                    @enderror

                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Row ends -->
                           
                          <div class="col-xs-12 col-sm-12 col-md-12 text-center" id="btnSubmit" style="margin-bottom:0px;">
                                      <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Complete</button>
                                  </div>

                        </div>
                      </div>
                      <!-- Tab content end -->
                       
            </form>

                    </div>
                    <!-- Custom tabs end -->
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- Row ends -->
<script>
    function previewAndResizePhoto() {
        const fileInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photo-preview');
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = new Image();
            img.src = e.target.result;
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                const maxDimension = 300; // Resize to 300x300 pixels
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxDimension) {
                        height *= maxDimension / width;
                        width = maxDimension;
                    }
                } else {
                    if (height > maxDimension) {
                        width *= maxDimension / height;
                        height = maxDimension;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);

                photoPreview.src = canvas.toDataURL('image/jpeg');
            };
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

<script>
    function addNextOfKin() {
        const container = document.getElementById('next-of-kin-container');
        const index = container.children.length;
        const nextOfKinHtml = `


            <div class="form-control next-of-kin">

            <div class="row gx-4">

                <div class="col-sm-4 col-12">

                  <!-- Form field start -->
                  <div class="mb-3">
                  <label for="next_kin_names_${index }" class="form-label">Next of kin Name</label>
                  <div class="input-group">
                  <span class="input-group-text">
                  <i class="bi bi-phone"></i>
                  </span>
                  <input type="text" class="form-control" name="next_of_kin[${index }][name]">
                  </div>

                  </div>
                  <!-- Form field end -->

                </div>
                <div class="col-sm-2 col-12">

                  <!-- Form field start -->
                  <div class="mb-3">
                  <label for="next_kin_phone_${index }" class="form-label">Next of kin Phone</label>
                  <div class="input-group">
                  <span class="input-group-text">
                  <i class="bi bi-phone"></i>
                  </span>
                  <input type="text" class="form-control" name="next_of_kin[${index }][phone]">
                  </div>

                  </div>
                  <!-- Form field end -->

                </div>
                
                <div class="col-sm-3 col-12">

                  <!-- Form field start -->
                  <div class="mb-3">
                  <label for="next_kin_relationship_${index }" class="form-label">Next of kin Relationship</label>
                  <div class="input-group">
                  <span class="input-group-text">
                  <i class="bi bi-phone"></i>
                  </span>
                  <input type="text" class="form-control" name="next_of_kin[${index }][relationship]">
                  </div>

                  </div>
                  <!-- Form field end -->

                </div>

                
                <div class="col-sm-2 col-12">

                  <!-- Form field start -->
                  <div class="mb-3">
                  <label for="next_kin_address_${index }" class="form-label">Next of kin Address</label>
                  <div class="input-group">
                  <span class="input-group-text">
                  <i class="bi bi-phone"></i>
                  </span>
                  <input type="text" class="form-control" name="next_of_kin[${index }][address]">
                  </div>

                  </div>
                  <!-- Form field end -->

                </div>
                
                <div class="col-sm-1 col-12">
                  <div class="mb-3">
                      <label for="button" class="form-label">Action</label>
                      <button type="button"  class="form-control" onclick="removeNextOfKin(this)" style="display:inline">Remove</button>
                  </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', nextOfKinHtml);
    }

    function removeNextOfKin(button) {
        button.closest('.next-of-kin').remove();
    }
</script>
 @endsection
