<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-sidenav-size="sm-hover">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style">

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')
</head>
<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- Sidenav Menu Start -->
        <div class="sidenav-menu">

            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="logo">
                <span class="logo logo-light">
                    <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" alt="logo"></span>
                    <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo"></span>
                </span>

                <span class="logo logo-dark">
                    <span class="logo-lg"><img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo"></span>
                    <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo"></span>
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <button class="button-sm-hover">
                <i class="ti ti-menu-4 fs-22 align-middle"></i>
            </button>

            <!-- Full Sidebar Menu Close Button -->
            <button class="button-close-fullsidebar">
                <i class="ti ti-x align-middle"></i>
            </button>

            <div class="scrollbar" data-simplebar>

                <!--- Sidenav Menu -->
                <ul class="side-nav">
                    <li class="side-nav-title">Menu</li>

                    <!-- Dashboard -->
                    <li class="side-nav-item">
                        <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    <!-- Changelog -->
                    <li class="side-nav-item">
                        <a href="{{ route('changelog.index') }}" class="side-nav-link {{ request()->routeIs('changelog.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-timeline"></i></span>
                            <span class="menu-text">Changelog</span>
                        </a>
                    </li>

                    <!-- Feedback -->
                    <li class="side-nav-item">
                        <a href="{{ route('feedback.index') }}" class="side-nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-message-dots"></i></span>
                            <span class="menu-text">Feedback</span>
                        </a>
                    </li>

                    <!-- Roadmap -->
                    <li class="side-nav-item">
                        <a href="{{ route('roadmap.index') }}" class="side-nav-link {{ request()->routeIs('roadmap.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-map-pin"></i></span>
                            <span class="menu-text">Roadmap</span>
                        </a>
                    </li>

                    <!-- Testimonials -->
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-star"></i></span>
                            <span class="menu-text">Testimonials</span>
                        </a>
                    </li>

                    <!-- Knowledge Board -->
                    <li class="side-nav-item">
                        <a href="{{ route('knowledge-board.index') }}" class="side-nav-link {{ request()->routeIs('knowledge-board.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-book"></i></span>
                            <span class="menu-text">Knowledge Board</span>
                        </a>
                    </li>

                    <!-- Research Repo -->
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-search"></i></span>
                            <span class="menu-text">Research Repo</span>
                        </a>
                    </li>

                    <!-- Subscribe -->
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-bell"></i></span>
                            <span class="menu-text">Subscribe</span>
                        </a>
                    </li>

                    <!-- Subscribe List -->
                    <li class="side-nav-item">
                        <a href="{{ route('subscribers.index') }}" class="side-nav-link {{ request()->routeIs('subscribers.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-list"></i></span>
                            <span class="menu-text">Subscribe List</span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-2">Customer Insights</li>

                    <!-- Segmentations -->
                    <li class="side-nav-item">
                        <a href="{{ route('user-segment.index') }}" class="side-nav-link {{ request()->routeIs('user-segment.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-layout-grid"></i></span>
                            <span class="menu-text">Segmentations</span>
                        </a>
                    </li>

                    <!-- Personas -->
                    <li class="side-nav-item">
                        <a href="{{ route('personas.index') }}" class="side-nav-link {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-users-group"></i></span>
                            <span class="menu-text">Personas</span>
                        </a>
                    </li>

                    <!-- Journey Mapping -->
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-route"></i></span>
                            <span class="menu-text">Journey Mapping</span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-2">Settings</li>

                    <!-- App Settings -->
                    <li class="side-nav-item">
                        <a href="{{ route('settings.index') }}" class="side-nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="ti ti-settings"></i></span>
                            <span class="menu-text">App Settings</span>
                        </a>
                    </li>
                    <!-- View Your Website -->
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link" target="_blank">
                            <span class="menu-icon"><i class="ti ti-external-link"></i></span>
                            <span class="menu-text">View Your Website</span>
                        </a>
                    </li>

                    <!-- Your Widget -->
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-box"></i></span>
                            <span class="menu-text">Your Widget</span>
                        </a>
                    </li>

                    <!-- Sign Out -->
                    <li class="side-nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                            @csrf
                        </form>
                        <a href="#" class="side-nav-link" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                            <span class="menu-icon"><i class="ti ti-logout"></i></span>
                            <span class="menu-text">Sign Out</span>
                        </a>
                    </li>

                    @yield('sidebar-menu')

                </ul>
            </div>
        </div>
        <!-- Sidenav Menu End -->

        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="container-fluid topbar-menu pt-1">
                <div class="d-flex align-items-center gap-2">
                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="{{ route('dashboard') }}" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="{{ route('dashboard') }}" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="sidenav-toggle-button btn btn-primary btn-icon d-flex mt-2">
                        <i class="ti ti-menu-4 fs-22"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <!-- Help & Support Dropdown -->
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-help fs-22"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Help & Support</h6>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-book text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Documentation</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-video text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Video Tutorials</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-message-circle text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Contact Support</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-file-text text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Submit Ticket</span>
                            </a>
                        </div>
                    </div>

                    <!-- Alert/Notifications Dropdown -->
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar rounded-circle position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-bell fs-22"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg">
                            <div class="dropdown-header">
                                <h6 class="mb-0">Notifications</h6>
                                <span class="badge bg-danger-subtle text-danger">3 New</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                <i class="ti ti-message-dots"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">New feedback received</h6>
                                        <div class="fs-13 text-muted">
                                            <p class="mb-1">You have 5 new feedback items</p>
                                            <p class="mb-0"><i class="ti ti-clock"></i> 2 hours ago</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                                <i class="ti ti-user-plus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">New team member added</h6>
                                        <div class="fs-13 text-muted">
                                            <p class="mb-1">John joined your team</p>
                                            <p class="mb-0"><i class="ti ti-clock"></i> 5 hours ago</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                <i class="ti ti-alert-triangle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">System update available</h6>
                                        <div class="fs-13 text-muted">
                                            <p class="mb-1">Version 2.0 is ready to install</p>
                                            <p class="mb-0"><i class="ti ti-clock"></i> 1 day ago</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center text-primary" href="#">
                                View All Notifications <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Activity Dropdown -->
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-activity fs-22"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg">
                            <div class="dropdown-header">
                                <h6 class="mb-0">Recent Activity</h6>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="px-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-check text-success fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fs-14">Task Completed</h6>
                                        <p class="text-muted fs-13 mb-0">Updated product documentation</p>
                                        <small class="text-muted">10 minutes ago</small>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-edit text-primary fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fs-14">Profile Updated</h6>
                                        <p class="text-muted fs-13 mb-0">Changed profile picture</p>
                                        <small class="text-muted">1 hour ago</small>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-upload text-warning fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fs-14">File Uploaded</h6>
                                        <p class="text-muted fs-13 mb-0">Uploaded new changelog entry</p>
                                        <small class="text-muted">3 hours ago</small>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-settings text-info fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fs-14">Settings Changed</h6>
                                        <p class="text-muted fs-13 mb-0">Updated notification preferences</p>
                                        <small class="text-muted">Yesterday</small>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center text-primary" href="#">
                                View All Activity <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Profile Image Dropdown -->
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar rounded-circle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="avatar-title rounded-circle bg-primary text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- User Info Header -->
                            <div class="dropdown-header pb-2">
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">Administrator</small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <!-- Profile Menu Items -->
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="ti ti-user text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">My Profile</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-lock text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Change Password</span>
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-adjustments text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Preference</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="topbar-logout-form">
                                @csrf
                            </form>
                            <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();">
                                <i class="ti ti-logout text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="container-fluid mt-3">
                @yield('content')
            </div>
            <!-- container -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start">
                            © {{ date('Y') }} {{ config('app.name', 'Laravel') }}
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end d-none d-md-block">
                                Powered by INSPINIA
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
