<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance Mode - {{ $settings->product_name ?? config('app.name') }}</title>

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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .maintenance-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            text-align: center;
        }

        .maintenance-header {
            background: #fffbeb;
            padding: 2rem;
            border-bottom: 1px solid #fcd34d;
        }

        .maintenance-logo {
            max-height: 60px;
            max-width: 200px;
            margin-bottom: 1rem;
        }

        .maintenance-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .maintenance-body {
            padding: 2.5rem 2rem;
        }

        .maintenance-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(245, 158, 11, 0);
            }
        }

        .maintenance-icon i {
            font-size: 3rem;
            color: white;
        }

        .maintenance-heading {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .maintenance-message {
            color: var(--text-secondary);
            font-size: 1.0625rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .countdown-section {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .countdown-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        .countdown-time {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .countdown-item {
            text-align: center;
        }

        .countdown-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
        }

        .countdown-unit {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            margin-top: 0.25rem;
        }

        .maintenance-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fef3c7;
            color: #92400e;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .maintenance-footer {
            padding: 1.5rem 2rem;
            background: #f9fafb;
            border-top: 1px solid var(--border-color);
        }

        .maintenance-footer p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .gear-animation {
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .tools-floating {
            position: absolute;
            opacity: 0.1;
            font-size: 4rem;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-header">
            @if($settings->logo)
                <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" class="maintenance-logo">
            @endif
            <h1 class="maintenance-title">{{ $settings->product_name ?? config('app.name') }}</h1>
        </div>

        <div class="maintenance-body">
            <div class="maintenance-icon">
                <i class="ti ti-tool gear-animation"></i>
            </div>

            <h2 class="maintenance-heading">We'll Be Right Back!</h2>

            <p class="maintenance-message">
                @if($message)
                    {{ $message }}
                @else
                    We're currently performing scheduled maintenance to improve your experience. Please check back soon.
                @endif
            </p>

            @if($endsAt)
                <div class="countdown-section">
                    <div class="countdown-label">Estimated time remaining</div>
                    <div class="countdown-time" id="countdown">
                        <div class="countdown-item">
                            <div class="countdown-value" id="hours">--</div>
                            <div class="countdown-unit">Hours</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="minutes">--</div>
                            <div class="countdown-unit">Minutes</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value" id="seconds">--</div>
                            <div class="countdown-unit">Seconds</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="maintenance-badge">
                <i class="ti ti-clock"></i>
                Maintenance in progress
            </div>
        </div>

        <div class="maintenance-footer">
            <p>Thank you for your patience!</p>
        </div>
    </div>

    @if($endsAt)
    <script>
        // Countdown timer
        const endTime = new Date('{{ $endsAt->toIso8601String() }}').getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                // Maintenance ended, reload page
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                setTimeout(() => location.reload(), 2000);
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
    @endif
</body>
</html>
