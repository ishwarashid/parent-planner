<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <h2 class="text-lg font-bold">Thank You for Registering!</h2>
        <p class="mt-4">Your application has been submitted successfully and is now under review.</p>
        <p>You will receive an email notification once your application has been approved. Please allow 1-2 business days for this process.</p>
        <p class="mt-4">You can now close this page.</p>
    </div>

    <div class="mt-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            Back to Homepage
        </a>
    </div>
</x-guest-layout>
