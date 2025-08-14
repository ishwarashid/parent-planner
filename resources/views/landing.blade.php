<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Parent Planner') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-600">Parent Planner</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-medium text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">Dashboard</a>
                            @else
                                <a href="{{ route('pricing') }}" class="font-medium text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">Pricing</a>
                                <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-20 md:py-32 flex items-center justify-center min-h-[60vh]">
                <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('https://source.unsplash.com/random/1600x900/?family,kids,parents');"></div>
                <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4">
                        Co-Parenting, <span class="block text-indigo-200">Simplified.</span>
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl mb-8 opacity-90">
                        Manage schedules, expenses, and communication with ease. Focus on what matters most—your children.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-indigo-50 transition duration-150 ease-in-out px-8 py-3 rounded-md text-lg font-semibold shadow-lg">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-indigo-600 transition duration-150 ease-in-out px-8 py-3 rounded-md text-lg font-semibold shadow-lg">
                            Learn More
                        </a>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            A better way to co-parent
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                        <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-md">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-500 text-white mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Shared Calendar</h3>
                            <p class="text-gray-600">Coordinate visitations and appointments with a clear, shared calendar.</p>
                        </div>

                        <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-md">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-500 text-white mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Expense Tracking</h3>
                            <p class="text-gray-600">Log and manage shared child-related expenses with receipt uploads and status tracking.</p>
                        </div>

                        <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-md">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-500 text-white mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Document Storage</h3>
                            <p class="text-gray-600">Securely store and share important documents like birth certificates and custody agreements.</p>
                        </div>

                        <div class="flex flex-col items-center text-center p-6 bg-gray-50 rounded-lg shadow-md">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-500 text-white mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 9H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Reporting</h3>
                            <p class="text-gray-600">Generate PDF and CSV reports for visitations and expenses for your records.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action Section -->
            <section class="bg-indigo-700 text-white py-16">
                <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-4">
                        Ready to simplify your co-parenting journey?
                    </h2>
                    <p class="text-lg sm:text-xl mb-8 opacity-90">
                        Join Parent Planner today and experience a smoother, more organized way to co-parent.
                    </p>
                    <a href="{{ route('register') }}" class="bg-white text-indigo-700 hover:bg-indigo-100 transition duration-150 ease-in-out px-8 py-3 rounded-md text-lg font-semibold shadow-lg">
                        Sign Up Now
                    </a>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400">
                &copy; {{ date('Y') }} Parent Planner. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>