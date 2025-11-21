<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template->email_subject ?? "We'd love to hear your feedback!" }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 32px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }
        .email-logo {
            margin-bottom: 20px;
        }
        .email-logo img {
            max-width: 150px;
            max-height: 60px;
            object-fit: contain;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-body {
            padding: 32px;
            color: #333333;
            line-height: 1.6;
        }
        .email-content {
            margin-bottom: 24px;
        }
        .promotional-offer {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 16px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .promotional-offer strong {
            color: #856404;
            font-size: 16px;
        }
        .promotional-offer-text {
            margin-top: 8px;
            color: #856404;
        }
        .cta-button {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button a {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 14px 36px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-weight: 600;
            font-size: 16px;
        }
        .email-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #999999;
            font-size: 14px;
        }
        .test-banner {
            background: #ff6b6b;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            @if($template->email_logo && !$isTest && $template->exists)
            <div class="email-logo">
                <img src="{{ asset('storage/' . $template->email_logo) }}" alt="Logo">
            </div>
            @elseif($isTest && isset($template->email_logo_data))
            <div class="email-logo">
                <img src="{{ $template->email_logo_data }}" alt="Logo">
            </div>
            @endif
            <h1>Your Feedback Matters!</h1>
        </div>

        <div class="email-body">
            <div class="email-content">
                @if($template->email_content)
                    {!! $template->email_content !!}
                @else
                    <p>Hi there!</p>
                    <p>We hope you're enjoying our product. We'd love to hear about your experience!</p>
                @endif
            </div>

            @if($template->promotional_offer)
            <div class="promotional-offer">
                <strong>🎁 Special Offer</strong>
                <div class="promotional-offer-text">
                    {{ $template->promotional_offer }}
                </div>
            </div>
            @endif

            <div class="cta-button">
                <a href="{{ route('testimonials.public.form', $template->unique_url) }}">
                    Share Your Feedback
                </a>
            </div>

            <div class="email-footer">
                <p>Thank you for your time!</p>
            </div>
        </div>
    </div>
</body>
</html>
