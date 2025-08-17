<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Parent Planner') }} - Pricing</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Color Palette & Styles -->
    <style>
        :root {
            --navy-blue: #1D2951;
            --dark-navy: #000033;
            --medium-turquoise: #40E0D0;
            --light-turquoise: #AFE4DE;
            --light-bg: #f8fafc;
        }

        .bg-theme-navy {
            background-color: #000080;
        }

        .text-theme-navy {
            color: var(--navy-blue);
        }

        .text-theme-dark-navy {
            color: var(--dark-navy);
        }

        .bg-theme-turquoise {
            background-color: var(--medium-turquoise);
        }

        .text-theme-turquoise {
            color: var(--medium-turquoise);
        }

        .hover\:bg-theme-light-turquoise:hover {
            background-color: var(--light-turquoise);
        }

        .hover\:text-theme-light-turquoise:hover {
            color: var(--light-turquoise);
        }

        .border-theme-turquoise {
            border-color: var(--medium-turquoise);
        }

        .bg-theme-light {
            background-color: var(--light-bg);
        }

        /* Toggle Switch Styles */
        .toggle-active {
            background-color: var(--medium-turquoise);
            color: var(--dark-navy);
        }

        .toggle-inactive {
            background-color: #e5e7eb;
            /* gray-200 */
            color: #4b5563;
            /* gray-600 */
        }
    </style>
</head>

<body class="font-sans antialiased bg-theme-light">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-theme-navy shadow-md fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-white">Parent Planner</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="font-medium text-white hover:text-theme-light-turquoise transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="font-medium text-white hover:text-theme-light-turquoise transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-theme-dark-navy bg-theme-turquoise hover:bg-theme-light-turquoise transition font-semibold">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-16">
            <!-- Pricing Section -->
            <section class="py-16 bg-white" x-data="{ billingCycle: 'monthly' }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-theme-dark-navy">Choose Your Plan</h1>
                        <p class="mt-4 text-xl text-theme-navy">Simple, transparent pricing. No hidden fees.</p>
                    </div>

                    <!-- Monthly/Yearly Toggle -->
                    <div class="flex justify-center items-center space-x-4 mb-12">
                        <span class="font-medium text-theme-navy">Monthly</span>
                        <div
                            class="relative inline-block w-14 align-middle select-none transition duration-200 ease-in">
                            <button @click="billingCycle = (billingCycle === 'monthly' ? 'yearly' : 'monthly')"
                                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none bg-gray-200"
                                :class="{ 'bg-theme-turquoise': billingCycle === 'yearly' }">
                                <span aria-hidden="true"
                                    class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                                    :class="{ 'translate-x-5': billingCycle === 'yearly', 'translate-x-0': billingCycle === 'monthly' }"></span>
                            </button>
                        </div>
                        <span class="font-medium text-theme-navy">Yearly</span>
                        <span class="text-lg font-semibold text-green-900 bg-green-100 px-3 py-1 rounded-full">Save
                            $12</span>
                    </div>

                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                        <div class="border border-gray-200 rounded-lg shadow-lg p-8 flex flex-col">
                            <h2 class="text-2xl font-bold text-center text-theme-dark-navy">Basic</h2>
                            <div class="text-center my-4">
                                <div x-show="billingCycle === 'monthly'">
                                    <span class="text-5xl font-extrabold text-theme-navy">$3</span>
                                    <span class="text-xl font-medium text-gray-500">/ month</span>
                                </div>
                                <div x-show="billingCycle === 'yearly'" style="display: none;">
                                    <span class="text-5xl font-extrabold text-theme-navy">$24</span>
                                    <span class="text-xl font-medium text-gray-500">/ year</span>
                                </div>
                            </div>
                            <ul class="space-y-4 text-theme-navy mb-8">
                                <li class="flex items-center"><svg class="w-6 h-6 text-theme-turquoise mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>Shared Calendar</li>
                                <li class="flex items-center"><svg class="w-6 h-6 text-theme-turquoise mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>Expense Tracking</li>
                                <li class="flex items-center"><svg class="w-6 h-6 text-theme-turquoise mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>Document Storage</li>
                            </ul>
                            <div class="mt-auto">
                                <a href="{{ route('checkout', ['plan' => 'price_1RvC8APOErLRYIriK6Wohwtj']) }}"
                                    x-show="billingCycle === 'monthly'"
                                    class="w-full text-center bg-theme-turquoise text-theme-dark-navy hover:bg-theme-light-turquoise transition px-8 py-3 rounded-md text-lg font-bold shadow-lg block">Subscribe
                                    Monthly</a>
                                <a href="{{ route('checkout', ['plan' => 'price_1RvC9KPOErLRYIriXLtJHjBk']) }}"
                                    x-show="billingCycle === 'yearly'" style="display: none;"
                                    class="w-full text-center bg-theme-turquoise text-theme-dark-navy hover:bg-theme-light-turquoise transition px-8 py-3 rounded-md text-lg font-bold shadow-lg block">Subscribe
                                    Yearly</a>
                            </div>
                        </div>

                        <!-- Premium Plan -->
                        <div class="border-2 border-theme-turquoise rounded-lg shadow-xl p-8 flex flex-col relative">
                            <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2"><span
                                    class="px-3 py-1 text-sm text-theme-dark-navy bg-theme-turquoise rounded-full font-semibold">Most
                                    Popular</span></div>
                            <h2 class="text-2xl font-bold text-center text-theme-dark-navy">Premium</h2>
                            <div class="text-center my-4">
                                <div x-show="billingCycle === 'monthly'">
                                    <span class="text-5xl font-extrabold text-theme-navy">$5</span>
                                    <span class="text-xl font-medium text-gray-500">/ month</span>
                                </div>
                                <div x-show="billingCycle === 'yearly'" style="display: none;">
                                    <span class="text-5xl font-extrabold text-theme-navy">$48</span>
                                    <span class="text-xl font-medium text-gray-500">/ year</span>
                                </div>
                            </div>
                            <ul class="space-y-4 text-theme-navy mb-8">
                                <li class="flex items-center"><svg class="w-6 h-6 text-theme-turquoise mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>All Basic Features</li>
                                <li class="flex items-center"><svg class="w-6 h-6 text-theme-turquoise mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>Invite Co-Parent & Others</li>
                                <li class="flex items-center"><svg class="w-6 h-6 text-theme-turquoise mr-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>Advanced Reporting</li>
                            </ul>
                            <div class="mt-auto">
                                <a href="{{ route('checkout', ['plan' => 'price_1RvC9oPOErLRYIrifvr2Ml7Q']) }}"
                                    x-show="billingCycle === 'monthly'"
                                    class="w-full text-center bg-theme-turquoise text-theme-dark-navy hover:bg-theme-light-turquoise transition px-8 py-3 rounded-md text-lg font-bold shadow-lg block">Subscribe
                                    Monthly</a>
                                <a href="{{ route('checkout', ['plan' => 'price_1RvCAaPOErLRYIri4yn3Ay4l']) }}"
                                    x-show="billingCycle === 'yearly'" style="display: none;"
                                    class="w-full text-center bg-theme-turquoise text-theme-dark-navy hover:bg-theme-light-turquoise transition px-8 py-3 rounded-md text-lg font-bold shadow-lg block">Subscribe
                                    Yearly</a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-theme-navy py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-300">
                &copy; {{ date('Y') }} Parent Planner. All rights reserved.
            </div>
        </footer>
    </div>
</body>

</html>
