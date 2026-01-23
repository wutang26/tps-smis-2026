@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Guard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Guard Areas</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->
@endsection
@section('content')
    <div class="row gx-4">
        <div class="col-sm-8 col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>Edit Guard Area</h2>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('guard-areas.index') }}"><i
                                        class="fa fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
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


                    <form method="POST" action="{{ route('guard-areas.update', $guardArea) }}">
                        @csrf
                        @method('PUT')
                        <div class="row gx-4">
                            <div class="col-sm-12 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="m-0">
                                            <label class="form-label" for="abc">Guard Area Name</label>
                                            <input type="text" class="form-control" id="hours" name="guard_area_name"
                                                required placeholder="CRO MPS"
                                                value="{{old('guard_area_name', $guardArea->name)}}">
                                        </div>
                                        @error('guard_area_name')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="m-0">
                                            <label class="form-label" for="abc">Number of guards </label>
                                            <input type="number" min="0" class="form-control" id="number_of_guards"
                                                name="number_of_guards" required placeholder="2"
                                                value="{{old('number_of_guards', $guardArea->number_of_guards)}}">
                                        </div>
                                        @error('number_of_guards')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="m-0">
                                            <label class="form-label" for="abc">Campus</label>
                                            <select class="form-control" name="campus_id" id="campuses" required>
                                                <option value="" disabled selected>select campus</option>
                                                @foreach ($campuses as $campus)
                                                    <option value="{{ $campus->id }}" {{ old('campus_id', $guardArea->campus_id) == $campus->id ? 'selected' : '' }}>
                                                        {{ $campus->campusName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('campus_id')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="m-0">
                                            <label class="form-label" for="abc">Company</label>
                                            <select class="form-control" name="company_id" id="companies" required>
                                                <option value="" disabled selected>select company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}" {{ old('company_id', $guardArea->company_id) == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('company_id')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="m-0">
                                            <label class="form-label" for="beat_exception_ids">Beat Exceptions</label>
                                            <select class="form-control" name="beat_exception_ids[]" id="beat_exception_ids"
                                                multiple>
                                                @foreach ($beatExceptions as $beatException)
                                                    <option value="{{ $beatException->id }}" @if(in_array($beatException->id, json_decode($guardArea->beat_exception_ids, true) ?? [])) selected
                                                    @endif>
                                                        {{ $beatException->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                        <!-- Link to unselect all options -->
                                        <a style="text-decoration:underline;" href="javascript:void(0);" id="unselectBeatException" class="text-info"><i>Clear</i></a>
                                        </div>
                                        <script>
                                            document.getElementById('unselectBeatException').addEventListener('click', function () {
                                                const selectElement = document.getElementById('beat_exception_ids');

                                                // Unselect all options
                                                for (let option of selectElement.options) {
                                                    option.selected = false;
                                                }
                                            });
                                        </script>

                                        @error('beat_exception_ids')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="m-0">
                                            <label class="form-label" for="abc">Beat Time Exceptions </label>
                                            <select class="form-control" name="beat_time_exception_ids[]"
                                                id="beat_time_exception_ids" multiple>
                                                @foreach ($beatTimeExceptions as $beatTimeException)
                                                    <option value="{{ $beatTimeException->id }}"
                                                        @if(in_array($beatTimeException->id, json_decode($guardArea->beat_time_exception_ids, true) ?? [])) selected
                                                        @endif>
                                                        {{ $beatTimeException->name }}
                                                    </option>

                                                @endforeach
                                            </select>
                                            
                                        <!-- Link to unselect all options -->
                                        <a style="text-decoration:underline;" href="javascript:void(0);" id="unselectBeatTimeException" class="text-info"><i>Clear</i></a>
                                        </div>
                                        <script>
                                            document.getElementById('unselectBeatTimeException').addEventListener('click', function () {
                                                const selectElement = document.getElementById('beat_time_exception_ids');

                                                // Unselect all options
                                                for (let option of selectElement.options) {
                                                    option.selected = false;
                                                }
                                            });
                                        </script>
                                        @error('beat_time_exception_ids[]')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-primary" type="submit">Update</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
    <script>
        document.getElementById('campuses').addEventListener('change', function () {
            var campusId = this.value;
            var companiesSelect = document.getElementById('companies');
            companiesSelect.innerHTML = '<option value="">Select a company</option>'; // Clear previous options
            var link = '/tps-smis/campanies/' + campusId;
            if (campusId) {
                fetch(link)
                    .then(response => response.json())
                    .then(companies => {
                        companies.forEach(company => {
                            var option = document.createElement('option');
                            option.value = company.id;
                            option.text = company.name;
                            companiesSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching companies:', error));
            }
        });
    </script>
@endsection