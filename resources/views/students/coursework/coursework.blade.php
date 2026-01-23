@extends('layouts.main')

@section('style')
<style>
    .breadcrumb {
        display: flex;
        width: 100%;
    }
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    #date {
        position: absolute;
        bottom: 10px;
        right: 15px;
    }
    .table-outer {
        overflow-x: auto;
    }
    .table thead th, .table tbody td {
        border: 1px solid #dee2e6;
    }
    .table tbody tr:last-child td {
        border-bottom: 1px solid #dee2e6;
    }
    .nd {
        margin-top: 20px;
    }
</style>
@endsection

@section('scrumb')
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Coursework Results</a></li>
                <li class="breadcrumb-item right-align"><a href="#" id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a></li>
            </ol>
        </nav>
    </div>
</nav>
@endsection

@section('content')
<div class="row gx-4">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">
                @if (session('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
            </div>
        <div class=" d-flex justify-content-between card-header mb-3">
            <h4>Coursework Results for {{ $student->force_number }} {{ $student->rank }} {{ $student->first_name }} {{ $student->last_name }}</h4>
            <a href="{{ route('student.final_results', $student->id) }}" class="btn btn-sm btn-primary">Final Results</a>
        </div>
            <div class="card-body">
                @if ($groupedBySemester->isEmpty())
                    No courseworks
                @endif
                <ul class="nav nav-tabs" id="semesterTab" role="tablist">
                    @foreach($groupedBySemester as $semesterId => $results)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="semester-tab-{{ $semesterId }}" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#semester-{{ $semesterId }}" 
                                    type="button" role="tab" 
                                    aria-controls="semester-{{ $semesterId }}" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $results->first()->coursework->semester->semester_name ?? 'Unknown Semester' }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="semesterTabContent">
                    

            @foreach($groupedBySemester as $semesterId => $results)
            @php $sn = 1; @endphp
                @php
                    // Group by course ID to ensure one row per course
                    $groupedByCourse = $results->groupBy(function($result) {
                        return optional($result->coursework->course)->id;
                    });
                @endphp

                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                    id="semester-{{ $semesterId }}"
                    role="tabpanel"
                    aria-labelledby="semester-tab-{{ $semesterId }}">

                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Credit Weight</th>
                                        @foreach($assessmentTypes as $type)
                                            <th>{{ $type->type_name }}</th>
                                        @endforeach
                                        <th>Total</th>
                                        <th>Remarks</th>
                                        <!-- <th>Actions</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groupedByCourse as $courseResults)
                                        @php
                                            $course = optional($courseResults->first()->coursework->course);
                                            $scores = [];

                                            // Initialize empty score for each assessment type
                                            foreach($assessmentTypes as $type) {
                                                $scores[$type->type_name] = 0;
                                            }

                                            // Fill in scores for the course by assessment type
                                            foreach($courseResults as $result) {
                                                $typeName = optional($result->coursework->assessmentType)->type_name;
                                                if ($typeName) {
                                                    $scores[$typeName] += $result->score;
                                                }
                                            }

                                            $total = array_sum($scores);

                                            // Get credit weight from studentCourses passed from controller
                                            $creditWeight = isset($studentCourses[$course->id]) ? $studentCourses[$course->id]->pivot->credit_weight : 'N/A';
                                        @endphp

                                        <tr>
                                            <td>{{ $sn++ }}.</td>
                                            <td>{{ $course->courseCode ?? 'N/A' }} </td>
                                            <td>{{ $course->courseName ?? 'N/A' }}</td>
                                            <td>{{ $creditWeight }}</td>
                                            @foreach($assessmentTypes as $type)
                                                <td>{{ $scores[$type->type_name] }}</td>
                                            @endforeach

                                            <td>{{ $total }}</td>

                                            <td>
                                                @if($total < 16)
                                                    <span style="color:red;">Fail</span>
                                                @else
                                                    Pass
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 6 + count($assessmentTypes) }}" class="text-center">
                                                No coursework results found for this semester.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
