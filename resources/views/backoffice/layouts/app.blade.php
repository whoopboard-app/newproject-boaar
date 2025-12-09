<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Backoffice @yield('title', 'Dashboard')</title>

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

    <style>
        .backoffice-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .backoffice-sidebar {
            width: 250px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        .backoffice-sidebar .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .backoffice-sidebar .sidebar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .backoffice-sidebar .sidebar-brand:hover {
            color: #e2e8f0;
        }
        .backoffice-sidebar .sidebar-nav {
            padding: 1rem 0;
        }
        .backoffice-sidebar .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 1rem;
        }
        .backoffice-sidebar .nav-item {
            padding: 0;
        }
        .backoffice-sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .backoffice-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .backoffice-sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #3b82f6;
        }
        .backoffice-sidebar .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .backoffice-main {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .backoffice-topbar {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .backoffice-topbar .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* Content */
        .backoffice-content {
            padding: 2rem;
            background-color: #f8fafc;
            flex: 1;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }
        .stat-card .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Recent Cards */
        .recent-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .recent-card .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        .recent-item {
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .recent-item:last-child {
            border-bottom: none;
        }

        /* Footer */
        .backoffice-footer {
            background: white;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            padding: 1rem 2rem;
            text-align: center;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .backoffice-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .backoffice-sidebar.show {
                transform: translateX(0);
            }
            .backoffice-main {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="backoffice-wrapper">
        <!-- Sidebar -->
        <aside class="backoffice-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('backoffice.dashboard') }}" class="sidebar-brand">
                    <i class="ti ti-shield-check"></i>
                    Backoffice
                </a>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">Main</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('backoffice.dashboard') }}" class="nav-link {{ request()->routeIs('backoffice.dashboard') ? 'active' : '' }}">
                            <i class="ti ti-dashboard"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>

                <div class="nav-section">Management</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('backoffice.clients.index') }}" class="nav-link {{ request()->routeIs('backoffice.clients.*') ? 'active' : '' }}">
                            <i class="ti ti-users"></i>
                            Clients
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="backoffice-main">
            <!-- Topbar -->
            <header class="backoffice-topbar">
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="ti ti-user me-1"></i>
                        {{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('backoffice.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-logout me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content -->
            <div class="backoffice-content">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="backoffice-footer">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} - Backoffice Admin Portal</p>
            </footer>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')

</body>
</html>
