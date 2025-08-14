<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    How would you like to register?
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Choose the account type that best describes you.
                </p>
            </div>

            <div class="flex flex-col space-y-4">
                <a href="{{ route('register') }}"
                   class="w-full px-4 py-3 font-semibold text-center text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-900">
                    Register as a Parent
                </a>

                <a href="{{ route('professional.register') }}"
                   class="w-full px-4 py-3 font-semibold text-center text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-900">
                    Register as a Professional
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
