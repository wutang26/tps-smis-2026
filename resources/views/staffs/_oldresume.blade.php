@extends('layouts.main')

@section('style')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        background-color: #f9f9f9;
    }
    .card {
        border: none;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee.
    }
    .header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px.
    }
    .header p {
        color: #7f8c8d;
        margin-bottom: 2px.
    }
    .photo img {
        border-radius: 8px.
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15).
    }
    .section {
        margin-top: 30px.
        padding: 15px 20px.
        border-left: 5px solid #2c3e50.
        background-color: #fdfdfd.
        border-radius: 8px.
    }
    .section h2 {
        font-size: 1.2rem.
        font-weight: bold.
        color: #2c3e50.
        border-bottom: 1px solid #eee.
        padding-bottom: 5px.
        margin-bottom: 15px.
    }
    .section p {
        margin-bottom: 8px.
        font-size: 0.95rem.
    }
    .section p strong {
        color: #34495e.
    }
</style>
@endsection

@section('content')
<div id="printArea">
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4 p-4">
            <div class="card-body">

                <!-- Header Section -->
                <div class="header">
                    <h1>{{ $staff->firstName ?? 'N/A' }} {{ $staff->middleName ?? '' }} {{ $staff->lastName ?? '' }}</h1>
                    <p>{{ $staff->email ?? 'N/A' }} | {{ $staff->phoneNumber ?? 'N/A' }}</p>
                    <p>{{ $staff->currentAddress ?? 'N/A' }}</p>
                    @if ($staff->photo)
                        <div class="photo mt-3">
                            <img src="{{ asset($staff->photo) }}" alt="Photo" height="100">
                        </div>
                    @endif
                </div>

                <!-- Personal Details Section -->
                <div class="section">
                    <h2>Personal Details</h2>
                    <p><strong>Date of Birth:</strong> {{ $staff->DoB ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ $staff->gender ?? 'N/A' }}</p>
                    <p><strong>Marital Status:</strong> {{ $staff->maritalStatus ?? 'N/A' }}</p>
                    <p><strong>Religion:</strong> {{ $staff->religion ?? 'N/A' }}</p>
                    <p><strong>Tribe:</strong> {{ $staff->tribe ?? 'N/A' }}</p>
                </div>

                <!-- Professional Details Section -->
                <div class="section">
                    <h2>Professional Details</h2>
                    <p><strong>Rank:</strong> {{ $staff->rank ?? 'N/A' }}</p>
                    <p><strong>Department:</strong> {{ $staff->department->name ?? 'N/A' }}</p>
                    <p><strong>Designation:</strong> {{ $staff->designation ?? 'N/A' }}</p>
                    <p><strong>Joining Date:</strong> {{ $staff->joiningDate ?? 'N/A' }}</p>
                    <p><strong>Location:</strong> {{ $staff->location ?? 'N/A' }}</p>
                </div>

                <!-- Work Experience Section -->
                <div class="section">
                    <h2>Work Experience</h2>
                    @if (!empty($workExperiences) && count($workExperiences) > 0)
                        @foreach ($workExperiences as $experience)
                            <p><strong>Institution:</strong> {{ $experience->institution ?? 'N/A' }}</p>
                            <p><strong>Job Title:</strong> {{ $experience->job_title ?? 'N/A' }}</p>
                            <p><strong>Position:</strong> {{ $experience->position ?? 'N/A' }}</p>
                            <p><strong>Start Date:</strong> {{ $experience->start_date ?? 'N/A' }}</p>
                            <p><strong>End Date:</strong> {{ $experience->end_date ?? 'Current' }}</p>
                            <p><strong>Duties:</strong> {{ $experience->duties ?? 'N/A' }}</p>
                        @endforeach
                    @else
                        <p>No work experience available.</p>
                    @endif
                </div>

                <!-- Computer Literacy Section -->
                <div class="section">
                    <h2>Computer Literacy</h2>
                    @if (!empty($computerLiteracies) && count($computerLiteracies) > 0)
                        @foreach ($computerLiteracies as $literacy)
                            <p><strong>Skill:</strong> {{ $literacy->skill ?? 'N/A' }}</p>
                            <p><strong>Proficiency:</strong> {{ $literacy->proficiency ?? 'N/A' }}</p>
                            @if ($literacy->certifications)
                                <p><strong>Certification:</strong> {{ $literacy->certifications }}</p>
                            @endif
                        @endforeach
                    @else
                        <p>No computer literacy information available.</p>
                    @endif
                </div>

                <!-- Language Proficiency Section -->
                <div class="section">
                    <h2>Language Proficiency</h2>
                    @if (!empty($languageProficiencies) && count($languageProficiencies) > 0)
                        @foreach ($languageProficiencies as $language)
                            <p><strong>Language:</strong> {{ $language->language ?? 'N/A' }}</p>
                            <p><strong>Speak:</strong> {{ $language->speak ?? 'N/A' }}</p>
                            <p><strong>Write:</strong> {{ $language->write ?? 'N/A' }}</p>
                            <p><strong>Read:</strong> {{ $language->read ?? 'N/A' }}</p>
                        @endforeach
                    @else
                        <p>No language proficiency information available.</p>
                    @endif
                </div>

                <!-- Training and Workshops Section -->
                <div class="section">
                    <h2>Training and Workshops</h2>
                    @if (!empty($trainingsAndWorkshops) && count($trainingsAndWorkshops) > 0)
                        @foreach ($trainingsAndWorkshops as $training)
                            <p><strong>Training Name:</strong> {{ $training->training_name ?? 'N/A' }}</p>
                            <p><strong>Description:</strong> {{ $training->training_description ?? 'N/A' }}</p>
                            <p><strong>Institution:</strong> {{ $training->institution ?? 'N/A' }}</p>
                            <p><strong>Start Date:</strong> {{ $training->start_date ?? 'N/A' }}</p>
                            <p><strong>End Date:</strong> {{ $training->end_date ?? 'N/A' }}</p>
                            @if ($training->certificate)
                                <p><strong>Certificate:</strong> <a href="{{ Storage::url($training->certificate) }}" target="_blank">View Certificate</a></p>
                            @endif
                        @endforeach
                    @else
                        <p>No training and workshop information available.</p>
                    @endif
                </div>

                <!-- Referees Section -->
                <div class="section">
                    <h2>Referees</h2>
                    @if (!empty($referees) && count($referees) > 0)
                        @foreach ($referees as $referee)
                            <p><strong>Name:</strong> {{ $referee->referee_fullname ?? 'N/A' }}</p>
                            <p><strong>Title:</strong> {{ $referee->title ?? 'N/A' }}</p>
                            <p><strong>Organization:</strong> {{ $referee->organization ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $referee->email_address ?? 'N/A' }}</p>
                            <p><strong>Phone Number:</strong> {{ $referee->phone_number ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $referee->address ?? 'N/A' }}</p>
                        @endforeach
                    @else
                        <p>No referee information available.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<div class="text-end">
    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">← Back</a>
    <button class="btn btn-primary btn-print" onclick="printDiv()">Print / Export to PDF</button>
</div>
</div>
@endsection

@section('scripts')
<script>
    function printDiv() {
        const printContents = document.getElementById('printArea').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();

        setTimeout(() => {
            document.body.innerHTML = originalContents;
            location.reload();
        }, 1000);
    }
</script>
@endsection
