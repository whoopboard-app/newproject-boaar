<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Successfully Unsubscribed
                </h2>

                <p class="text-gray-600 mb-6">
                    Goodbye, <strong>{{ $subscriber->full_name }}</strong>
                </p>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-800">
                        You have been successfully unsubscribed from our mailing list.
                        Your email address <strong>{{ $subscriber->email }}</strong> will no longer receive updates from us.
                    </p>
                </div>

                <div class="text-sm text-gray-500 mb-6">
                    <p>We're sorry to see you go!</p>
                    <p class="mt-2">If you change your mind, you can always subscribe again.</p>
                </div>

                <div class="mt-8">
                    <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
