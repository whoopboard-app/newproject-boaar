<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Subscription</title>
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
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #4338CA;
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
        .footer a {
            color: #4F46E5;
            text-decoration: none;
        }
        .unsubscribe-link {
            color: #666;
            font-size: 11px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
    </div>

    <div class="content">
        <h2>Hello {{ $subscriber->full_name }}!</h2>

        <p>Thank you for subscribing to our newsletter.</p>

        <p>Please click the button below to confirm your subscription and start receiving updates from us.</p>

        <div style="text-align: center;">
            <a href="{{ $verifyUrl }}" class="button">Confirm Subscription</a>
        </div>

        <p>Or copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #4F46E5;">{{ $verifyUrl }}</p>

        <p>If you did not subscribe to our newsletter, you can safely ignore this email.</p>
    </div>

    <div class="footer">
        <p>Best regards,<br>{{ config('app.name') }} Team</p>

        <p class="unsubscribe-link">
            Don't want to receive emails?
            <a href="{{ $unsubscribeUrl }}">Unsubscribe here</a>
        </p>

        <p style="margin-top: 15px; color: #999;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
