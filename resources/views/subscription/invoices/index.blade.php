@extends('subscription.layout')

@section('content')
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
                                        {{ $invoice['items'][0]['price']['description'] ?? 'Subscription' }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $invoice['items'][0]['price']['unit_price']['amount'] ?? '0' }} {{ $invoice['items'][0]['price']['unit_price']['currency_code'] ?? 'USD' }}
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
@endsection