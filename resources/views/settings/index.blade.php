@extends('layouts.inspinia')

@section('title', 'App Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">App Settings</h4>
            <p class="text-muted fs-14">Manage your application settings and configurations</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Profile Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar-lg">
                        <span class="avatar-title rounded-circle bg-primary-subtle">
                            <i class="ti ti-user fs-2 text-primary"></i>
                        </span>
                    </div>
                </div>
                <h5 class="card-title mb-2">My Profile</h5>
                <p class="text-muted fs-14 mb-3">
                    Update your personal information, change password, and manage your account preferences.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-eye me-1"></i>View
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-clock me-2"></i>
                    <small>Last updated: {{ now()->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Team Member Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar-lg">
                        <span class="avatar-title rounded-circle bg-success-subtle">
                            <i class="ti ti-user-plus fs-2 text-success"></i>
                        </span>
                    </div>
                </div>
                <h5 class="card-title mb-2">Invite Team Member</h5>
                <p class="text-muted fs-14 mb-3">
                    Send invitations to new team members, manage roles, and track pending invitations.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('team.manage') }}" class="btn btn-sm btn-success">
                        <i class="ti ti-eye me-1"></i>View
                    </a>
                    <a href="{{ route('team.manage') }}" class="btn btn-sm btn-outline-success">
                        <i class="ti ti-user-plus me-1"></i>Invite
                    </a>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-users me-2"></i>
                    <small>5 active members, 3 pending</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar-lg">
                        <span class="avatar-title rounded-circle bg-warning-subtle">
                            <i class="ti ti-tags fs-2 text-warning"></i>
                        </span>
                    </div>
                </div>
                <h5 class="card-title mb-2">Categories</h5>
                <p class="text-muted fs-14 mb-3">
                    Manage changelog categories, add new categories, and customize category colors.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('categories.manage') }}" class="btn btn-sm btn-warning">
                        <i class="ti ti-eye me-1"></i>View
                    </a>
                    <a href="{{ route('categories.manage') }}" class="btn btn-sm btn-outline-warning">
                        <i class="ti ti-plus me-1"></i>Add New
                    </a>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-tag me-2"></i>
                    <small>12 active categories</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Settings Row -->
<div class="row mt-3">
    <!-- General Settings Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar-lg">
                        <span class="avatar-title rounded-circle bg-info-subtle">
                            <i class="ti ti-settings fs-2 text-info"></i>
                        </span>
                    </div>
                </div>
                <h5 class="card-title mb-2">General Settings</h5>
                <p class="text-muted fs-14 mb-3">
                    Configure application name, logo, timezone, language and other general settings.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-info">
                        <i class="ti ti-eye me-1"></i>View
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-info">
                        <i class="ti ti-settings me-1"></i>Configure
                    </a>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-check-circle me-2"></i>
                    <small>All settings configured</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Settings Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar-lg">
                        <span class="avatar-title rounded-circle bg-danger-subtle">
                            <i class="ti ti-bell fs-2 text-danger"></i>
                        </span>
                    </div>
                </div>
                <h5 class="card-title mb-2">Notifications</h5>
                <p class="text-muted fs-14 mb-3">
                    Manage email notifications, push notifications, and notification preferences.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-danger">
                        <i class="ti ti-eye me-1"></i>View
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-danger">
                        <i class="ti ti-bell me-1"></i>Manage
                    </a>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-mail me-2"></i>
                    <small>Email notifications enabled</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Settings Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar-lg">
                        <span class="avatar-title rounded-circle bg-secondary-subtle">
                            <i class="ti ti-shield-lock fs-2 text-secondary"></i>
                        </span>
                    </div>
                </div>
                <h5 class="card-title mb-2">Security</h5>
                <p class="text-muted fs-14 mb-3">
                    Manage password, two-factor authentication, active sessions, and security logs.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-secondary">
                        <i class="ti ti-eye me-1"></i>View
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-lock me-1"></i>Configure
                    </a>
                </div>
            </div>
            <div class="card-footer border-top">
                <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-check me-2"></i>
                    <small>2FA not enabled</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
