@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/attendences/.$attendenceType->id">Today
                            {{ $attendenceType->name }} Attendence Summary</a>
                    </li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('style')
    <style>
        /* Desktop: natural width (default) */
        #customTab2 .nav-item {
            flex: 0 0 auto;
            /* don't stretch */
        }

        /* Mobile: shrink buttons to fit screen width */
        @media (max-width: 768px) {
            #customTab2 {
                flex-wrap: nowrap;
                /* keep in single row */
                overflow-x: hidden;
                /* hide scroll */
            }

            #customTab2 .nav-item {
                flex: 1 1 0;
                /* all tabs share equal width */
                text-align: center;
            }

            #customTab2 .nav-link {
                white-space: nowrap;
                /* keep text in single line */
                padding: 0.5rem 0.25rem;
                /* reduce padding on mobile */
                font-size: 0.85rem;
                /* shrink font if needed */
            }
        }
    </style>
@endsection
@section('content')
    @include('layouts.sweet_alerts.index')
    @if(count($companies) > 0)
        <div class="row ">
            <div class="col-12 col-md-3">
                <form action="{{ route('attendances.summary', ['type_id' => $attendenceType->id]) }}" method="GET"
                    class="d-flex gap-2 flex-wrap align-items-center">
                    <input type="date" name="date" max="{{ Carbon\Carbon::today()->format('Y-m-d') }}" class="form-control"
                        style="flex: 1 1 auto; min-width: 0; width: auto;" value="{{ $date }}">
                    <button type="submit" class="btn btn-primary flex-shrink-0">Filter</button>
                </form>
            </div>


        </div>
        <script>
            document.getElementById('companies').addEventListener('change', function () {
                var companyId = this.value;
                var platoonsSelect = document.getElementById('platoons');
                platoonsSelect.innerHTML = '<option value="">Select a platoon</option>'; // Clear previous options
                var link = '/tps-smis/platoons/' + companyId
                if (companyId) {
                    fetch(link)
                        .then(response => response.json())
                        .then(platoons => {
                            platoons.forEach(platoon => {
                                var option = document.createElement('option');
                                option.value = platoon.id;
                                option.text = platoon.name;
                                platoonsSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching platoons:', error));
                }
            });
        </script>

        <div class="row gx-4">
            <div class="col-sm-12 col-12">
                <div class=" mb-4">
                    <div class="">
                        <!-- Custom tabs start -->
                        <div class="custom-tabs-container">
                            <!-- Nav tabs start -->
                            <ul class="nav nav-tabs d-flex flex-row flex-nowrap" id="customTab2" role="tablist">
                                @foreach ($companies as $company)
                                    <li class="nav-item" role="presentation">
                                        <a id="tab-one{{ $company->name }}" data-bs-toggle="tab" href="#one{{ $company->name }}"
                                            role="tab" aria-controls="one{{ $company->name }}"
                                            aria-selected="{{ $selectedCompany && $selectedCompany->id == $company->id ? 'true' : 'false' }}"
                                            class="nav-link {{ $selectedCompany && $selectedCompany->id == $company->id ? 'active' : '' }}">
                                            {{ $company->name }} Coy
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Nav tabs end -->

                            <!-- Tab content start -->
                            <div class="tab-content h-300">
                                @php
                                    $foundActiveTab = false;
                                @endphp
                                @for ($j = 0; $j < count($statistics); ++$j)
                                    @php
                                        $isActive = !$foundActiveTab && $selectedCompany->id == $statistics[$j]['company']->id;
                                    @endphp
                                    <div id="one{{$statistics[$j]['company']->name}}"
                                        class="tab-pane fade {{ $isActive ? 'show active' : '' }}" role="tabpanel">
                                        @php
                                            $companyId = $statistics[$j]['company']->id;
                                            $request = \App\Models\AttendanceRequest::where('company_id', $companyId)
                                                ->where('date', $date)
                                                ->where('attendenceType_id', $attendenceType->id)
                                                //->where('requested_by', auth()->id())
                                                //->where('status', 'pending')
                                                ->first();
                                        @endphp
                                        @if (Carbon\Carbon::parse($date)->isToday() || ($request && $request->status == 'approved'))
                                            <div class="justify-content-end">
                                                <form
                                                    action="{{ route('attendences.create', ['attendenceType' => $attendenceType->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <input type="text" name="date" value="{{ $date }}" hidden>
                                                        <div>
                                                            <label class="form-label" for="abc4">Platoon</label>
                                                            <select style="height:60%" class="form-select" name="platoon" required
                                                                id="platoons">
                                                                <option value="" disabled selected>Select a platoon</option>
                                                                @foreach ($statistics[$j]['company']->platoons as $platoon)
                                                                    @if ($platoon->today_attendence($attendenceType->id, $date)->isEmpty())
                                                                        <option value="{{ $platoon->id }}">{{ $platoon->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-4">
                                                            <button type="submit" class="btn btn-success">New</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="d-flex gap-2 justify-content-end">
                                                @if ($request && $request->status == 'pending')
                                                    <button class="btn btn-info btn-sm" disabled style="color:white">
                                                        Requested
                                                    </button>
                                                @elseif ($request && $request->status == 'approved' && $request->requested_by == auth()->user()->id)
                                                    <button class="btn btn-info btn-sm" disabled style="color:white">
                                                        Request Approved
                                                    </button>
                                                @else
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#statusModal{{ $companyId }}">
                                                        Request
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="modal fade" id="statusModal{{ $statistics[$j]['company']->id ?? '' }}"
                                                tabindex="-1"
                                                aria-labelledby="statusModalLabel{{ $statistics[$j]['company']->id ?? '' }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-info"
                                                                id="statusModalLabel{{ $statistics[$j]['company']->id ?? '' }}">
                                                                Request Attendance Record for
                                                                {{ $statistics[$j]['company']->description }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="requestForm" method="POST"
                                                                action="{{ route('attendance.store.request') }}">
                                                                @csrf
                                                                <input type="text" name="company_id"
                                                                    value="{{ $statistics[$j]['company']->id }}" hidden>
                                                                <input type="text" name="date" value="{{ $date }}" hidden>
                                                                <input type="text" name="attendenceType_id"
                                                                    value="{{ $attendenceType->id }}" hidden>
                                                                <div class="mb-3">
                                                                    <label for="reason{{ $statistics[$j]['company']->id }}"
                                                                        class="form-label fw-semibold">Reason</label>
                                                                    <textarea name="reason"
                                                                        id="reason{{ $statistics[$j]['company']->id }}" rows="4"
                                                                        class="form-control" placeholder="Enter reason..."></textarea>
                                                                </div>
                                                                <div class="d-flex gap-2 justify-content-end">
                                                                    <button type="submit" class="btn btn-sm btn-primary">Send
                                                                        Request</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Row starts -->
                                        <div class="row gx-4">
                                            <div class="col-sm-12 col-12">
                                                <div class="  mb-3">
                                                    <div class="">
                                                        <!-- Row starts -->
                                                        <div class="row gx-4 mt-1">
                                                            <!-- Attendence starts -->
                                                            <div class="col-xxl-3 col-sm-6 col-12">
                                                                <div class="card mb-4">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="p-3  me-3">
                                                                                <img src="/tps-smis/resources/assets/images/attendance.png"
                                                                                    style="height:50 !important; width:50"
                                                                                    alt="attendence image" />
                                                                            </div>
                                                                            <div class="p3 d-flex flex-column">
                                                                                <p class="m-0 ">Present</p>
                                                                                <h2 class="lh-1 opacity-50">
                                                                                    {{$statistics[$j]['statistics']['present']}}
                                                                                </h2>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mt-1">
                                                                                <a class="text-primary ms-4"
                                                                                    href="{{ route('today', ['company_id' => $companies[$j]->id, 'type' => $attendenceType->id, 'date' => $date, 'attendenceTypeId' => $attendenceType->id]) }}">
                                                                                    <span>View</span>
                                                                                </a>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Attendence  end. -->

                                                            <!-- Sick days starts -->
                                                            <div class="col-xxl-3 col-sm-6 col-12">
                                                                <div class="card mb-4">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="p-3  me-3">
                                                                                <img src="/tps-smis/resources/assets/images/bed.png"
                                                                                    style="height:50 !important; width:50"
                                                                                    alt="Sick image" />
                                                                            </div>
                                                                            <div class="p3 d-flex flex-column">
                                                                                <p class="m-0 ">Sick </p>
                                                                                <h2 class="lh-1 opacity-50">
                                                                                    {{$statistics[$j]['statistics']['sick']}}
                                                                                </h2>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mt-1">
                                                                                <a class="text-primary ms-4"
                                                                                    href="javascript:void(0);">
                                                                                    <span>View</span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Sick days  end. -->

                                                            <!-- Leave days starts -->
                                                            <div class="col-xxl-3 col-sm-6 col-12">
                                                                <div class="card mb-4">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="p-3  me-3">
                                                                                <img src="/tps-smis/resources/assets/images/leave.png"
                                                                                    style="height:50 !important; width:50"
                                                                                    alt="Leave image" />
                                                                            </div>
                                                                            <div class="p3 d-flex flex-column">
                                                                                <p class="m-0 ">Safari </p>
                                                                                <h2 class="lh-1 opacity-50">
                                                                                    {{$statistics[$j]['statistics']['safari']}}
                                                                                </h2>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mt-1">
                                                                                <a class="text-primary ms-4"
                                                                                    href="javascript:void(0);">
                                                                                    <span>View</span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Leave days  end. -->

                                                            <!-- MPS days starts -->
                                                            <div class="col-xxl-3 col-sm-6 col-12">
                                                                <div class="card mb-4">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="p-3  me-3">
                                                                                <img src="/tps-smis/resources/assets/images/prison.png"
                                                                                    style="height:50 !important; width:50"
                                                                                    alt="MPS image" />
                                                                            </div>
                                                                            <div class="p3 d-flex flex-column">
                                                                                <p class="m-0 ">MPS </p>
                                                                                <h2 class="lh-1 opacity-50">
                                                                                    {{$statistics[$j]['statistics']['mps']}}
                                                                                </h2>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mt-1">
                                                                                <a class="text-primary ms-4"
                                                                                    href="{{url("mps/" . $companies[$j]->id . "/company")}}">
                                                                                    <span>View</span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- MPS days  end. -->
                                                        </div>
                                                        <!-- Row ends -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Row ends -->

                                    </div>
                                    @php
                                        if ($isActive) {
                                            $foundActiveTab = true;
                                        }
                                    @endphp
                                @endfor
                            </div>
                            <!-- Tab content end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <h1>No students for the selected session.</h1>
    @endif
@endsection