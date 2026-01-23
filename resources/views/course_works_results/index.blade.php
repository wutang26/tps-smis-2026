@extends('layouts.main')

@section('scrumb')
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Course Work Results</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Work Results Lists</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')

<div class="row gx-4">

    <!-- Left side -->
    <div class="col-sm-3">
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs" id="semesterTabs" role="tablist">
                    @foreach ($semesters as $key => $semester)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $key == 0 ? 'active bg-success text-white' : '' }}"
                                id="tab-{{ $semester->id }}"
                                data-bs-toggle="tab"
                                data-bs-target="#semester-{{ $semester->id }}"
                                type="button">
                            {{ $semester->semester_name }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="semesterTabsContent">
                    @foreach ($semesters as $key => $semester)
                    <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                         id="semester-{{ $semester->id }}">

                        <h5>Courses for {{ $semester->semester_name }}</h5>

                        @if ($semester->courses->isNotEmpty())
                            <ul>
                                @foreach ($semester->courses as $course)
                                <li>
                                    <a href="#" class="course-link"
                                       data-course-id="{{ $course->id }}">
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

    <!-- Right side -->
    <div class="col-sm-9">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span style="font-size: 26px;">Coursework Results</span>

                <div class="d-flex" style="gap: 10px;">
                    <button disabled id="add_btn" class="btn btn-success">
                        <a href="" id="add_link" class="text-white text-decoration-none">
                            <i class="fa fa-plus"></i> Upload Coursework
                        </a>
                    </button>

                    <button disabled id="ca_configuration_btn" class="btn btn-success">
                        <a href="" id="ca_configuration_link" class="text-white text-decoration-none">
                            <i class="fa fa-cog"></i> CA Configurations
                        </a>
                    </button>
                </div>
            </div>

            <div class="card-body">

                <!-- Search Form (SUBMIT ONLY) -->
                <form id="search-form" class="d-flex justify-content-end mb-3">
                    <input id="search-input"
                           name="search"
                           class="form-control"
                           placeholder="Search by name or force number"
                           style="max-width: 350px; margin-right: 10px;">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-info">
                            <tr id="coursework-headings"></tr>
                        </thead>
                        <tbody id="coursework-results"></tbody>
                    </table>
                </div>

                <div id="pagination-container" class="d-flex justify-content-end mt-3"></div>

            </div>
        </div>
    </div>

</div>

@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Highlight selected course + store selected ID
    document.querySelectorAll(".course-link").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            let courseId = this.dataset.courseId;
            window.currentCourseId = courseId;

            document.querySelectorAll('.course-link')
                .forEach(a => a.classList.remove('selected'));
            this.classList.add('selected');

            // Enable buttons
            const uploadRoute = @json(route('coursework.upload_explanation', ['courseId' => 'CID']));
            document.getElementById("add_link").href = uploadRoute.replace("CID", courseId);
            document.getElementById("add_btn").disabled = false;

            const configRoute = @json(route('course.coursework', ['courseId' => 'CID']));
            document.getElementById("ca_configuration_link").href = configRoute.replace("CID", courseId);
            document.getElementById("ca_configuration_btn").disabled = false;

            fetchCourseworkResults(courseId, 1);
        });
    });

    // SEARCH ONLY WHEN FORM SUBMITTED
    document.getElementById("search-form").addEventListener("submit", function(e) {
        e.preventDefault();
        if (window.currentCourseId) {
            fetchCourseworkResults(window.currentCourseId, 1);
        }
    });

});

// MAIN FETCH FUNCTION (Supports Search + Pagination)
function fetchCourseworkResults(courseId, page = 1) {

    const searchValue = document.getElementById("search-input").value.trim();
    const url = `/tps-smis/coursework_results/coursework/${courseId}?page=${page}&search=${encodeURIComponent(searchValue)}`;

    const head = document.getElementById("coursework-headings");
    const body = document.getElementById("coursework-results");
    const pagination = document.getElementById("pagination-container");

    body.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;

    fetch(url)
        .then(res => res.json())
        .then(data => {

            if (!data.results || !data.results.data.length) {
                head.innerHTML = "";
                body.innerHTML = `
                    <tr><td colspan="7" class="text-center text-muted">No results found.</td></tr>
                `;
                pagination.innerHTML = "";
                return;
            }

            // Reset table
            head.innerHTML = `
                <th>#</th>
                <th>Force No.</th>
                <th>Student Name</th>
            `;

            // Add coursework headings
            data.courseworks.forEach(cw => {
                head.innerHTML += `<th class="text-center">${cw.coursework_title}</th>`;
            });

            head.innerHTML += `<th class="text-center">Total</th>`;
            head.innerHTML += `<th class="text-center">Actions</th>`;

            body.innerHTML = "";
            
            let start = (data.results.pagination.current_page - 1) * data.results.pagination.per_page + 1;
            console.log(data.results.pagination.current_page )
            Object.values(data.results.data).forEach((row, index) => {
                let html = `
                    <tr>
                        <td class="text-center">${start + index}</td>
                        <td class="text-center">${row.student.force_number}</td>
                        <td>${row.student.first_name} ${row.student.middle_name ?? ""} ${row.student.last_name}</td>
                `;

                data.courseworks.forEach(cw => {
                    let score = row.scores[cw.id] ?? "-";
                    html += `<td class="text-center">${score}</td>`;
                });

                html += `
                        <td class="text-center">${row.total_cw}</td>
                        <td class="text-center">
                            <button class="btn btn-info btn-sm">View</button>
                            <button class="btn btn-primary btn-sm">Edit</button>
                        </td>
                    </tr>
                `;

                body.innerHTML += html;
            });

            // Pagination
            pagination.innerHTML = `
                <nav>
                    <ul class="pagination justify-content-end">
                        ${data.results.links.map(link => `
                            <li class="page-item ${link.active ? "active" : ""}">
                                <a href="#" class="page-link" data-page="${link.url ? new URL(link.url).searchParams.get("page") : ""}">
                                    ${link.label}
                                </a>
                            </li>
                        `).join("")}
                    </ul>
                </nav>
            `;

            // Pagination Click
            document.querySelectorAll(".page-link").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    let p = this.dataset.page;
                    if (p) fetchCourseworkResults(courseId, p);
                });
            });

        });
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
