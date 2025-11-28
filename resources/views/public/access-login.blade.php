<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Access Required - {{ $settings->product_name ?? config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: #5865F2;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .access-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }

        .access-header {
            background: #f9fafb;
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .access-logo {
            max-height: 60px;
            max-width: 200px;
            margin-bottom: 1rem;
        }

        .access-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .access-subtitle {
            color: var(--text-secondary);
            margin: 0.5rem 0 0;
            font-size: 0.9375rem;
        }

        .access-body {
            padding: 2rem;
        }

        .access-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .access-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background: #4752c4;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .access-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: #f9fafb;
            border-top: 1px solid var(--border-color);
        }

        .access-footer p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .private-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fef3c7;
            color: #92400e;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="access-container">
        <div class="access-header">
            @if($settings->logo)
                <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="access-logo">
            @endif
            <h1 class="access-title">{{ $settings->product_name ?? config('app.name') }}</h1>
            <p class="access-subtitle">Private Site Access</p>
        </div>

        <div class="access-body">
            <div class="access-icon">
                <i class="ti ti-lock"></i>
            </div>

            <div class="private-badge mx-auto" style="display: inline-flex; width: fit-content;">
                <i class="ti ti-shield-lock"></i>
                This site is private
            </div>

            <p class="text-center text-muted mb-4">
                Enter your email address to request access. If you're on the access list, you'll receive a verification code.
            </p>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="ti ti-check me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach($errors->all() as $error)
                        <div><i class="ti ti-alert-circle me-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('public.access.request-code') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-mail me-2"></i>Request Access Code
                </button>
            </form>
        </div>

        <div class="access-footer">
            <p>Don't have access? Contact the site administrator.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
