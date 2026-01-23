@extends('layouts.main')

@section('scrumb')
    <!-- Breadcrumb -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="#">
                            @if(isset($student)) Update @else Create @endif Step One
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <?php $typeToAppend = isset($student) ? "edit" : "create"; ?>

    <form action="{{ url('students/create/post-step-one/' . $typeToAppend) }}" method="POST">
        @csrf
        @method('POST')

        <div class="row g-3 mx-0"><!-- prevent overflow -->

            <!-- Force Number -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{ $student->id ?? '' }}">
                        <label class="form-label">Force Number</label>
                        <input type="text" class="form-control w-100"
                               id="force_number" name="force_number"
                               placeholder="Enter force number"
                               value="{{ old('force_number', $student->force_number ?? '') }}">
                        @error('force_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Rank -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <label class="form-label">Rank</label>
                        <select class="form-select w-100" id="rank" name="rank">
                            <option value="" disabled {{ old('rank', $student->rank ?? '') == '' ? 'selected' : '' }}>Select rank</option>
                            <option value="RC" {{ old('rank', $student->rank ?? '') == 'RC' ? 'selected' : '' }}>Basic Recruit</option>
                            <option value="Constable" {{ old('rank', $student->rank ?? '') == 'Constable' ? 'selected' : '' }}>Police Constable</option>
                            <option value="CPL" {{ old('rank', $student->rank ?? '') == 'CPL' ? 'selected' : '' }}>CPL</option>
                            <option value="Sergeant" {{ old('rank', $student->rank ?? '') == 'Sergeant' ? 'selected' : '' }}>Sergeant Major</option>
                        </select>
                        @error('rank')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- First Name -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control w-100"
                               id="first_name" name="first_name"
                               placeholder="Enter firstname"
                               value="{{ old('first_name', $student->first_name ?? '') }}" required>
                        @error('first_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Middle Name -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control w-100"
                               id="middle_name" name="middle_name"
                               placeholder="Enter middlename"
                               value="{{ old('middle_name', $student->middle_name ?? '') }}" required>
                        @error('middle_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Last Name -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control w-100"
                               id="last_name" name="last_name"
                               placeholder="Enter lastname"
                               value="{{ old('last_name', $student->last_name ?? '') }}" required>
                        @error('last_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Education Level -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <label class="form-label">Education Level</label>
                        <select class="form-select w-100" id="education_level" name="education_level">
                            <option value="" disabled {{ old('education_level', $student->education_level ?? '') == '' ? 'selected' : '' }}>Select education</option>
                            <option value="KIDATO CHA NNE" {{ old('education_level', $student->education_level ?? '') == 'KIDATO CHA NNE' ? 'selected' : '' }}>KIDATO CHA NNE</option>
                            <option value="KIDATO CHA SITA" {{ old('education_level', $student->education_level ?? '') == 'KIDATO CHA SITA' ? 'selected' : '' }}>KIDATO CHA SITA</option>
                            <option value="ASTASHAHADA" {{ old('education_level', $student->education_level ?? '') == 'ASTASHAHADA' ? 'selected' : '' }}>ASTASHAHADA</option>
                            <option value="STASHAHADA" {{ old('education_level', $student->education_level ?? '') == 'STASHAHADA' ? 'selected' : '' }}>STASHAHADA</option>
                            <option value="SHAHADA" {{ old('education_level', $student->education_level ?? '') == 'SHAHADA' ? 'selected' : '' }}>SHAHADA</option>
                        </select>
                        @error('education_level')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Home Region -->
            <div class="col-sm-4 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <label class="form-label">Home Region</label>
                        <input type="text" class="form-control w-100"
                               id="home_region" name="home_region"
                               placeholder="Enter home region"
                               value="{{ old('home_region', $student->home_region ?? '') }}">
                        @error('home_region')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- CSS to prevent mobile scroll -->
<style>
    body {
        overflow-x: hidden;
    }
    .card, .form-control, .form-select {
        max-width: 100%;
    }
</style>
@endsection
