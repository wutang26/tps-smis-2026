@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Semester Exam Results</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#" id="breadcrumbCourse">Semester Exam Results List</a>
                </li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')
<div class="row gx-4">

    <!-- Left section: Semester tabs and courses -->
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
                                            <a href="#"
                                               class="course-link"
                                               data-course-id="{{ $course->id }}"
                                               data-semester-id="{{ $semester->id }}"
                                               data-course-name="{{ $course->courseName }}">
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

    <!-- Right section: Exam results -->
    <div class="col-sm-9">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span style="font-size: 26px;">Semester Exam Results</span>
                <div class="d-flex gap-2">
                    <button disabled id="upload_exam_btn" class="btn btn-success">
                        <a href="" id="upload_exam_link" class="text-white text-decoration-none">
                            <i class="fa fa-plus"></i> Upload SE Results
                        </a>
                    </button>
                    <button disabled id="exam_configuration_btn" class="btn btn-success">
                        <a href="" id="exam_configuration_link" class="text-white text-decoration-none">
                            <i class="fa fa-cog"></i> SE Configurations
                        </a>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Search Form -->
                <form id="search-form" class="d-flex justify-content-end mb-3">
                    <input id="search-input" name="search" class="form-control"
                           placeholder="Search by name or force number" style="max-width: 350px; margin-right: 10px;">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-info">
                            <tr id="exam-headings"></tr>
                        </thead>
                        <tbody id="exam-results"></tbody>
                    </table>
                </div>

                <div id="exam-pagination" class="d-flex justify-content-end mt-3"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Highlight selected course + store selected ID
    document.querySelectorAll(".course-link").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();

            const courseId = this.dataset.courseId;
            const semesterId = this.dataset.semesterId;
            const courseName = this.dataset.courseName;
            window.currentCourseId = courseId;
            window.currentSemesterId = semesterId;

            // Update breadcrumb
            document.getElementById('breadcrumbCourse').textContent =
                `Semester Exam Results List for ${courseName}`;

            // Highlight selected course
            document.querySelectorAll('.course-link').forEach(a => a.classList.remove('selected'));
            this.classList.add('selected');

            // Enable buttons
            const uploadRoute = @json(route('exam.upload_explanation', ['courseId'=>'COURSE_ID', 'semesterId'=>'SEM_ID']));
            document.getElementById("upload_exam_link").href =
                uploadRoute.replace("COURSE_ID", courseId).replace("SEM_ID", semesterId);
            document.getElementById("upload_exam_btn").disabled = false;

            const configRoute = @json(route('exam.configure', ['courseId'=>'COURSE_ID']));
            document.getElementById("exam_configuration_link").href =
                configRoute.replace("COURSE_ID", courseId);
            document.getElementById("exam_configuration_btn").disabled = false;

            // Fetch exam results
            fetchExamResults(courseId, semesterId, 1);
        });
    });

    // Search form submit
    document.getElementById("search-form").addEventListener("submit", function(e){
        e.preventDefault();
        if(window.currentCourseId && window.currentSemesterId){
            fetchExamResults(window.currentCourseId, window.currentSemesterId, 1);
        }
    });

});

// Fetch function for exam results with search & pagination
function fetchExamResults(courseId, semesterId, page = 1){
    const search = document.getElementById("search-input").value.trim();
    const url = `/tps-smis/semester_exams/semester_exam_results/${courseId}/${semesterId}?page=${page}&search=${encodeURIComponent(search)}`;

    const head = document.getElementById("exam-headings");
    const body = document.getElementById("exam-results");
    const pagination = document.getElementById("exam-pagination");

    body.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;

    fetch(url)
    .then(res => res.json())
    .then(data => {
        if(!data.results || Object.keys(data.results.data).length === 0){
            head.innerHTML = "";
            body.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No results found.</td></tr>`;
            pagination.innerHTML = "";
            return;
        }

        // Table headings
        head.innerHTML = `
            <th>#</th>
            <th>Force Number</th>
            <th>Student Name</th>
            <th style="text-align:center;">Exam Score</th>
            <th style="text-align:center;">Actions</th>
        `;

        // Table body
        body.innerHTML = "";
        const perPage = data.results.per_page || 10;
        const startIndex = (page - 1) * perPage + 1;
        let rowIndex = startIndex;

        Object.values(data.results.data).forEach(studentResult => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td style="text-align:center;">${rowIndex++}</td>
                <td style="text-align:center;">${studentResult.student.force_number}</td>
                <td>${studentResult.student.first_name} ${studentResult.student.middle_name ?? ""} ${studentResult.student.last_name}</td>
                <td style="text-align:center;">${studentResult.exam_score ?? "-"}</td>
                <td style="text-align:center;">
                    <button class="btn btn-info btn-sm">View</button>
                    <button class="btn btn-primary btn-sm">Edit</button>
                </td>
            `;
            body.appendChild(row);
        });

        // Pagination
        pagination.innerHTML = `
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    ${data.results.links.map(link => {
                        const pageNum = link.url ? new URL(link.url, window.location.origin).searchParams.get("page") : null;
                        return `<li class="page-item ${link.active ? "active" : ""}">
                                    <a href="#" class="page-link" data-page="${pageNum}">${link.label}</a>
                                </li>`;
                    }).join("")}
                </ul>
            </nav>
        `;

        document.querySelectorAll(".page-link").forEach(link => {
            link.addEventListener("click", function(e){
                e.preventDefault();
                const p = this.dataset.page;
                if(p) fetchExamResults(courseId, semesterId, p);
            });
        });

    }).catch(err=>{
        console.error(err);
        body.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Failed to load results.</td></tr>`;
        pagination.innerHTML = "";
    });
}
</script>
@endsection
