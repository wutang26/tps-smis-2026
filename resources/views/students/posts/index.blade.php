@extends('layouts.main')

@section('style')

@endsection
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb" style="margin-right: 25px;">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Posts</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->
@endsection
@section('content')
    @include('layouts.sweet_alerts.index')
    <div class="card-body">

        <div>
            <h2>Students Posts</h2>
        </div>
        <div class="d-flex justify-content-center mb-3">
            <form id="student-search-form" class="d-flex gap-2" action="{{ route('students-post.search') }}" method="GET">
                <!-- Name Search -->
                <input type="text" id="name" name="name" value="{{ request('name') }}" class="form-control me-2"
                    placeholder="Name (optional)">

                <!-- Company Dropdown -->
                <select id="companies" class="form-select me-2" name="company_id">
                    <option value="" selected disabled>Select Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Platoon Dropdown -->
                <select id="platoons" class="form-select me-2" name="platoon">
                    <option value="" selected disabled>Select Platoon</option>
                </select>

                <!-- Search Button -->
                <button type="submit" id="search-btn" class="btn btn-primary">Search</button>

                <!-- Reset Button -->
                <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
            </form>

        </div>
        @if ($posts->isEmpty())
            <h3>No posts available for provided criteria.</h3>
        @else



            <div class="table-outer">
                <div class="table-responsive">
                    <table class="table table-striped truncate m-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Phone</th>
                                <th>Post</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $post->student->force_number }} {{ $post->student->rank }}
                                        {{ $post->student->first_name }} {{ $post->student->last_name }}</td>
                                    <td>{{ $post->student->company->name }} - {{ $post->student->platoon }}</td>
                                    <td>{{ $post->student->phone }}</td>
                                    <td>{{ $post->region }} {{ $post->district ? '- ' . $post->district : '' }}
                                        {{ $post->unit ? '- ' . $post->unit : ''  }} {{ $post->office ? '- ' . $post->office : ''  }}</td>
                                    <td class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#postModal{{ $post->id }}">
                                            More
                                        </button>
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ route('students.show', $post->student_id) }}">Profile</a>
                                    </td>
                                    <div class="modal fade" id="postModal{{ $post->id }}" tabindex="-1"
                                        aria-labelledby="postModalLabel{{ $post->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="postModalLabel{{ $post->id }}">Post Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Student :</strong> {{ $post->student->force_number }}
                                                        {{ $post->student->rank }} {{ $post->student->first_name }}
                                                        {{ $post->student->last_name }}</p>
                                                    <p><strong>Region:</strong> {{ ucfirst($post->region) }}</p>
                                                    <p><strong>District:</strong> {{ $post->district }}</p>
                                                    <p><strong>Unit:</strong> {{ $post->unit }}</p>
                                                    <p><strong>Office:</strong> {{ $post->office }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    {!! $posts->appends(request()->query())->links('pagination::bootstrap-5') !!}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const companySelect = document.getElementById('companies');
            const platoonsSelect = document.getElementById('platoons');
            const nameInput = document.getElementById('name');
            const searchBtn = document.getElementById('search-btn');
            const resetBtn = document.getElementById('reset-btn');

            // Enable/disable search button
            function validateForm() {
                const name = nameInput.value.trim();
                const company = companySelect.value;
                const platoon = platoonsSelect.value;

                searchBtn.disabled = !(name || (company && platoon));
            }

            // Load platoons dynamically
            function loadPlatoons(companyId, selectedPlatoon = null) {
                platoonsSelect.innerHTML = '<option value="">Select Platoon</option>';
                if (!companyId) return;

                fetch(`/tps-smis/platoons/${companyId}`)
                    .then(res => res.json())
                    .then(platoons => {
                        platoons.forEach(platoon => {
                            const option = document.createElement('option');
                            option.value = platoon.id;
                            option.text = platoon.name;
                            if (selectedPlatoon && platoon.id == selectedPlatoon) option.selected = true;
                            platoonsSelect.appendChild(option);
                        });
                        validateForm();
                    })
                    .catch(err => console.error('Error fetching platoons:', err));
            }

            // Events
            nameInput.addEventListener('input', validateForm);
            companySelect.addEventListener('change', function () {
                loadPlatoons(this.value);
                validateForm();
            });
            platoonsSelect.addEventListener('change', validateForm);

            // Reset form
            resetBtn.addEventListener('click', function () {
                nameInput.value = '';
                companySelect.selectedIndex = 0;
                platoonsSelect.innerHTML = '<option value="">Select Platoon</option>';
                validateForm();
            });

            // Initial load
            const initialCompany = companySelect.value;
            const initialPlatoon = "{{ request('platoon') }}";
            if (initialCompany) loadPlatoons(initialCompany, initialPlatoon);
            validateForm();
        });
    </script>


@endsection