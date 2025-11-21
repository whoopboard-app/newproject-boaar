<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email Preview</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .message-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 3rem 2rem;
            text-align: center;
        }

        .icon-container {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        .icon-container i {
            font-size: 3.5rem;
            color: white;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
            }
        }

        h1 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .message-text {
            color: #666;
            font-size: 1.125rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: left;
            margin-bottom: 2rem;
        }

        .info-box h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .info-box ul {
            margin-bottom: 0;
            padding-left: 1.5rem;
        }

        .info-box li {
            color: #555;
            margin-bottom: 0.5rem;
        }

        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="message-card">
        <div class="icon-container">
            <i class="ti ti-mail-check"></i>
        </div>

        <h1>Test Email Preview</h1>
        <p class="message-text">
            This is a test email preview. The testimonial form will be available after you publish your template.
        </p>

        <div class="info-box">
            <h5><i class="ti ti-info-circle me-2"></i>How It Works</h5>
            <ul>
                <li><strong>Test Email:</strong> You've received a test email to preview how it looks</li>
                <li><strong>Publish Template:</strong> Click "Publish Template" in your admin panel to activate it</li>
                <li><strong>Live Form:</strong> Once published, this link will direct recipients to the testimonial submission form</li>
                <li><strong>Collect Feedback:</strong> Recipients can then share their testimonials through the 4-step wizard</li>
            </ul>
        </div>

        <div class="alert alert-success">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Good news!</strong> Your test email looks great. Publish your template to make it live!
        </div>

        <p class="text-muted mb-3">
            <small>Close this window and return to your admin panel to publish the template.</small>
        </p>
    </div>
</body>
</html>
