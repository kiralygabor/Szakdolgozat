@extends('layout')

@section('title', 'Facebook Data Deletion Instructions')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h1 class="h3 mb-4 fw-bold text-primary">Facebook Data Deletion Instructions</h1>
                    <p class="mb-4">
                        We use Facebook Login to make it easier for you to sign up and log into our application. 
                        If you wish to remove your activities and data associated with our app, you can follow these steps:
                    </p>

                    <ol class="list-group list-group-numbered mb-4 border-0">
                        <li class="list-group-item border-0 ps-3">Go to your Facebook Account's Settings & Privacy. Click <strong>Settings</strong>.</li>
                        <li class="list-group-item border-0 ps-3">Look for <strong>Apps and Websites</strong> and you will see all of the apps and websites you linked with your Facebook.</li>
                        <li class="list-group-item border-0 ps-3">Search and click on <strong>{{ config('app.name', 'Kicsimelo') }}</strong> in the list.</li>
                        <li class="list-group-item border-0 ps-3">Scroll and click <strong>Remove</strong>.</li>
                        <li class="list-group-item border-0 ps-3">Congratulations, you have successfully removed your app activities and data from Facebook.</li>
                    </ol>

                    <h4 class="h5 fw-bold mb-3">Deleting your account from our site</h4>
                    <p class="text-muted">
                        If you also want to completely delete your account from our platform:
                    </p>
                    <ul class="text-muted">
                        <li>Log into your account.</li>
                        <li>Go to your <strong>Profile Settings</strong>.</li>
                        <li>Click on the <strong>Delete Account</strong> at the bottom of the page.</li>
                        <li>Confirm the deletion. This will permanently remove all your data from our servers.</li>
                    </ul>

                    <div class="mt-5 text-center">
                        <a href="{{ route('index') }}" class="btn btn-primary px-4 py-2 rounded-pill">Return to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
