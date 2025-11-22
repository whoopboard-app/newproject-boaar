<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'WhoopBoard') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    @stack('styles')

    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .clean-container {
            min-height: 100vh;
            padding-top: 90px;
            padding-bottom: 100px;
        }
        .page-header {
            background: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .page-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-title h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>@yield('page-title', 'Create Template')</h2>
            </div>
            <div>
                <button type="button" class="btn btn-outline-secondary" onclick="closeWindow()">
                    <i class="ti ti-x me-1"></i> Close
                </button>
            </div>
        </div>
    </div>

    <div class="clean-container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function closeWindow() {
            // Try to close the window (works if opened via window.open)
            window.close();

            // Fallback: if window didn't close, go back in history
            setTimeout(function() {
                if (!window.closed) {
                    window.history.back();
                }
            }, 100);
        }
    </script>

    @stack('scripts')
</body>
</html>
