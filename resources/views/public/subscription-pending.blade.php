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
            --bg-hover: #f9fafb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            color: var(--text-primary);
        }

        .pending-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .pending-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 3rem;
            max-width: 550px;
            width: 100%;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .success-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .pending-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-align: center;
        }

        .pending-message {
            color: var(--text-secondary);
            font-size: 1.125rem;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 2rem;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .info-box p {
            margin: 0;
            color: #1e40af;
            font-size: 0.9375rem;
            line-height: 1.6;
        }

        .info-box strong {
            font-weight: 600;
        }

        .help-text {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.6;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
        }

        .back-home-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 600;
            transition: color 0.2s;
            margin-top: 2rem;
        }

        .back-home-link:hover {
            color: #4752c4;
        }
    </style>
</head>
<body>
    <div class="pending-container">
        <div class="pending-card">
            <div class="success-icon">
                <i class="ti ti-mail-check"></i>
            </div>

            <h1 class="pending-title">Check Your Email!</h1>

            <p class="pending-message">
                Thank you for subscribing, <strong>{{ $subscriber->full_name }}</strong>!
            </p>

            <div class="info-box">
                <p>
                    <i class="ti ti-info-circle me-1"></i>
                    We've sent a verification email to <strong>{{ $subscriber->email }}</strong>.
                    Please check your inbox and click the confirmation link to complete your subscription.
                </p>
            </div>

            <div class="help-text">
                <p class="mb-0"><strong>Didn't receive the email?</strong></p>
                <p class="mt-1 mb-0">Please check your spam folder or contact support.</p>
            </div>

            <div class="text-center">
                <a href="{{ route('public.home', $settings->unique_url) }}" class="back-home-link">
                    <i class="ti ti-arrow-left"></i>
                    Back to {{ $settings->product_name }}
                </a>
            </div>
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
