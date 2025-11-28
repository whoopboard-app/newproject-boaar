<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .code-box {
            background-color: #fff;
            border: 2px dashed #4F46E5;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #4F46E5;
            font-family: monospace;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background-color: #FEF3C7;
            border: 1px solid #F59E0B;
            padding: 12px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 13px;
            color: #92400E;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($settings->logo)
            <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->product_name }}" style="max-height: 50px; margin-bottom: 10px;">
        @endif
        <h1>{{ $settings->product_name ?? config('app.name') }}</h1>
    </div>

    <div class="content">
        <h2>Your Verification Code</h2>

        <p>Hello,</p>

        <p>You requested access to <strong>{{ $settings->product_name ?? config('app.name') }}</strong>. Use the verification code below to complete your login:</p>

        <div class="code-box">
            <div class="code">{{ $invite->verification_code }}</div>
        </div>

        <p>Enter this code on the verification page to access the site.</p>

        <div class="warning">
            <strong>Important:</strong> This code will expire in 15 minutes. If you didn't request this code, you can safely ignore this email.
        </div>
    </div>

    <div class="footer">
        <p>Best regards,<br>{{ $settings->product_name ?? config('app.name') }} Team</p>

        <p style="margin-top: 15px; color: #999;">
            &copy; {{ date('Y') }} {{ $settings->product_name ?? config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
