<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe - {{ $settings->product_name ?? 'Feedback Board' }}</title>

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

        .subscribe-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .subscribe-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 3rem;
            max-width: 500px;
            width: 100%;
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

        .subscribe-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .subscribe-icon {
            width: 64px;
            height: 64px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .subscribe-icon i {
            font-size: 2rem;
            color: white;
        }

        .subscribe-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .subscribe-description {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
        }

        .btn-subscribe {
            background: var(--primary-color);
            color: white;
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-subscribe:hover {
            background: #4752c4;
            transform: translateY(-1px);
        }

        .form-note {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
        }

        .form-note i {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="subscribe-container">
        <div class="subscribe-card">
            <a href="{{ route('public.feedback') }}" class="back-link">
                <i class="ti ti-arrow-left"></i>
                Back to {{ $settings->product_name }}
            </a>

            <div class="subscribe-header">
                <div class="subscribe-icon">
                    <i class="ti ti-bell-ringing"></i>
                </div>
                <h1 class="subscribe-title">Subscribe for Updates</h1>
                <p class="subscribe-description">
                    Stay updated with the latest features, improvements, and news from {{ $settings->product_name ?? 'our platform' }}.
                </p>
            </div>

            <form action="{{ route('public.subscribe.submit') }}" method="POST" id="subscribeForm">
                @csrf

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                           id="full_name" name="full_name"
                           value="{{ old('full_name') }}"
                           placeholder="Enter your full name" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter your email address" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-note mb-4">
                    <i class="ti ti-info-circle me-1"></i>
                    <strong>Note:</strong> We'll send a confirmation email to verify your subscription. Please check your inbox and click the confirmation link to complete your subscription.
                </div>

                <button type="submit" class="btn btn-subscribe">
                    <i class="ti ti-check me-2"></i> Subscribe
                </button>
            </form>

            @if(session('error'))
                <div class="alert alert-danger mt-3" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
