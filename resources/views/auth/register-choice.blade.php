<x-guest-layout>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white overflow-hidden sm:rounded-lg">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    How would you like to register?
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Choose the account type that best describes you.
                </p>
            </div>

            <div class="flex flex-col space-y-4">
                <a href="{{ route('register') }}"
                   class="w-full px-4 py-3 font-semibold text-center text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register as a Parent
                </a>

                <a href="{{ route('professional.register') }}"
                   class="w-full px-4 py-3 font-semibold text-center text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Register as a Professional
                </a>
            </div>
            
            <div class="mt-6 text-center">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
