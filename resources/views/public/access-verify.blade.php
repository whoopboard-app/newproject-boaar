<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Code - {{ $settings->product_name ?? config('app.name') }}</title>

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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

        .email-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
        }

        .code-input {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5rem;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            text-transform: uppercase;
        }

        .code-input:focus {
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

        .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            text-decoration: underline;
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

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            padding: 0 1rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
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
            <p class="access-subtitle">Enter Verification Code</p>
        </div>

        <div class="access-body">
            <div class="access-icon">
                <i class="ti ti-mail-check"></i>
            </div>

            <div class="text-center mb-4">
                <div class="email-badge mx-auto" style="display: inline-flex;">
                    <i class="ti ti-mail"></i>
                    {{ $email }}
                </div>
                <p class="text-muted mb-0">
                    We've sent a 6-character verification code to your email. Enter it below to access the site.
                </p>
            </div>

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

            <form method="POST" action="{{ route('public.access.verify') }}">
                @csrf

                <div class="mb-4">
                    <label for="code" class="form-label text-center d-block">Verification Code</label>
                    <input type="text" class="form-control code-input" id="code" name="code" maxlength="6" placeholder="------" required autofocus autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check me-2"></i>Verify & Access Site
                </button>
            </form>

            <div class="divider">
                <span>or</span>
            </div>

            <div class="text-center">
                <form method="POST" action="{{ route('public.access.resend-code') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link p-0">
                        <i class="ti ti-refresh me-1"></i>Resend code
                    </button>
                </form>
                <span class="text-muted mx-2">|</span>
                <a href="{{ route('public.access.login') }}" class="btn btn-link p-0">
                    <i class="ti ti-arrow-left me-1"></i>Use different email
                </a>
            </div>
        </div>

        <div class="access-footer">
            <p>Code expires in 15 minutes</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-uppercase and focus management
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    </script>
</body>
</html>
