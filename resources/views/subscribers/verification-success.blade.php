<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmed - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Subscription Confirmed!
                </h2>

                <p class="text-gray-600 mb-6">
                    Welcome, <strong>{{ $subscriber->full_name }}</strong>!
                </p>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-green-800">
                        Your email address <strong>{{ $subscriber->email }}</strong> has been successfully verified.
                        You'll now receive our updates and newsletters.
                    </p>
                </div>

                <div class="text-sm text-gray-500 mb-6">
                    <p>Thank you for joining our community!</p>
                </div>

                <div class="flex flex-col space-y-3">
                    <a href="/" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Go to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
