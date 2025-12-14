<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Backoffice Access Code</title>
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
            background-color: #1e293b;
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
            border: 2px dashed #1e293b;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #1e293b;
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
        <h1>Backoffice Access</h1>
        <p style="margin: 0; opacity: 0.9;">{{ config('app.name') }}</p>
    </div>

    <div class="content">
        <h2>Your Verification Code</h2>

        <p>Hello,</p>

        <p>You requested access to the <strong>Backoffice Admin Portal</strong>. Use the verification code below to continue with your login:</p>

        <div class="code-box">
            <div class="code">{{ $accessCode->code }}</div>
        </div>

        <p>Enter this code on the login page along with your password to access the backoffice.</p>

        <div class="warning">
            <strong>Important:</strong> This code will expire in 30 minutes. If you didn't request this code, please ignore this email and ensure your account is secure.
        </div>
    </div>

    <div class="footer">
        <p>Best regards,<br>{{ config('app.name') }} Team</p>

        <p style="margin-top: 15px; color: #999;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
