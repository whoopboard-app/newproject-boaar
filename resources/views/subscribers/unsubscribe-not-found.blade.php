<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriber Not Found - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Subscriber Not Found
                </h2>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-red-800">
                        The unsubscribe link is invalid or has expired. Please contact support if you need assistance.
                    </p>
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
