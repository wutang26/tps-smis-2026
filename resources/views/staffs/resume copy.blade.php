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
        border-bottom: 2px solid #eee;
    }

    .header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .header p {
        color: #7f8c8d;
        margin-bottom: 2px;
    }

    .photo img {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .section {
        margin-top: 30px;
        padding: 15px 20px;
        border-left: 5px solid #2c3e50;
        background-color: #fdfdfd;
        border-radius: 8px;
    }

    .section h2 {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    .section p {
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .section p strong {
        color: #34495e;
    }

    @media only screen and (min-width: 576px) {
        #pfno {
            margin-left: 12.5% !important;
            background-color: red;
        }
    }

    @media only screen and (max-width: 600px) {
        .abcd {
            font-size: 15px !important;
        }
    }
</style>
@endsection

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Staffs</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Staff Curriculum Vitae (CV)</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection


@section('content')
<div id="printArea">
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4 p-4">
            <div class="card-body">
                <div class="header">
                    <h1>{{ $staff->firstName }} {{ $staff->middleName }} {{ $staff->lastName }}</h1>
                    <p>{{ $staff->email }} | {{ $staff->phoneNumber }}</p>
                    <p>{{ $staff->currentAddress }}</p>
                    @if ($staff->photo)
                        <div class="photo mt-3">
                            <img src="{{ asset($staff->photo) }}" alt="Photo" height="100">
                        </div>
                    @endif
                </div>

                <div class="section">
                    <h2>Personal Details</h2>
                    <p><strong>Force Number:</strong> {{ $staff->forceNumber }}</p>
                    <p><strong>Date of Birth:</strong> {{ $staff->DoB }}</p>
                    <p><strong>Gender:</strong> {{ $staff->gender }}</p>
                    <p><strong>Marital Status:</strong> {{ $staff->maritalStatus }}</p>
                    <p><strong>Religion:</strong> {{ $staff->religion }}</p>
                    <p><strong>Tribe:</strong> {{ $staff->tribe }}</p>
                </div>

                <div class="section">
                    <h2>Professional Details</h2>
                    <p><strong>Rank:</strong> {{ $staff->rank }}</p>
                    <p><strong>Department:</strong> {{ $staff->department->name ?? 'N/A' }}</p>
                    <p><strong>Designation:</strong> {{ $staff->designation }}</p>
                    <p><strong>Contract Type:</strong> {{ $staff->contractType }}</p>
                    <p><strong>Joining Date:</strong> {{ $staff->joiningDate }}</p>
                    <p><strong>Location:</strong> {{ $staff->location }}</p>
                </div>

                <div class="section">
                    <h2>Education</h2>
                    <p><strong>Level:</strong> {{ $staff->educationLevel }}</p>
                </div>

                <div class="section">
                    <h2>Additional Details</h2>
                    <p><strong>Profile Complete:</strong> {{ $staff->profile_complete ? 'Yes' : 'No' }}</p>
                </div>

                <div class="section">
                    <h2>Other Information</h2>
                    <p>Add any custom details here...</p>
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
</div>

<!-- Print Script -->
@section('scripts')
<script>
    function printDiv() {
        const printContents = document.getElementById('printArea').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();

        // Use setTimeout to give the print dialog time to finish before resetting
        setTimeout(() => {
            document.body.innerHTML = originalContents;
            location.reload(); 
        }, 1000);
    }
</script>
@endsection

@endsection
