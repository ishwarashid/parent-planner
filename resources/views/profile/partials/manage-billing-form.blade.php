<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Billing Management') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Manage your billing information and view your subscription details.') }}
        </p>
    </header>

    <div class="mt-6">
        <a href="{{ route('professional.billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Manage Billing') }}
        </a>
    </div>
</section>
