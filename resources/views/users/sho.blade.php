@extends('layouts.main')

@section('content')
                            <!-- anza -->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container  container-xxl ">

                                    <!--begin::Navbar-->
                                    <div class="card card-flush mb-9" id="kt_user_profile_panel">
                                        <!--begin::Hero nav-->
                                        <div class="card-header rounded-top bgi-size-cover h-200px"
                                            style="background-position: 100% 50%; background-image:url('resources/2/assets/media/misc/profile-head-bg.jpg')">
                                        </div>
                                        <!--end::Hero nav-->

                                        <!--begin::Body-->
                                        <div class="card-body mt-n19">
                                            <!--begin::Details-->
                                            <div class="m-0">
                                                <!--begin: Pic-->
                                                <div class="d-flex flex-stack align-items-end pb-4 mt-n19">
                                                    <div
                                                        class="symbol symbol-125px symbol-lg-150px symbol-fixed position-relative mt-n3">
                                                        <img src="resources/2/assets/media/avatars/300-3.jpg" alt="image"
                                                            class="border border-white border-4"
                                                            style="border-radius: 20px" />
                                                        <div
                                                            class="position-absolute translate-middle bottom-0 start-100 ms-n1 mb-9 bg-success rounded-circle h-15px w-15px">
                                                        </div>
                                                    </div>

                                                    <!--begin::Toolbar-->
                                                    <div class="me-0">
                                                        <button
                                                            class="btn btn-icon btn-sm btn-active-color-primary  justify-content-end pt-3"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end">
                                                            <i class="fonticon-settings fs-2"></i>
                                                        </button>

                                                        <!--begin::Menu 3-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                                            data-kt-menu="true">
                                                            <!--begin::Heading-->
                                                            <div class="menu-item px-3">
                                                                <div
                                                                    class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                                    Payments
                                                                </div>
                                                            </div>
                                                            <!--end::Heading-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Create Invoice
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link flex-stack px-3">
                                                                    Create Payment

                                                                    <span class="ms-2" data-bs-toggle="tooltip"
                                                                        title="Specify a target name for future usage and reference">
                                                                        <i class="ki-duotone ki-information fs-6"><span
                                                                                class="path1"></span><span
                                                                                class="path2"></span><span
                                                                                class="path3"></span></i> </span>
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Generate Bill
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                                data-kt-menu-placement="right-end">
                                                                <a href="#" class="menu-link px-3">
                                                                    <span class="menu-title">Subscription</span>
                                                                    <span class="menu-arrow"></span>
                                                                </a>

                                                                <!--begin::Menu sub-->
                                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Plans
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Billing
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Statements
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu separator-->
                                                                    <div class="separator my-2"></div>
                                                                    <!--end::Menu separator-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <div class="menu-content px-3">
                                                                            <!--begin::Switch-->
                                                                            <label
                                                                                class="form-check form-switch form-check-custom form-check-solid">
                                                                                <!--begin::Input-->
                                                                                <input
                                                                                    class="form-check-input w-30px h-20px"
                                                                                    type="checkbox" value="1"
                                                                                    checked="checked"
                                                                                    name="notifications" />
                                                                                <!--end::Input-->

                                                                                <!--end::Label-->
                                                                                <span
                                                                                    class="form-check-label text-muted fs-6">
                                                                                    Recuring
                                                                                </span>
                                                                                <!--end::Label-->
                                                                            </label>
                                                                            <!--end::Switch-->
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu sub-->
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3 my-1">
                                                                <a href="#" class="menu-link px-3">
                                                                    Settings
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                        </div>
                                                        <!--end::Menu 3-->
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                                <!--end::Pic-->

                                                <!--begin::Info-->
                                                <div class="d-flex flex-stack flex-wrap align-items-end">
                                                    <!--begin::User-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Name-->
                                                        <div class="d-flex align-items-center mb-2">
                                                            <a href="#"
                                                                class="text-gray-800 text-hover-primary fs-2 fw-bolder me-1">Bessie
                                                                Cooper</a>
                                                            <a href="#" class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="right" title="Account is verified">
                                                                <i class="ki-duotone ki-verify fs-1 text-primary"><span
                                                                        class="path1"></span><span
                                                                        class="path2"></span></i> </a>
                                                        </div>
                                                        <!--end::Name-->

                                                        <!--begin::Text-->
                                                        <span class="fw-bold text-gray-600 fs-6 mb-2 d-block">
                                                            Design is like a fart. If you have to force it, it’s
                                                            probably shit.
                                                        </span>
                                                        <!--end::Text-->

                                                        <!--begin::Info-->
                                                        <div
                                                            class="d-flex align-items-center flex-wrap fw-semibold fs-7 pe-2">
                                                            <a href="#"
                                                                class="d-flex align-items-center text-gray-500 text-hover-primary">
                                                                UI/UX Design
                                                            </a>
                                                            <span
                                                                class="bullet bullet-dot h-5px w-5px bg-gray-500 mx-3"></span>
                                                            <a href="#"
                                                                class="d-flex align-items-center text-gray-500 text-hover-primary">
                                                                Austin, TX
                                                            </a>
                                                            <span
                                                                class="bullet bullet-dot h-5px w-5px bg-gray-500 mx-3"></span>
                                                            <a href="#" class="text-gray-500 text-hover-primary">
                                                                3,450 Followers
                                                            </a>
                                                        </div>
                                                        <!--end::Info-->
                                                    </div>
                                                    <!--end::User-->

                                                    <!--begin::Actions-->
                                                    <div class="d-flex">
                                                        <a href="#" class="btn btn-sm btn-light me-3"
                                                            id="kt_drawer_chat_toggle">Send Message</a>

                                                        <button class="btn btn-sm btn-primary"
                                                            id="kt_user_follow_button">
                                                            <i class="ki-duotone ki-check fs-2 d-none"></i>
                                                            <!--begin::Indicator label-->
                                                            <span class="indicator-label">
                                                                Follow</span>
                                                            <!--end::Indicator label-->

                                                            <!--begin::Indicator progress-->
                                                            <span class="indicator-progress">
                                                                Please wait... <span
                                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                            </span>
                                                            <!--end::Indicator progress-->
                                                        </button>
                                                    </div>
                                                    <!--end::Actions-->
                                                </div>
                                                <!--end::Info-->
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                    </div>
                                    <!--end::Navbar-->

                                    <!--begin::Nav items-->
                                    <div id="kt_user_profile_nav"
                                        class="rounded bg-gray-200 d-flex flex-stack flex-wrap mb-9 p-2"
                                        data-kt-sticky="true" data-kt-sticky-name="sticky-profile-navs"
                                        data-kt-sticky-offset="{default: false, lg: '200px'}"
                                        data-kt-sticky-width="{target: '#kt_user_profile_panel'}"
                                        data-kt-sticky-left="auto" data-kt-sticky-top="70px"
                                        data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                                        <!--begin::Nav-->
                                        <ul class="nav flex-wrap border-transparent">
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    active" href="overview.html">

                                                    Overview </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="settings.html">

                                                    Settings </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="security.html">

                                                    Security </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="activity.html">

                                                    Activity </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="billing.html">

                                                    Billing </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="statements.html">

                                                    Statements </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="referrals.html">

                                                    Referrals </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="api-keys.html">

                                                    API Keys </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="logs.html">

                                                    Logs </a>
                                            </li>
                                            <!--end::Nav item-->
                                        </ul>
                                        <!--end::Nav-->
                                    </div>
                                    <!--end::Nav items-->
                                    <!--begin::details View-->
                                    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                                        <!--begin::Card header-->
                                        <div class="card-header cursor-pointer">
                                            <!--begin::Card title-->
                                            <div class="card-title m-0">
                                                <h3 class="fw-bold m-0">Profile Details</h3>
                                            </div>
                                            <!--end::Card title-->

                                            <!--begin::Action-->
                                            <a href="settings.html"
                                                class="btn btn-sm btn-primary align-self-center">Edit Profile</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--begin::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body p-9">
                                            <!--begin::Row-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">Max Smith</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Company</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <span class="fw-semibold text-gray-800 fs-6">Keenthemes</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">
                                                    Contact Phone

                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Phone number must be active">
                                                        <i class="ki-duotone ki-information fs-7"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i> </span>
                                                </label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8 d-flex align-items-center">
                                                    <span class="fw-bold fs-6 text-gray-800 me-2">044 3276 454
                                                        935</span>
                                                    <span class="badge badge-success">Verified</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Company Site</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <a href="#"
                                                        class="fw-semibold fs-6 text-gray-800 text-hover-primary">keenthemes.com</a>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">
                                                    Country

                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Country of origination">
                                                        <i class="ki-duotone ki-information fs-7"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i> </span>
                                                </label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">Germany</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Communication</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">Email, Phone</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-10">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Allow Changes</label>
                                                <!--begin::Label-->

                                                <!--begin::Label-->
                                                <div class="col-lg-8">
                                                    <span class="fw-semibold fs-6 text-gray-800">Yes</span>
                                                </div>
                                                <!--begin::Label-->
                                            </div>
                                            <!--end::Input group-->


                                            <!--begin::Notice-->
                                            <div
                                                class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                                                <!--begin::Icon-->
                                                <i class="ki-duotone ki-information fs-2tx text-warning me-4"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span></i>
                                                <!--end::Icon-->

                                                <!--begin::Wrapper-->
                                                <div class="d-flex flex-stack flex-grow-1 ">
                                                    <!--begin::Content-->
                                                    <div class=" fw-semibold">
                                                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>

                                                        <div class="fs-6 text-gray-700 ">Your payment was declined. To
                                                            start using tools, please <a class="fw-bold"
                                                                href="billing.html">Add Payment Method</a>.</div>
                                                    </div>
                                                    <!--end::Content-->

                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::details View-->


                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Table Widget 5-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->

            
@endsection