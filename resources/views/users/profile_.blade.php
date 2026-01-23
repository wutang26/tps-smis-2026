@extends('layouts.main')


@section('style')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<style>
.profile-header {
    background-image: url('/tps-rms/resources/assets/images/profile/bg-profile.jpg');
    background-size: cover;
    background-position: center;
    height: 200px;
    position: relative;
}

.profile-header img {
    position: absolute;
    bottom: -50px;
    left: 20px;
    border-radius: 50%;
    border: 5px solid white;
}

.profile-header .profile-info {
    position: absolute;
    bottom: 20px;
    left: 150px;
    color: white;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
}
</style>

@endsection
@section('content')

<!-- Row starts -->
<div class="container mt-5">
    <div class="profile-header"> <img src="/tps-rms/resources/assets/images/profile/avatar.jpg" alt="Profile Picture" />
        <div class="profile-info">
            <h3>Michael A. Franklin</h3>
            <p>User Experience Specialist</p>
            <p>California, United States</p>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3"> <button class="btn btn-danger me-2">Edit Profile</button> <button
            class="btn btn-primary">Following</button> </div>
    <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation"> <button class="nav-link" id="about-tab" data-bs-toggle="tab"
                data-bs-target="#about" type="button" role="tab" aria-controls="about"
                aria-selected="false">About</button> </li>
        <li class="nav-item" role="presentation"> <button class="nav-link" id="activities-tab" data-bs-toggle="tab"
                data-bs-target="#activities" type="button" role="tab" aria-controls="activities"
                aria-selected="false">Activities</button> </li>
        <li class="nav-item" role="presentation"> <button class="nav-link" id="settings-tab" data-bs-toggle="tab"
                data-bs-target="#settings" type="button" role="tab" aria-controls="settings"
                aria-selected="false">Settings</button> </li>
        <li class="nav-item" role="presentation"> <button class="nav-link active" id="projects-tab" data-bs-toggle="tab"
                data-bs-target="#projects" type="button" role="tab" aria-controls="projects"
                aria-selected="true">Projects</button> </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">About content</div>
        <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">Activities content
        </div>
        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">Settings content</div>
        <div class="tab-pane fade show active" id="projects" role="tabpanel" aria-labelledby="projects-tab">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Velonic</td>
                        <td>01/01/2025</td>
                        <td>31/01/2025</td>
                        <td>Work in Progress</td>
                        <td>Techzaa</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Material Admin</td>
                        <td>01/02/2025</td>
                        <td>28/02/2025</td>
                        <td>Pending</td>
                        <td>Techzaa</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Pixel Admin</td>
                        <td>01/03/2025</td>
                        <td>31/03/2025</td>
                        <td>Done</td>
                        <td>Techzaa</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Marvin Admin</td>
                        <td>01/04/2025</td>
                        <td>30/04/2025</td>
                        <td>Work in Progress</td>
                        <td>Techzaa</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Conquer Admin</td>
                        <td>01/05/2025</td>
                        <td>31/05/2025</td>
                        <td>Coming soon</td>
                        <td>Techzaa</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>


<!-- Row ends -->
@endsection