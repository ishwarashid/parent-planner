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
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        
        <!-- Floating Contact/Help Button - Only shown when logged in -->
        @auth
        <style>
            .floating-contact-container {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                z-index: 50;
            }
            
            .floating-main-btn {
                width: 4rem;
                height: 4rem;
                border-radius: 50%;
                background-color: rgb(0, 206, 209); /* Turquoise background */
                color: rgb(0, 0, 51); /* Dark navy text/icons */
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                border: none;
                font-size: 1.5rem;
                font-weight: bold;
            }
            
            .floating-main-btn:hover {
                transform: scale(1.1);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                background-color: rgb(0, 180, 183); /* Slightly darker turquoise on hover */
            }
            
            .floating-options-container {
                position: absolute;
                bottom: 5rem;
                right: 0;
                margin-bottom: 0.5rem;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px) scale(0.8);
                transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
                transform-origin: bottom right;
            }
            
            .floating-options-container.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0) scale(1);
            }
            
            .floating-option-btn {
                background-color: rgb(0, 206, 209); /* Turquoise background */
                color: rgb(0, 0, 51); /* Dark navy text/icons */
                padding: 0.75rem 1.25rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                display: flex;
                align-items: center;
                gap: 0.75rem;
                cursor: pointer;
                transition: all 0.2s ease;
                border: 1px solid rgba(0, 0, 51, 0.2); /* Dark navy border */
                min-width: 140px;
                font-weight: 600;
                justify-content: center;
            }
            
            .floating-option-btn:hover {
                background-color: rgb(0, 180, 183); /* Slightly darker turquoise on hover */
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border-color: rgba(0, 0, 51, 0.4);
            }
            
            .floating-option-btn i {
                font-size: 1.25rem;
            }
        </style>
        
        <div class="floating-contact-container">
            <!-- Main floating button -->
            <div class="relative">
                <button id="floating-contact-btn" class="floating-main-btn">
                    <i class="bi bi-question-lg"></i>
                </button>
                
                <!-- Options dropdown -->
                <div id="floating-options" class="floating-options-container">
                    <button id="help-option" class="floating-option-btn">
                        <i class="bi bi-question-circle"></i>
                        <span>Help</span>
                    </button>
                    <button id="contact-option" class="floating-option-btn">
                        <i class="bi bi-envelope"></i>
                        <span>Contact</span>
                    </button>
                </div>
            </div>
        </div>
        
        <script>
            // Toggle floating options when main button is clicked
            document.addEventListener('DOMContentLoaded', function() {
                const mainButton = document.getElementById('floating-contact-btn');
                const optionsContainer = document.getElementById('floating-options');
                
                mainButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    optionsContainer.classList.toggle('show');
                });
                
                // Close options when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mainButton.contains(e.target) && !optionsContainer.contains(e.target)) {
                        optionsContainer.classList.remove('show');
                    }
                });
                
                // Contact Us option: navigate to landing page contact section
                document.getElementById('contact-option').addEventListener('click', function() {
                    window.location.href = "{{ route('home') }}#contact";
                    optionsContainer.classList.remove('show'); // Close options after clicking
                });
                
                // Help option: currently does nothing
                document.getElementById('help-option').addEventListener('click', function() {
                    // For now, do nothing as requested
                    optionsContainer.classList.remove('show'); // Close options after clicking
                });
            });
        </script>
        @endauth
        
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
