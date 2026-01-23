@extends('layouts.main')

@section('content')
<div class="col-sm-12">
    <!-- Admitted Patients Button -->
    <div class="d-flex gap-2 justify-content-end mb-3">
        <a href="{{ route('doctor.admitted') }}" class="btn btn-success">
            View Admitted Patients
        </a>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Approved Patients</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <script>
                    setTimeout(() => {
                        let msg = document.getElementById('successMessage');
                        if (msg) {
                            msg.style.transition = 'opacity 0.5s';
                            msg.style.opacity = '0';
                            setTimeout(() => msg.style.display = 'none', 500);
                        }
                    }, 5000);
                </script>
            @endif

            @if($patients->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Platoon</th>
                                <th>Status</th>
                                <th>Date Attended</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->student->first_name ?? '-' }}</td>
                                    <td>{{ $patient->student->last_name ?? '-' }}</td>
                                    <td>{{ $patient->platoon }}</td>
                                    <td>{{ ucfirst($patient->status) }}</td>
                                    <td>{{ $patient->created_at ?? '-' }}</td>
                                    <td>
                                        <!-- Button to open modal -->
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#patientModal{{ $patient->id }}">
                                            Enter Details
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="patientModal{{ $patient->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $patient->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('patients.saveDetails') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="student_id" value="{{ $patient->id }}">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel{{ $patient->id }}">
                                                                Enter Details for {{ $patient->student->first_name ?? '-' }} {{ $patient->student->last_name ?? '-' }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="excuse_type_id" class="form-label">Excuse Type</label>
                                                                <select name="excuse_type_id" class="form-select excuse-select" required data-patient-id="{{ $patient->id }}">
                                                                    <option value="" disabled selected>Select E.D Type</option>
                                                                    @foreach ($excuseTypes as $id => $excuseName)
                                                                        <option value="{{ $id }}">{{ $excuseName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3 referral-options d-none" id="referralOption{{ $patient->id }}">
                                                                <label class="form-label">Admitted Type</label><br>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="admitted_type" value="Internal">
                                                                    <label class="form-check-label">Internal</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="admitted_type" value="Referral">
                                                                    <label class="form-check-label">Referral</label>
                                                                </div>
                                                            </div>

                                                            <!-- <div class="mb-3">
                                                                <label class="form-label">Days of Rest</label>
                                                                <input type="number" class="form-control" name="rest_days" min="0" required>
                                                            </div> -->
                                                            <div class="mb-3 rest-days-group" id="restDaysGroup{{ $patient->id }}">
                                                              <label class="form-label">Days of Rest</label>
                                                              <input type="number" class="form-control" name="rest_days" min="0">
                                                             </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Doctor's Comment</label>
                                                                <textarea class="form-control" name="doctor_comment" rows="3" required></textarea>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center mt-3">No approved patients available at the moment.</p>
            @endif
        </div>
    </div>
</div>

<!-- SweetAlert2 -->

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Toggle Referral/Internal if 'Admitted' is selected
        document.querySelectorAll('.excuse-select').forEach(select => {
            select.addEventListener('change', function () {
                let patientId = this.getAttribute('data-patient-id');
                let referralDiv = document.getElementById('referralOption' + patientId);
                let selectedText = this.options[this.selectedIndex].text;

                if (selectedText === 'Admitted') {
                    referralDiv.classList.remove('d-none');
                } else {
                    referralDiv.classList.add('d-none');
                }
            });
        });

        // Discharge confirmation
        document.querySelectorAll('.discharge-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will discharge the patient!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, discharge'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    document.querySelectorAll('.excuse-select').forEach(select => {
    select.addEventListener('change', function () {
        let patientId = this.getAttribute('data-patient-id');
        let referralDiv = document.getElementById('referralOption' + patientId);
        let restDaysGroup = document.getElementById('restDaysGroup' + patientId);
        let selectedText = this.options[this.selectedIndex].text.trim().toLowerCase();

        if (selectedText === 'admitted' || selectedText === 'normal') {
            referralDiv.classList.remove('d-none');
            if (restDaysGroup) restDaysGroup.style.display = 'none';
        } else {
            referralDiv.classList.add('d-none');
            if (restDaysGroup) restDaysGroup.style.display = 'block';
        }
    });
});

</script>


@endsection
