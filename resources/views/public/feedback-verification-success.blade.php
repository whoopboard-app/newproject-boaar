<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea Confirmed - {{ $settings->product_name ?? 'Feedback Board' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --primary-color: #5865F2;
            --success-color: #10b981;
            --border-color: #e5e7eb;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            color: var(--text-primary);
        }

        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .success-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 3rem;
            max-width: 550px;
            width: 100%;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success-color);
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

        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .success-description {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .idea-summary {
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .idea-summary-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .idea-summary-text {
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .btn-home {
            background: var(--primary-color);
            color: white;
            padding: 0.875rem 2rem;
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

        .btn-home:hover {
            background: #4752c4;
            color: white;
            transform: translateY(-1px);
        }

        .already-verified {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #92400e;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="ti ti-check"></i>
            </div>

            <h1 class="success-title">
                @if($alreadyVerified)
                    Already Confirmed!
                @else
                    Idea Successfully Confirmed!
                @endif
            </h1>

            @if($alreadyVerified)
                <div class="already-verified">
                    <i class="ti ti-info-circle me-1"></i>
                    This idea has already been confirmed. No further action is needed.
                </div>
            @endif

            <p class="success-description">
                @if($alreadyVerified)
                    Your idea was already confirmed and is visible on our feedback board.
                @else
                    Thank you for confirming your submission! Your idea is now visible on our feedback board and ready to receive votes from the community.
                @endif
            </p>

            <div class="idea-summary">
                <div class="idea-summary-label">Your Idea</div>
                <div class="idea-summary-text">{{ $feedback->idea }}</div>
            </div>

            @if($settings)
                <a href="{{ route('public.feedback') }}" class="btn btn-home">
                    <i class="ti ti-arrow-left"></i>
                    Go to Feedback Board
                </a>
            @else
                <a href="/" class="btn btn-home">
                    <i class="ti ti-home"></i>
                    Go Home
                </a>
            @endif
        </div>
    </div>

    @include('partials.public-footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
