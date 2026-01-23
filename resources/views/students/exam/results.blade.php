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
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Final Results</a></li>
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
            <h4>Final Results for {{ $student->force_number }} {{ $student->rank }} {{ $student->first_name }} {{ $student->last_name }}</h4>
            <a href="{{ route('student.courseworks', $student->id) }}" class="btn btn-sm btn-primary">Coursework</a>
        </div>

            <div class="card-body">
                {{-- Tabs --}}
                <ul class="nav nav-tabs" id="semesterTab" role="tablist">
                    @foreach($groupedBySemester as $semesterId => $results)
                        @php
                            $semester = $results->first()->semester;
                        @endphp
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="semester-tab-{{ $semesterId }}" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#semester-{{ $semesterId }}" 
                                    type="button" role="tab" 
                                    aria-controls="semester-{{ $semesterId }}" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $semester?->semester_name ?? 'Unknown Semester' }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content mt-3" id="semesterTabContent">
                    @foreach($groupedBySemester as $semesterId => $results)
                        @php $sn = 1; @endphp

                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                             id="semester-{{ $semesterId }}"
                             role="tabpanel"
                             aria-labelledby="semester-tab-{{ $semesterId }}">

                            <div class="table-outer">
                                <strong>Overall  GPA : {{$student->GPA['overallGPA']}}</strong>
                                @if ($semesterId == 1)
                                    <div class="nd">
                                        <strong><i>Semester  GPA : {{$student->GPA['semesterOneGPA']}}</i></strong>
                                    </div>
                                @elseif ($semesterId == 2)
                                    <div class="nd">
                                        <strong><i>Semester  GPA : {{$student->GPA['semesterTwoGPA']}}</i></strong>
                                    </div>
                                @endif
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered m-0">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Credit Weight</th>
                                                <th>Score</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @forelse($results as $result)
                                                @php
                                                    $course = $result->course;
                                                    $score  = $result->total_score;
                                                @endphp
                                                <tr>
                                                    <td>{{ $sn++ }}.</td>
                                                    <td>{{ $course?->courseCode ?? 'N/A' }}</td>
                                                    <td>{{ $course?->courseName ?? 'N/A' }}</td>
                                                    <td>{{ $result->course->programmes->first()->pivot->credit_weight ?? 'N/A' }}</td>
                                                    <td>{{ $score }}</td>
                                                    <td>{{ $result->grade }}</td>
                                                    <td>
                                                        @if($score < 40)
                                                            <span class="text-danger">Fail</span>
                                                        @else
                                                            Pass
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        No exam results found for this semester.
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
