@extends('layouts.inspinia')

@section('title', 'App Settings')

@section('content')
<style>
    .ti {
        color: #1379F0;
    }

    small {
        font-size: 13px;
        color: #676A72;
    }
    .card-body p {
        color: #676A72;
    }

    .card .card-title {
        color: #2C2D30;
    }
</style>
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
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('profile.edit') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-user fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Personal Info</h5>
                    <p class="text-muted fs-14 mb-3">
                        We're open to partnerships, guest posts, promo bannersand more.
                    </p>
                    <div class="d-flex gap-2">
                        <small>Last updated: {{ now()->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Invite Team Member Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('team.manage') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-user-plus fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Invite Team Member</h5>
                    <p class="text-muted fs-14 mb-3">
                        Send invitations to new team members, manage roles, and track pending invitations. </p>
                    <div class="d-flex gap-2">
                        <small>5 active members, 3 pending</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Categories Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('categories.manage') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-tags fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Change Log categories</h5>
                    <p class="text-muted fs-14 mb-3">
                        Manage changelog categories, add new categories, and customize category colors. </p>
                    <div class="d-flex gap-2">
                        <small>12 active categories</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Additional Settings Row -->
<div class="row mt-3">
    <!-- General Settings Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('settings.general') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-settings fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">General Settings</h5>
                    <p class="text-muted fs-14 mb-3">
                        Configure application name, logo, website URL, unique URL and other general settings.
                    </p>
                    <div class="d-flex gap-2">
                        <small>All settings configured</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Notifications Settings Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="#">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-bell fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Notifications</h5>
                    <p class="text-muted fs-14 mb-3">
                        Manage email notifications, push notifications, and notification preferences.
                    </p>
                    <div class="d-flex gap-2">
                        <small>Email notifications enabled</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Security Settings Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="#">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-shield-lock fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Security</h5>
                    <p class="text-muted fs-14 mb-3">
                        Manage password, two-factor authentication, active sessions, and security logs.
                    </p>
                    <div class="d-flex gap-2">
                        <small>2FA not enabled</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row mt-3">
    <!-- RoadMap Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('roadmap.index') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-route fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">RoadMap</h5>
                    <p class="text-muted fs-14 mb-3">
                        Manage roadmap statuses, customize colors, and define your product development workflow.
                    </p>
                    <div class="d-flex gap-2">
                        <small>Manage status workflow</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Feedback Category Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('feedback-category.index') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-message-2 fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Feedback Category</h5>
                    <p class="text-muted fs-14 mb-3">
                        Manage feedback categories with auto-generated colors and organize user feedback efficiently.
                    </p>
                    <div class="d-flex gap-2">
                        <small>Organize feedback types</small>
                    </div>
                </div>
            </div>
        </a>
    </div>



    <!-- App Configuration Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('configuration.index') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-adjustments-horizontal fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">App Configuration</h5>
                    <p class="text-muted fs-14 mb-3">
                        Configure module settings for Feedback, Changelog, Testimonials, Email Templates, and Knowledge Board.
                    </p>
                    <div class="d-flex gap-2">
                        <small>Module-level configurations</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Third Settings Row -->
<div class="row mt-3">
    <!-- Rating Settings Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('settings.rating') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-star fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Rating Settings</h5>
                    <p class="text-muted fs-14 mb-3">
                        Configure article rating options, choose rating type, and select where ratings appear.
                    </p>
                    <div class="d-flex gap-2">
                        <small>User feedback ratings</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Themes (Public) Card -->
    <div class="col-lg-4 col-md-6 mb-3 d-flex">
        <a href="{{ route('settings.themes') }}">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="avatar-lg">
                            <i class="ti ti-palette fs-2"></i>
                        </div>
                    </div>
                    <h5 class="card-title mb-2">Themes (Public)</h5>
                    <p class="text-muted fs-14 mb-3">
                        Customize Knowledge Board appearance, header/footer colors, intro text, and SEO settings.
                    </p>
                    <div class="d-flex gap-2">
                        <small>Customize public pages</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

</div>
@endsection