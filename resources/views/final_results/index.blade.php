@extends('layouts.main')

@section('scrumb')
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Semester Exam Results</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Semester Final Results Lists</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')

@session('success')
<div class="alert alert-success alert-dismissible " role="alert">
    {{ $value }}
</div>
@endsession

<div class="row gx-4">

    <!-- Left Section: Semesters & Courses -->
    <div class="col-sm-3">
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs" id="semesterTabs" role="tablist">
                    @foreach ($semesters as $key => $semester)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $key == 0 ? 'active bg-success text-white' : '' }}"
                            id="tab-{{ $semester->id }}" data-bs-toggle="tab"
                            data-bs-target="#semester-{{ $semester->id }}" type="button" role="tab"
                            aria-controls="semester-{{ $semester->id }}"
                            aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                            {{ $semester->semester_name }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="semesterTabsContent">
                    @foreach ($semesters as $key => $semester)
                    <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="semester-{{ $semester->id }}"
                        role="tabpanel" aria-labelledby="tab-{{ $semester->id }}">
                        <h5>Courses for {{ $semester->semester_name }}</h5>
                        @if ($semester->courses->isNotEmpty())
                        <ul>
                            @foreach ($semester->courses as $course)
                            <li>
                                <a href="#" class="course-link" data-course-id="{{ $course->id }}"
                                    data-semester-id="{{ $semester->id }}">
                                    {{ $course->courseName }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p>No courses available for this semester.</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section: Results Table -->
    <div class="col-sm-9">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span style="font-size:30px !important">Semester Final Results</span>
                <button id="ca_configuration_btn" class="btn btn-success mb-2" onclick="confirmReturn()" disabled>
                    <i class="fa fa-plus"></i> Return results
                </button>
            </div>
            <div class="card-body">

                <!-- Search -->
                <div class="d-flex justify-content-end mb-2">
                    <input type="text" id="coursework-search-input" placeholder="Search by name or force number"
                        class="form-control" style="max-width: 300px; margin-right:10px;">
                    <button class="btn btn-primary" id="coursework-search-btn">Search</button>
                </div>

                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-info">
                                <tr id="coursework-headings"></tr>
                            </thead>
                            <tbody id="coursework-results"></tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3" id="pagination-container"></div>
                </div>

            </div>
        </div>
    </div>

</div>

<style>
.nav-link { color: black; }
.nav-link.active { color: #28a745; }
.course-link { text-decoration: none; color: black; }
.course-link.selected { color: darkblue; font-weight: bold; }
</style>
@endsection

@section('scripts')
<script>
let selectedCourseId = null;
let selectedSemesterId = null;
let csrfToken = null;

document.addEventListener('DOMContentLoaded', function() {
    csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const caConfigButton = document.getElementById('ca_configuration_btn');
    caConfigButton.disabled = true;

    // Semester tabs
    document.querySelectorAll('#semesterTabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('#semesterTabs .nav-link').forEach(link => link.classList.remove('active', 'bg-success', 'text-white'));
            this.classList.add('active', 'bg-success', 'text-white');
        });
    });

    // Course links
    document.querySelectorAll('.course-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            selectedCourseId = this.dataset.courseId;
            selectedSemesterId = this.dataset.semesterId;

            document.querySelectorAll('.course-link').forEach(l => l.classList.remove('selected'));
            this.classList.add('selected');

            caConfigButton.disabled = false;
            fetchCourseworkResults(selectedSemesterId, selectedCourseId);
        });
    });

    // Confirm return results
    window.confirmReturn = function() {
        if (!selectedCourseId || !selectedSemesterId) return;
        Swal.fire({
            title: 'Return Results',
            text: 'Are you sure you want to return the results?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, return',
            cancelButtonText: 'No, cancel',
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/final_results/return/semester/${selectedSemesterId}/course/${selectedCourseId}`, {
                    method: 'GET',
                    headers: {'X-CSRF-TOKEN': csrfToken}
                })
                .then(r => r.json())
                .then(data => {
                    fetchCourseworkResults(selectedSemesterId, selectedCourseId);
                    Swal.fire('Returned!', 'The results have been returned.', 'success');
                })
                .catch(e => Swal.fire('Error', 'Failed to return results.', 'error'));
            }
        });
    };

    // Search functionality
    const searchInput = document.getElementById('coursework-search-input');
    const searchBtn = document.getElementById('coursework-search-btn');

    searchBtn.addEventListener('click', () => {
        if (selectedCourseId && selectedSemesterId) {
            fetchCourseworkResults(selectedSemesterId, selectedCourseId, 1, searchInput.value.trim());
        }
    });

    searchInput.addEventListener('keypress', e => {
        if (e.key === 'Enter' && selectedCourseId && selectedSemesterId) {
            fetchCourseworkResults(selectedSemesterId, selectedCourseId, 1, searchInput.value.trim());
        }
    });

    function fetchCourseworkResults(semesterId, courseId, page = 1, search = '') {
        const apiUrl = `final_results/semester/${semesterId}/course/${courseId}?page=${page}&search=${encodeURIComponent(search)}`;
        const headingsContainer = document.getElementById('coursework-headings');
        const resultsContainer = document.getElementById('coursework-results');
        const paginationContainer = document.getElementById('pagination-container');

        fetch(apiUrl)
            .then(r => r.json())
            .then(data => {
                if (!data.results || !data.results.data.length) {
                    headingsContainer.innerHTML = '';
                    resultsContainer.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No results found.</td></tr>`;
                    paginationContainer.innerHTML = '';
                    return;
                }

                headingsContainer.innerHTML = `
                    <th>#</th>
                    <th>Force Number</th>
                    <th>Student Name</th>
                    <th style="text-align:center;">Score</th>
                    <th style="text-align:center;">Grade</th>
                    <th style="text-align:center;">Actions</th>
                `;

                resultsContainer.innerHTML = '';
                data.results.data.forEach((result, index) => {
                    const student = result.student;
                    const fullName = `${student.first_name} ${student.middle_name || ''} ${student.last_name}`.replace(/\s+/g,' ').trim();
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="text-align:center;">${index + 1}</td>
                        <td style="text-align:center;">${student.force_number}</td>
                        <td>${fullName}</td>
                        <td style="text-align:center;">${result.total_score ?? ''}</td>
                        <td style="text-align:center;">${result.grade ?? ''}</td>
                        <td style="text-align:center;">
                            <button class="btn btn-info btn-sm">View</button>
                            <button class="btn btn-primary btn-sm">Edit</button>
                        </td>
                    `;
                    resultsContainer.appendChild(row);
                });

                // Pagination
                paginationContainer.innerHTML = `
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            ${data.results.links.map(link => {
                                const page = link.url ? new URL(link.url, window.location.origin).searchParams.get('page') : null;
                                return `<li class="page-item ${link.active ? 'active' : ''} ${link.url ? '' : 'disabled'}">
                                    <a class="page-link" href="#" ${page ? `data-page="${page}"` : ''}>${link.label}</a>
                                </li>`;
                            }).join('')}
                        </ul>
                    </nav>
                `;

                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', e => {
                        e.preventDefault();
                        const page = link.getAttribute('data-page');
                        if (page) fetchCourseworkResults(semesterId, courseId, page, search);
                    });
                });
            })
            .catch(err => {
                console.error(err);
                resultsContainer.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Failed to load results.</td></tr>`;
            });
    }
});
</script>
@endsection
