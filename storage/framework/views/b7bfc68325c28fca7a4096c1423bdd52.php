<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Parent Planner')); ?> - Pricing</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?php echo e(url('/')); ?>" class="text-2xl font-bold text-indigo-600">Parent Planner</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php if(Route::has('login')): ?>
                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(url('/dashboard')); ?>" class="font-medium text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">Dashboard</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="font-medium text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">Log in</a>
                                <?php if(Route::has('register')): ?>
                                    <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out">Register</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-16">
            <!-- Pricing Section -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900">
                            Choose Your Plan
                        </h1>
                        <p class="mt-4 text-xl text-gray-600">
                            Simple, transparent pricing. No hidden fees.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                        <!-- Basic Plan -->
                        <div class="border rounded-lg shadow-lg p-8 flex flex-col">
                            <h2 class="text-2xl font-bold text-center">Basic</h2>
                            <div class="text-5xl font-extrabold text-center my-4">
                                $10 <span class="text-xl font-medium text-gray-500">/ month</span>
                            </div>
                            <ul class="space-y-4 text-gray-600 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Shared Calendar
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Expense Tracking
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Document Storage
                                </li>
                            </ul>
                            <div class="mt-auto">
                                <a href="<?php echo e(route('checkout', ['plan' => 'price_1RlnbZPOErLRYIriPtbcMUQx'])); ?>" class="w-full text-center bg-indigo-600 text-white hover:bg-indigo-700 transition duration-150 ease-in-out px-8 py-3 rounded-md text-lg font-semibold shadow-lg">
                                    Subscribe
                                </a>
                            </div>
                        </div>

                        <!-- Premium Plan -->
                        <div class="border rounded-lg shadow-lg p-8 flex flex-col">
                            <h2 class="text-2xl font-bold text-center">Premium</h2>
                            <div class="text-5xl font-extrabold text-center my-4">
                                $20 <span class="text-xl font-medium text-gray-500">/ month</span>
                            </div>
                            <ul class="space-y-4 text-gray-600 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    All Basic Features
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Advanced Reporting
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Priority Support
                                </li>
                            </ul>
                            <div class="mt-auto">
                                <a href="<?php echo e(route('checkout', ['plan' => 'price_premium'])); ?>" class="w-full text-center bg-indigo-600 text-white hover:bg-indigo-700 transition duration-150 ease-in-out px-8 py-3 rounded-md text-lg font-semibold shadow-lg">
                                    Subscribe
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400">
                &copy; <?php echo e(date('Y')); ?> Parent Planner. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH /home/azan/Desktop/parent-planner-master/resources/views/subscriptions/pricing.blade.php ENDPATH**/ ?>