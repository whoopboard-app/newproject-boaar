@extends('layouts.inspinia')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="font-weight-semibold mb-0">Dashboard</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-success mb-3 fs-15">
                    <strong>Welcome back!</strong> You're logged in successfully.
                </div>

                <h5 class="text-dark fs-14 mb-2">
                    Hello, <span class="fw-semibold">{{ Auth::user()->name }}</span>!
                </h5>

                <p class="text-muted fs-14 mb-0">
                    Welcome to your dashboard. This is built with the INSPINIA admin template integrated into Laravel.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="badge badge-soft-primary p-3 mb-3">
                    <i class="ti ti-user fs-28"></i>
                </div>
                <h4>Profile</h4>
                <p class="text-muted fs-15 mb-3">Manage your account settings and preferences.</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-soft-primary">Go to Profile</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="badge badge-soft-success p-3 mb-3">
                    <i class="ti ti-shield-check fs-28"></i>
                </div>
                <h4>Security</h4>
                <p class="text-muted fs-15 mb-3">Update your password and security settings.</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-soft-success">Security Settings</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="badge badge-soft-danger p-3 mb-3">
                    <i class="ti ti-rocket fs-28"></i>
                </div>
                <h4>Get Started</h4>
                <p class="text-muted fs-15 mb-3">Explore the features and get started with your project.</p>
                <a href="#" class="btn btn-soft-danger">Learn More</a>
            </div>
        </div>
    </div>
</div>
@endsection
