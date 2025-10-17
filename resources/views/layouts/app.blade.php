<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-timezone" content="">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            &copy; {{ date('Y') }} Parent Planner. All rights reserved.
                        </div>
                        <div class="flex space-x-6">
                            <a href="{{ route('terms') }}" class="text-gray-600 hover:text-gray-900">Terms</a>
                            <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-gray-900">Privacy</a>
                            <a href="{{ route('refund') }}" class="text-gray-600 hover:text-gray-900">Refund Policy</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        @stack('scripts')
        <script>
            // Detect user's timezone and set it in the meta tag
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.querySelector('meta[name="user-timezone"]').setAttribute('content', userTimezone);
            
            // Send timezone to server via AJAX
            fetch('/set-timezone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({timezone: userTimezone})
            });
        </script>
    </body>
</html>
