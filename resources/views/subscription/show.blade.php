<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subscription Portal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Flash Messages -->
                    @if(session('status'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('status') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <a href="{{ route('subscription.show') }}" 
                               class="{{ request()->routeIs('subscription.show') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                                Subscription
                            </a>
                            <a href="{{ route('subscription.invoices.index') }}" 
                               class="{{ request()->routeIs('subscription.invoices.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                                Billing History
                            </a>
                        </nav>
                    </div>

                    <!-- Main Content -->
                    <div class="max-w-4xl mx-auto">
                        @if($subscription)
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Subscription Details</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about your current subscription.</p>
                                </div>
                                <div class="border-t border-gray-200">
                                    <dl>
                                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">Plan</dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $planName ?? 'No Subscription' }}
                                            </dd>
                                        </div>
                                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($subscription->status === 'active') bg-green-100 text-green-800
                                                    @elseif($subscription->status === 'canceled') bg-red-100 text-red-800
                                                    @elseif($subscription->status === 'past_due') bg-yellow-100 text-yellow-800
                                                    @elseif($subscription->status === 'trialing') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $subscription->status)) }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">Renewal Date</dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                @if($subscription->ends_at)
                                                    {{ $subscription->ends_at->format('F j, Y') }}
                                                @else
                                                    {{ $subscription->created_at->addMonth()->format('F j, Y') }}
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Plan Swap -->
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Change Plan</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Switch to a different subscription plan.</p>
                                </div>
                                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                                    <form action="{{ route('subscription.swap') }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                            <div class="sm:col-span-4">
                                                <label for="plan" class="block text-sm font-medium text-gray-700">Select Plan</label>
                                                <div class="mt-1">
                                                    <select id="plan" name="plan" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                        @foreach($plans as $plan)
                                                            <option value="{{ $plan['id'] }}" 
                                                                {{ $subscription && $subscription->items->first()->price_id === $plan['id'] ? 'selected' : '' }}>
                                                                {{ $plan['name'] }} - {{ $plan['price'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Change Plan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Subscription Actions -->
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Subscription Actions</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage your subscription.</p>
                                </div>
                                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        @if($subscription->status === 'active' || $subscription->status === 'trialing')
                                            <form action="{{ route('subscription.cancel') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Cancel Subscription
                                                </button>
                                            </form>
                                        @elseif($subscription->status === 'canceled')
                                            <form action="{{ route('subscription.resume') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Resume Subscription
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Update Payment Method -->
                                        <a href="{{ route('subscription.payment-method') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Update Payment Method
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">No subscription</h3>
                                <p class="mt-1 text-sm text-gray-500">You don't have an active subscription.</p>
                                <div class="mt-6">
                                    <a href="{{ route('pricing') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Subscribe Now
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>