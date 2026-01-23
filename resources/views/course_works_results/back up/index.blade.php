@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
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
<!-- Scrumb ends -->
@endsection

@section('content')
@session('success')
<div class="alert alert-success alert-dismissible " role="alert">
    {{ $value }}
</div>
@endsession

<div class="row gx-4">
    <div class="col-sm-3">
    <div class="card mb-3">
        <div class="card-header">
            <!-- Semester Tabs -->
            <ul class="nav nav-tabs" id="semesterTabs" role="tablist">
                @foreach ($semesters as $key => $semester)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $key == 0 ? 'active bg-success text-white' : '' }}" id="tab-{{ $semester->id }}"
                        data-bs-toggle="tab" data-bs-target="#semester-{{ $semester->id }}" type="button" role="tab"
                        aria-controls="semester-{{ $semester->id }}"
                        aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                        {{ $semester->semester_name }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body">
            <!-- Tab Content for Semesters -->
            <div class="tab-content" id="semesterTabsContent">
                @foreach ($semesters as $key => $semester)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="semester-{{ $semester->id }}"
                    role="tabpanel" aria-labelledby="tab-{{ $semester->id }}">
                    <h5>Courses for {{ $semester->semester_name }}</h5>
                    @if ($semester->courses->isNotEmpty())
                    <ul>
                        @foreach ($semester->courses as $course)
                        <li>
                            <a href="#" class="course-link" data-course-id="{{ $course->id }}">
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

<style>
  /* Default Tabs */
.nav-link {
    color: black; /* Default text color */
}

/* Active Semester Tab */
.nav-link.active {
    color: #28a745; /* Green text for the active semester tab */
}

/* Default Course Links */
.course-link {
    text-decoration: none;
    color: black; /* Default text color for course links */
}

/* Selected Course Link */
.course-link.selected {
    color: darkblue; /* Light blue text for the selected course */
    font-weight: bold; /* Optional: Make it stand out more */
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Highlight the active semester tab
        document.querySelectorAll('#semesterTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('#semesterTabs .nav-link').forEach(link => link.classList.remove('active', 'bg-success', 'text-white'));
                this.classList.add('active', 'bg-success', 'text-white');
            });
        });

        // Highlight the selected course
        document.querySelectorAll('.course-link').forEach(course => {
            course.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default link behavior
                document.querySelectorAll('.course-link').forEach(link => link.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    });
</script>

    <!-- Left section ends-->


    <!-- Right section starts-->
    <div class="col-sm-9">
        <div class="card mb-3">
            <div class="card-header">
                <div class="pull-right">
                    <span style="font-size:30px !important">Coursework Results</span>

                    <!-- <h6>Here display the course choosen</h6> -->

                    <button disabled id="add_btn" class="btn btn-success mb-2"
                        style="float:right !important; margin-right:1%;">
                        <a href="" id="add_link" style="color:white;"> <i class="fa fa-plus"></i> Upload Coursework</a>
                    </button>

                    <button disabled id="ca_configuration_btn" class="btn btn-success mb-2"
                        style="float:right !important; margin-right:1%;">
                        <a href="" id="ca_configuration_link" style="color:white;"> <i class="fa fa-plus"></i> CA Configurations</a>
                    </button>
                </div>
                
                <button id="export-btn" class="btn btn-secondary btn-sm" style="margin-bottom:-20px;">Export to Excel</button>
                
            </div>
            <div class="card-body">
                <div class="table-outer">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-info">
                                <tr id="coursework-headings">
                                    <!-- Dynamic headings will load here -->
                                </tr>
                            </thead>
                            <tbody id="coursework-results">
                                <!-- Dynamic results or "No results found" message will load here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3" id="pagination-container">
                        <!-- Styled Bootstrap pagination links will dynamically load here -->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Right section ends-->
</div>
<!-- Row ends -->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners for all course links
    document.querySelectorAll('.course-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Get the course ID
            const courseId = this.getAttribute('data-course-id');

            // Enable buttons and update their links dynamically
            const addLink = document.getElementById('add_link');
            const addButton = document.getElementById('add_btn');
            const uploadExplanationRoute = @json(route('coursework.upload_explanation', [
                'courseId' => 'COURSE_ID_PLACEHOLDER'
            ]));
            addLink.setAttribute('href', uploadExplanationRoute.replace('COURSE_ID_PLACEHOLDER',
                courseId));
            addButton.disabled = false;

            const caConfigurationLink = document.getElementById('ca_configuration_link');
            const caConfigurationButton = document.getElementById('ca_configuration_btn');
            const caConfigurationRoute = @json(route('course.coursework', ['courseId' =>
                'COURSE_ID_PLACEHOLDER'
            ]));
            caConfigurationLink.setAttribute('href', caConfigurationRoute.replace(
                'COURSE_ID_PLACEHOLDER', courseId));
            caConfigurationButton.disabled = false;

            console.log(`Selected Course ID: ${courseId}`);

            // Fetch and display results for the selected course
            fetchCourseworkResults(courseId);
        });
    });

    function fetchCourseworkResults(courseworkId, page = 1) {
    const apiUrl = `/tps-smis/coursework_results/coursework/${courseworkId}?page=${page}`;
    const headingsContainer = document.getElementById('coursework-headings');
    const resultsContainer = document.getElementById('coursework-results');
    const paginationContainer = document.getElementById('pagination-container');

    if (!headingsContainer || !resultsContainer || !paginationContainer) {
        console.error('Error: Necessary DOM elements are missing');
        return;
    }

    // Show a loading indicator (optional)
    resultsContainer.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetched Data:', data);

            // Handle cases where no results are found
            if (!data.results || !data.results.data || Object.keys(data.results.data).length === 0) {
                headingsContainer.innerHTML = '';
                resultsContainer.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-muted text-center">No results found for this coursework.</td>
                    </tr>
                `;
                paginationContainer.innerHTML = '';
                return;
            }

            // Check if `per_page` exists and is a valid number
            const perPage = data.results.per_page && !isNaN(data.results.per_page) ? data.results.per_page : 10;  // Default to 10 if not available

            // Clear previous content
            headingsContainer.innerHTML = `
                <th>#</th>
                <th>Force Number</th>
                <th>Student Name</th>
            `;
            resultsContainer.innerHTML = '';

            // Add dynamic coursework headings
            data.courseworks.forEach(coursework => {
                const heading = document.createElement('th');
                heading.style.textAlign = 'center';
                heading.innerText = coursework.coursework_title;
                headingsContainer.appendChild(heading);
            });

            const totalHeading = document.createElement('th');
            totalHeading.style.textAlign = 'center';
            totalHeading.innerText = 'Total CW';
            headingsContainer.appendChild(totalHeading);

            const actionsHeading = document.createElement('th');
            actionsHeading.style.textAlign = 'center';
            actionsHeading.innerText = 'Actions';
            headingsContainer.appendChild(actionsHeading);

            // Ensure the page number is a valid integer
            page = parseInt(page, 10);
            if (isNaN(page) || page <= 0) {
                page = 1;  // Default to page 1 if invalid
            }

            // Calculate the startIndex for serial numbers
            const startIndex = (page - 1) * perPage + 1;

            let rowIndex = startIndex;
            Object.entries(data.results.data).forEach(([studentId, studentResult]) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="text-align: center;">${rowIndex++}</td>
                    <td style="text-align: center;">${studentResult.student.force_number}</td>
                    <td>${studentResult.student.first_name} ${studentResult.student.middle_name || ''} ${studentResult.student.last_name}</td>
                `;

                data.courseworks.forEach(coursework => {
                    const score = studentResult.scores[coursework.id] || '-';
                    row.innerHTML += `<td style="text-align: center;">${score}</td>`;
                });

                row.innerHTML += `
                    <td style="text-align: center;">${studentResult.total_cw}</td>
                    <td style="text-align: center;">
                        <button class="btn btn-info btn-sm">View</button>
                        <button class="btn btn-primary btn-sm">Edit</button>
                    </td>
                `;
                resultsContainer.appendChild(row);
            });

            // Render pagination links dynamically
            paginationContainer.innerHTML = `
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        ${data.results.links.map(link => {
                            const page = link.url ? new URL(link.url, window.location.origin).searchParams.get('page') : null;

                            return `
                                <li class="page-item ${link.active ? 'active' : ''}">
                                    <a class="page-link" href="#" data-page="${page}">
                                        ${link.label}
                                    </a>
                                </li>
                            `;
                        }).join('')}
                    </ul>
                </nav>
            `;

            // Attach event listeners for pagination links
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    if (page) fetchCourseworkResults(courseworkId, page);
                });
            });
        })
        .catch(error => {
            console.error('Error fetching results:', error);

            resultsContainer.innerHTML = `
                <tr>
                    <td colspan="7" class="text-danger text-center">Failed to load results. Please try again later.</td>
                </tr>
            `;
        });
}


});
</script>

<script>
    // Function to export results to Excel
function exportToExcel(courseworkId) {
    const tableData = [];
    
    // Define table headers based on the specified format
    tableData.push([
        'S/N',
        'Full Name',
        'Sex',
        'Examination Number',
        'Individual Assignment',
        'Group Assignment',
        'Test',
        'Total'
    ]);

    // Fetch results from the server
    fetch(`/tps-smis/coursework_results/coursework/${courseworkId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            let rowIndex = 1; // Initialize the serial number
            data.results.data.forEach(studentResult => {
                const fullName = `${studentResult.student.first_name} ${studentResult.student.middle_name || ''} ${studentResult.student.last_name}`;
                const row = [
                    rowIndex++, // Serial number
                    fullName.trim(), // Full Name
                    studentResult.student.sex || '-', // Sex
                    studentResult.student.force_number, // Examination Number
                    studentResult.scores['individual_assignment'] || '-', // Individual Assignment
                    studentResult.scores['group_assignment'] || '-', // Group Assignment
                    studentResult.scores['test'] || '-', // Test
                    studentResult.total_cw || '-', // Total
                ];
                tableData.push(row); // Add row to table data
            });

            // Use SheetJS to create and download Excel file
            const worksheet = XLSX.utils.aoa_to_sheet(tableData);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Results');
            XLSX.writeFile(workbook, 'Coursework_Results.xlsx');
        })
        .catch(error => {
            console.error('Error exporting results:', error);
            alert('Failed to export results. Please try again later.');
        });
}

// Attach event listener to the Export button
document.getElementById('export-btn').addEventListener('click', function () {
    // Dynamically fetch the courseworkId from a hidden input or dataset
    const courseworkId = document.getElementById('export-btn').dataset.courseworkId;

    // If courseworkId is not available, throw an error
    if (!courseworkId) {
        console.error('Error: courseworkId is not defined');
        alert('Coursework ID is missing. Cannot export results.');
        return;
    }

    // Call the export function with the courseworkId
    exportToExcel(courseworkId);
});

</script>

@endsection