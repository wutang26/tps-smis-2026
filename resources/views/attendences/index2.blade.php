@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/attendences/">Today Attendence Summary</a>
                </li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
@endsession
<div class="d-flex  justify-content-end">
    <div class="col-4 z-index:1">
        <form action="{{url('attendences/create/' . $page->id)}}" method="POST">
            @csrf
            @method('POST')
            <div class=" d-flex gap-2 justify-content-end">
                <div class="">
                    <label for="">Company </label>
                    <select style="height:60%" class="form-select" name="company" id="companies" required
                        aria-label="Default select example">
                        <option value="">company</option>
                        @foreach ($companies as $company)
                            <option value="{{$company->name}}">{{$company->name}}</option>
                        @endforeach

                    </select>
                </div>

                <div class=""> <label class="form-label" for="abc4">Platoon</label>
                    <select style="height:60%" class="form-select" name="platoon" required id=""
                        aria-label="Default select example">
                        <option value="">platoon</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success ">New
                    </button>
                </div>
            </div>
    </div>
    </form>
</div>


<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class=" mb-4">
            <div class="">
                <!-- Custom tabs start -->
                <div class="custom-tabs-container">
                    <!-- Nav tabs start -->
                    <ul class="nav nav-tabs" id="customTab2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                                aria-controls="oneA" aria-selected="true"> HQ Coy</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab"
                                aria-controls="twoA" aria-selected="false">A Coy</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab"
                                aria-controls="threeA" aria-selected="false">B Coy</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab"
                                aria-controls="fourA" aria-selected="false">C Coy</a>
                        </li>
                    </ul>
                    <!-- Nav tabs end -->

                    <!-- Tab content start -->
                    <div class="tab-content h-300">
                        <div class="tab-pane fade show active" id="oneA" role="tabpanel">
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
                                                                    <p class="m-0 ">Attended</p>
                                                                    <h2 class="lh-1 opacity-50">
                                                                        {{$statistics['HQ']['present']}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url('/today/1/' . $page->id)}}">
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
                                                                        {{$statistics['HQ']['sick']}}
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
                                                                        {{$statistics['HQ']['safari']}}
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
                                                                        {{$statistics['HQ']['mps']}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url("mps/HQ/company")}}">
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
                        <div class="tab-pane fade" id="twoA" role="tabpanel">
                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="col-sm-12 col-12">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <!-- Row starts -->

                                            <div class="row gx-4 mt-1">
                                                <!-- Attendence starts -->

                                                <!-- Start of A Coy -->
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
                                                                    <p class="m-0 ">Attended</p>
                                                                    <h2 class="lh-1 opacity-50">
                                                                        {{$statistics['A']['present']}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url('/today/2/' . $page->id)}}">
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
                                                                        {{$statistics['A']['sick']}}
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
                                                                        {{$statistics['A']['safari']}}
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
                                                                        {{$statistics['A']['mps']}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url("mps/A/company")}}">
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
                        <div class="tab-pane fade show " id="threeA" role="tabpanel">

                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="col-sm-12 col-12">
                                    <div class="card border mb-3">
                                        <div class="card-body">
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
                                                                    <p class="m-0 ">Attended</p>
                                                                    <h2 class="lh-1 opacity-50">
                                                                        {{$statistics['B']['present']}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url('/today/3/' . $page->id)}}">
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
                                                                        {{$statistics['B']['sick']}}
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
                                                                        {{$statistics['B']['safari']}}
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
                                                                        {{$statistics['B']['mps']}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url("mps/B/company")}}">
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
                        <div class="tab-pane fade show" id="fourA" role="tabpanel">

                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="col-sm-12 col-12">
                                    <div class="card border mb-3">
                                        <div class="card-body">
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
                                                                    <p class="m-0 ">Attended</p>
                                                                    <h2 class="lh-1 opacity-50">
                                                                        {{$statistics['C']['present']}}</h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url('/today/4/' . $page->id)}}">
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
                                                                        {{$statistics['C']['sick']}} </h2>
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
                                                                        {{$statistics['C']['safari']}}</h2>
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
                                                                        {{$statistics['C']['mps']}}</h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mt-1">
                                                                    <a class="text-primary ms-4"
                                                                        href="{{url("mps/C/company")}}">
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
                    </div>
                    <!-- Tab content end -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection