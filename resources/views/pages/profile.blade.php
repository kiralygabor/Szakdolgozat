@extends('layout')

@section('content')
<div class="container my-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Side Nav (desktop) -->
        <div class="col-md-4 d-none d-md-block">
            <div class="card">
                <div class="card-body">
                    <ul class="nav flex-column nav-pills" id="settingsTab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <i class="fas fa-user me-2"></i>Profile Information
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="account-tab" data-bs-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="false">
                                <i class="fas fa-cog me-2"></i>Account Settings
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="security-tab" data-bs-toggle="pill" href="#security" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt me-2"></i>Security
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="notification-tab" data-bs-toggle="pill" href="#notification" role="tab" aria-controls="notification" aria-selected="false">
                                <i class="fas fa-bell me-2"></i>Notification
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="billing-tab" data-bs-toggle="pill" href="#billing" role="tab" aria-controls="billing" aria-selected="false">
                                <i class="fas fa-credit-card me-2"></i>Billing
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div class="col-12 d-md-none mb-3">
            <ul class="nav nav-tabs" id="mobileSettingsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="m-profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="fas fa-user"></i>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="m-account-tab" data-bs-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">
                        <i class="fas fa-cog"></i>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="m-security-tab" data-bs-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">
                        <i class="fas fa-shield-alt"></i>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="m-notification-tab" data-bs-toggle="tab" href="#notification" role="tab" aria-controls="notification" aria-selected="false">
                        <i class="fas fa-bell"></i>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="m-billing-tab" data-bs-toggle="tab" href="#billing" role="tab" aria-controls="billing" aria-selected="false">
                        <i class="fas fa-credit-card"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body tab-content" id="settingsTabContent">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h6>Profile Information</h6>
                        <hr>
                        <form>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" value="Kenneth Valdez">
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Your Bio</label>
                                <textarea class="form-control" id="bio" rows="3">A front-end developer...</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">Website URL</label>
                                <input type="text" class="form-control" id="url" value="http://example.com">
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" value="Bay Area, San Francisco, CA">
                            </div>
                            <button type="button" class="btn btn-primary">Update Profile</button>
                            <button type="reset" class="btn btn-light">Reset</button>
                        </form>
                    </div>

                    <!-- Account Tab -->
                    <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <h6>Account Settings</h6>
                        <hr>
                        <form>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" value="kennethvaldez">
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="text-danger">Delete Account</label>
                                <p class="text-muted small">Once you delete your account, there is no going back.</p>
                                <button class="btn btn-danger" type="button">Delete Account</button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <h6>Security Settings</h6>
                        <hr>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Change Password</label>
                                <input type="password" class="form-control mb-1" placeholder="Old Password">
                                <input type="password" class="form-control mb-1" placeholder="New Password">
                                <input type="password" class="form-control" placeholder="Confirm New Password">
                            </div>
                        </form>
                    </div>

                    <!-- Notification Tab -->
                    <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                        <h6>Notification Settings</h6>
                        <hr>
                        <form>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="emailAlerts" checked>
                                <label class="form-check-label" for="emailAlerts">
                                    Email each time a vulnerability is found
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="emailSummary" checked>
                                <label class="form-check-label" for="emailSummary">
                                    Email a digest summary of vulnerabilities
                                </label>
                            </div>
                        </form>
                    </div>

                    <!-- Billing Tab -->
                    <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                        <h6>Billing Settings</h6>
                        <hr>
                        <button class="btn btn-info mb-2" type="button">Add Payment Method</button>
                        <div class="border p-3 bg-light text-center">No payments yet.</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
