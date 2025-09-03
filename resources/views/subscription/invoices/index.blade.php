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
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Billing History</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Your invoice history.</p>
                            </div>
                            <div class="border-t border-gray-200">
                                <ul class="divide-y divide-gray-200">
                                    @forelse($invoices as $invoice)
                                        <li>
                                            <div class="px-4 py-4 flex items-center justify-between sm:px-6">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                                            Invoice #{{ $invoice['id'] }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($invoice['billed_at'])->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-1 flex items-center justify-between">
                                                        <p class="text-sm text-gray-900">
                                                            @if(count($invoice['items']) > 0)
                                                                {{ $invoice['items'][0]['description'] ?? 'Unnamed Item' }}
                                                                @if(count($invoice['items']) > 1)
                                                                    (and {{ count($invoice['items']) - 1 }} more items)
                                                                @endif
                                                            @else
                                                                Invoice
                                                            @endif
                                                        </p>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ Laravel\Paddle\Cashier::formatAmount($invoice['total'], $invoice['currency']) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="ml-5 flex-shrink-0">
                                                    <a href="{{ route('subscription.invoices.download', $invoice['id']) }}" 
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-full text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li>
                                            <div class="px-4 py-8 sm:px-6 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices</h3>
                                                <p class="mt-1 text-sm text-gray-500">You don't have any invoices yet.</p>
                                            </div>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>