<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Backoffice @yield('title', 'Login')</title>

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
        .backoffice-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }
        .backoffice-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .backoffice-header p {
            margin: 5px 0 0;
            opacity: 0.8;
            font-size: 0.875rem;
        }
        .code-input {
            font-family: monospace;
            font-size: 1.5rem;
            letter-spacing: 0.5rem;
            text-align: center;
            text-transform: uppercase;
        }
    </style>

    @stack('styles')
</head>
<body class="authentication-bg">

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <div class="backoffice-header">
                                <h2>Backoffice</h2>
                                <p>Admin Portal</p>
                            </div>

                            @yield('content')

                        </div>
                    </div>

                    @yield('extra-content')

                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} - Backoffice
        </span>
    </footer>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')

</body>
</html>
