@extends('layouts.main')

@section('style')
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"> -->
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
@include('layouts.sweet_alerts.index')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
          <div class="card-body back">
            <div class="profile-header"> 
              <img src="/tps-smis/resources/assets/images/profile/avatar.jpg" alt="Profile Picture" />
            </div>

            <div class="d-flex justify-content-end mt-3 gap-2">
            <a href="{{ route('staffs.resume', $staff->id) }}" class="btn btn-primary me-2">Curriculumn Vitae</a>
            <button class="btn btn-danger me-2">Edit Profile</button>
            <form id="changeStatusForm"  action="{{ route('staff.change_status') }}" method="post">
              @csrf
              <select 
    name="status" 
    class="form-control bg-success text-white" 
    onchange="confirm('changeStatusForm', 'Change Status', 'Status', 'Change', '{{ $staff->status }}')">
    @php
        $statuses = ['active', 'leave', 'safari', 'secondment'];
    @endphp
    @foreach ($statuses as $status)
        <option value="{{ $status }}" {{ $staff->status === $status ? 'selected' : '' }}>
            {{ ucfirst($status) }}
        </option>
    @endforeach
</select>


                <input type="text" name="staff_id" value="{{ $staff->id }}" hidden>
            </form>
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
                        <li class="nav-item" role="presentation">
                          <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab"
                            aria-controls="twoA" aria-selected="false"><i
                              class="bi bi-info-circle me-2"></i>My Attendances</a>
                        </li>
                        <li class="nav-item" role="presentation">
                          <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab"
                            aria-controls="threeA" aria-selected="false"><i
                              class="bi bi-credit-card-2-front me-2"></i>My Leave(s)</a>
                        </li>
                        <li class="nav-item" role="presentation">
                          <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab"
                            aria-controls="fourA" aria-selected="false"><i class="bi bi-eye-slash me-2"></i>Change
                            Password</a>
                        </li>
                      </ul>
                      <!-- Nav tabs end -->

                      <!-- Tab content start -->
                      <div class="tab-content h-300">
                        <div class="tab-pane fade show active" id="oneA" role="tabpanel">

                          <!-- Row starts -->
                          <div class="row gx-4">
                            <div class="col-sm-12 col-12">
                              <div class="card border mb-3">
                                <div class="card-body">

                                  <!-- Row starts -->
                                  <div class="row gx-4">
                                    <div class="col-sm-2 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="forceNumber" class="form-label">Force Number</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                          </span>
                                          <input type="text" class="form-control" id="forceNumber" value="{{$staff->forceNumber ?? ''}}" Disabled>
                                        </div>
                                      </div>
                                      <!-- Form field end -->

                                    </div>
                                    
                                    <div class="col-sm-3 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                          </span>
                                          <input type="text" class="form-control" id="fullName" value="{{$staff->firstName ?? ''}} {{$staff->middleName ?? ''}} {{$staff->lastName ?? ''}}" Disabled>
                                        </div>
                                      </div>
                                      <!-- Form field end -->

                                    </div>

                                    <div class="col-sm-3 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="yourEmail" class="form-label">Email</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                          </span>
                                          <input type="email" class="form-control" id="yourEmail" value="{{$staff->email}}" Disabled>
                                        </div>
                                      </div>
                                      <!-- Form field end -->

                                    </div>
                                    <div class="col-sm-2 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="contactNumber" class="form-label">Contact</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-phone"></i>
                                          </span>
                                          <input type="text" class="form-control" id="contactNumber" value="{{$staff->phoneNumber ?? ''}}" Disabled>
                                        </div>

                                      </div>
                                      <!-- Form field end -->

                                    </div>
                                    <div class="col-sm-2 col-12">

                                      <!-- Form field start -->
                                      <div class="mb-3">
                                        <label for="birthDay" class="form-label">Date of Birth</label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-calendar4"></i>
                                          </span>
                                          <input type="text" class="form-control" id="birthDay"  value="{{$staff->DoB ?? ''}}" Disabled>
                                        </div>
                                      </div>
                                      <!-- Form field end -->

                                    </div>
                                    <div class="col-12">

                                      <!-- Form field start -->
                                      <div class="m-0">
                                        <label class="form-label" for="abt">About </label>
                                        <div class="input-group">
                                          <span class="input-group-text">
                                            <i class="bi bi-filter-circle"></i>
                                          </span>
                                          <textarea class="form-control" id="abt" rows="4" Disabled> Hey, blah blah</textarea>
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
                          <!-- Row ends -->

                        </div>
                        <div class="tab-pane fade" id="twoA" role="tabpanel">

                          <!-- Row starts -->
                          <div class="row gx-5 align-items-center">
                            <div class="col-sm-4 col-12">
                              <div class="p-3">
                                <img src="/tps-smis/resources/assets/images/notifications.svg" alt="Notifications" class="img-fluid">
                              </div>
                            </div>
                            <div class="col-sm-4 col-12">

                              <!-- List 2 group start -->
                             
                              <!-- List 2 group end -->

                            </div>

                          </div>
                          <!-- Row ends -->

                        </div>
                        <div class="tab-pane fade" id="threeA" role="tabpanel">

                          <!-- Row starts -->
                          <div class="row gx-4">
                            <div class="col-12">

                              <!-- List 3 group start -->
                             
                              <!-- List 3 group end -->

                            </div>
                          </div>
                          <!-- Row ends -->

                        </div>
                        <div class="tab-pane fade" id="fourA" role="tabpanel">

                          <!-- Row starts -->
                          <div class="row align-items-end">
                            <div class="col-xl-4 col-sm-6 col-12">
                              <div class="p-3">
                                <img src="/tps-smis/resources/assets/images/login.svg" alt="Contact Us" class="img-fluid" width="300" height="320">
                              </div>
                            </div>
                            <div class="col-sm-4 col-12">
                              <div class="card border mb-3">
                                <div class="card-body">

                                  <div class="mb-3">
                                    <label class="form-label" for="currentPwd">Current password <span
                                        class="text-danger">*</span></label>
                                    <div class="input-group">
                                      <input type="password" id="currentPwd" placeholder="Enter Current password"
                                        class="form-control">
                                      <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-eye text-black"></i>
                                      </button>
                                    </div>
                                  </div>

                                  <div class="mb-3">
                                    <label class="form-label" for="newPwd">New password <span
                                        class="text-danger">*</span></label>
                                    <div class="input-group">
                                      <input type="password" id="newPwd" class="form-control"
                                        placeholder="Your password must be 8-20 characters long.">
                                      <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-eye text-black"></i>
                                      </button>
                                    </div>
                                  </div>

                                  <div class="mb-3">
                                    <label class="form-label" for="confNewPwd">Confirm new password <span
                                        class="text-danger">*</span></label>
                                    <div class="input-group">
                                      <input type="password" id="confNewPwd" placeholder="Confirm new password"
                                        class="form-control">
                                      <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-eye text-black"></i>
                                      </button>
                                    </div>
                                  </div>

                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Row ends -->

                        </div>
                      </div>
                      <!-- Tab content end -->

                    </div>
                    <!-- Custom tabs end -->

                    <!-- Buttons start -->
                    <!-- <div class="d-flex gap-2 justify-content-end">
                      <button type="button" class="btn btn-outline-dark">
                        Cancel
                      </button>
                      <button type="button" class="btn btn-primary">
                        Update
                      </button>
                    </div> -->
                    <!-- Buttons end -->

                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->
<script>
  function confirm(formId, itemTitle, message, action, currentStatus) {
    // Determine label and whether to show description
    const isActive = currentStatus === 'active';
    const dateLabel = isActive ? 'Start Date' : 'End Date';
    const dateId = isActive ? 'swal-start-date' : 'swal-end-date';

    Swal.fire({
      title: itemTitle,
      html: `
        <p style="margin-bottom: 15px; font-size: 14px; color: #555;">
          Are you sure you want to <strong>${action}</strong> ${message}?
        </p>
        <div style="display: flex; flex-direction: column; gap: 12px; text-align: left;">
          ${isActive ? `
            <label for="swal-description" style="font-weight: 600; color: #333;">Description</label>
            <textarea id="swal-description" class="swal-input" placeholder="Enter description"></textarea>
          ` : ''}

          <label for="${dateId}" style="font-weight: 600; color: #333;">${dateLabel}</label>
          <input type="date" id="${dateId}" class="swal-input" value="${new Date().toISOString().split('T')[0]}"/>
        </div>
      `,
      icon: 'warning',
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Yes, ' + action,
      cancelButtonText: 'No, cancel!',
      customClass: {
        popup: 'swal-popup-custom',
        confirmButton: 'swal-btn-confirm',
        cancelButton: 'swal-btn-cancel'
      },
      preConfirm: () => {
        if (isActive) {
          const description = document.getElementById('swal-description').value.trim();
          if (!description) {
            Swal.showValidationMessage('Description is required!');
            return false;
          }
        }
        const dateValue = document.getElementById(dateId).value;
        if (!dateValue) {
          Swal.showValidationMessage(`${dateLabel} is required!`);
          return false;
        }

        return { description: isActive ? document.getElementById('swal-description').value.trim() : null, dateValue };
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById(formId);

        if (isActive) {
          const descInput = document.createElement('input');
          descInput.type = 'hidden';
          descInput.name = 'description';
          descInput.value = result.value.description;
          form.appendChild(descInput);
        }

        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = isActive ? 'start_date' : 'end_date';
        dateInput.value = result.value.dateValue;
        form.appendChild(dateInput);

        form.submit();
      }
    });
  }
</script>


<style>
  /* SweetAlert2 custom styles */
  .swal-popup-custom {
    border-radius: 14px;
    font-family: 'Segoe UI', sans-serif;
    padding: 25px 25px 20px 25px;
    max-width: 480px;
  }

  .swal-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: inset 0 1px 4px rgba(0,0,0,0.1);
    font-size: 14px;
    transition: all 0.25s ease;
  }

  .swal-input:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 8px rgba(76, 175, 80, 0.4);
  }

  .swal-btn-confirm {
    background-color: #4CAF50 !important;
    color: white !important;
    border-radius: 10px !important;
    padding: 12px 25px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    transition: all 0.2s ease;
  }

  .swal-btn-confirm:hover {
    background-color: #45a049 !important;
    transform: translateY(-1px);
  }

  .swal-btn-cancel {
    background-color: #f44336 !important;
    color: white !important;
    border-radius: 10px !important;
    padding: 12px 25px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    transition: all 0.2s ease;
  }

  .swal-btn-cancel:hover {
    background-color: #e53935 !important;
    transform: translateY(-1px);
  }

  /* Placeholder styling */
  .swal-input::placeholder {
    color: #aaa;
  }
</style>



 @endsection