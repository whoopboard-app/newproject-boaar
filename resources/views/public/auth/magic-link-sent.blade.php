<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Your Email - {{ $settings->product_name ?? 'Feedback Board' }}</title>

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
            background-color: #f9fafb;
            color: var(--text-primary);
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 3rem;
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 2rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .email-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #5865F2 0%, #7289da 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .email-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .success-description {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .email-highlight {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .email-highlight strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            color: #1e40af;
            text-align: left;
        }

        .info-box i {
            margin-right: 0.5rem;
        }

        .btn-back {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #4752c4;
            color: white;
            transform: translateY(-1px);
        }

        .resend-link {
            display: block;
            margin-top: 1rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .resend-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .resend-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <a href="{{ route('public.home', $settings->unique_url) }}" class="back-link">
                <i class="ti ti-arrow-left"></i>
                Back to {{ $settings->product_name ?? 'Feedback Board' }}
            </a>

            <div class="email-icon">
                <i class="ti ti-mail-check"></i>
            </div>

            <h1 class="success-title">Check Your Email</h1>
            <p class="success-description">
                We've sent a magic link to your email address. Click the link in the email to log in.
            </p>

            <div class="email-highlight">
                <i class="ti ti-at me-1"></i>
                <strong>{{ $publicUser->email }}</strong>
            </div>

            <div class="info-box">
                <i class="ti ti-info-circle"></i>
                <strong>Didn't receive the email?</strong>
                <ul class="mb-0 mt-2 ps-4">
                    <li>Check your spam or junk folder</li>
                    <li>Make sure you entered the correct email</li>
                    <li>The link expires in 15 minutes</li>
                </ul>
            </div>

            <a href="{{ route('public.home', $settings->unique_url) }}" class="btn-back">
                <i class="ti ti-arrow-left"></i>
                Return to Feedback Board
            </a>

            <p class="resend-link">
                Need a new link? <a href="{{ route('public.auth.login', $settings->unique_url) }}">Request another one</a>
            </p>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
