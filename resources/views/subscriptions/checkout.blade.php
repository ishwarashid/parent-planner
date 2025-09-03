<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Parent Planner') }} - Checkout</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Paddle JS -->
    @paddleJS
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-indigo-900 shadow-md fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-white">Parent Planner</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/dashboard') }}"
                            class="font-medium text-white hover:text-indigo-200 transition">Dashboard</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
                    <h1 class="text-3xl font-bold text-center text-gray-900 mb-8">Complete Your Subscription</h1>
                    
                    <div class="text-center mb-8">
                        <p class="text-gray-600">You're about to subscribe to Parent Planner. Click the button below to complete your checkout with Paddle.</p>
                    </div>
                    
                    <div class="text-center">
                        <x-paddle-button :checkout="$checkout" class="px-8 py-4 text-lg" id="paddle-button">
                            Continue to Checkout
                        </x-paddle-button>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <a href="{{ route('pricing') }}" class="text-indigo-600 hover:text-indigo-800">
                            &larr; Back to Pricing
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Listen for Paddle checkout events
        window.Paddle.Checkout.loaded(function() {
            // Add event listener for checkout completion
            window.Paddle.Checkout.on('checkout.completed', function(data) {
                // Redirect to dashboard after a short delay
                setTimeout(function() {
                    window.location.href = '{{ route('dashboard') }}';
                }, 2000);
            });
        });
    </script>
</body>

</html>
