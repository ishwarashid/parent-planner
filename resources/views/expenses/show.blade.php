<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-title: #1A237E;
        /* Indigo */
        --theme-body-text: #1D2951;
        /* Muted Navy */
        --theme-subtitle: #2F4F4F;
        /* Dark Slate Gray */
        --theme-dt-text: #000033;
        /* Dark Navy */
        --theme-dd-text: #1D2951;
        /* Muted Navy */
        --theme-divider: #AFEEEE;
        /* Pale Turquoise */
        --theme-button-primary-bg: #FFB300;
        /* Amber */
        --theme-button-primary-text: #000033;
        /* Dark Navy */
        --theme-button-primary-bg-hover: #FFDAB9;
        /* Peach Puff */
        --theme-button-secondary-bg: #2F4F4F;
        /* Dark Slate Gray */
        --theme-button-secondary-text: #FFFFFF;
        --theme-button-secondary-bg-hover: #008080;
        /* Teal */
        --theme-button-danger-bg: #FF6F61;
        /* Coral */
        --theme-button-danger-text: #FFFFFF;
        --theme-button-danger-bg-hover: #FF5722;
        /* Deep Orange */
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    .theme-title-text {
        color: var(--theme-title);
    }

    .theme-subtitle-text {
        color: var(--theme-subtitle);
    }

    .theme-dl-divider {
        border-color: var(--theme-divider) !important;
    }

    .theme-dt-text {
        color: var(--theme-dt-text);
    }

    .theme-dd-text {
        color: var(--theme-dd-text);
    }

    .theme-button {
        font-weight: 700;
        transition: background-color 0.2s;
    }

    .theme-button-primary {
        background-color: var(--theme-button-primary-bg);
        color: var(--theme-button-primary-text);
    }

    .theme-button-primary:hover {
        background-color: var(--theme-button-primary-bg-hover);
    }

    .theme-button-secondary {
        background-color: var(--theme-button-secondary-bg);
        color: var(--theme-button-secondary-text);
    }

    .theme-button-secondary:hover {
        background-color: var(--theme-button-secondary-bg-hover);
    }

    .theme-button-danger {
        background-color: var(--theme-button-danger-bg);
        color: var(--theme-button-danger-text);
    }

    .theme-button-danger:hover {
        background-color: var(--theme-button-danger-bg-hover);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Expense Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold theme-title-text">{{ $expense->description }}</h3>
                        <p class="theme-subtitle-text">For: {{ $expense->child->name }}</p>
                    </div>

                    <div class="border-t theme-dl-divider pt-4">
                        <dl class="divide-y theme-dl-divider">
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Total Amount</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    ${{ number_format($expense->amount, 2) }}</dd>
                            </div>

                            <!-- NEW: Responsibility Split Section -->
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Responsibility Split</dt>
                                <dd class="mt-2 text-sm sm:col-span-2 sm:mt-0 theme-dd-text">
                                    <ul role="list"
                                        class="divide-y divide-gray-200 rounded-md border border-gray-200">
                                        @forelse ($expense->splits as $split)
                                            <li
                                                class="flex items-center justify-between py-2 pl-4 pr-5 text-sm leading-6">
                                                <div class="flex w-0 flex-1 items-center">
                                                    {{-- Make sure splits.user was eager loaded in the controller for performance --}}
                                                    <span
                                                        class="truncate font-medium">{{ $split->user->name ?? 'Unknown User' }}</span>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <span class="font-medium">{{ $split->percentage }}%</span>
                                                    <span
                                                        class="text-gray-500 ml-2">(${{ number_format($expense->amount * ($split->percentage / 100), 2) }})</span>
                                                </div>
                                            </li>
                                        @empty
                                            <li
                                                class="flex items-center justify-between py-2 pl-4 pr-5 text-sm leading-6">
                                                <span class="truncate font-medium text-gray-500">No split information
                                                    available.</span>
                                            </li>
                                        @endforelse
                                    </ul>
                                </dd>
                            </div>
                            <!-- END: Responsibility Split Section -->

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Created by</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $expense->payer->name }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Category</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $expense->category }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Status</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800' : ($expense->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($expense->status) }}
                                    </span>
                                </dd>
                            </div>
                            <!-- Private File Notification -->
                            @if (config('filesystems.disks.do.visibility') === 'private' && $expense->receipt_url)
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 theme-dt-text"></dt>
                                    <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-blue-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-blue-800">Private File Storage
                                                    </h3>
                                                    <div class="mt-2 text-sm text-blue-700">
                                                        <p>This receipt is stored privately for security. The image link
                                                            will expire after 5 minutes. If the image doesn't load or
                                                            has expired, please refresh the page to generate a new link.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                            @endif

                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Receipt</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    @if ($expense->receipt_url)
                                        <a href="{{ $expense->receipt_url }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-900">View Receipt</a>
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Recurring</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $expense->is_recurring ? 'Yes' : 'No' }}</dd>
                            </div>
                            
                            @if($expense->is_recurring)
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Recurrence Pattern</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ ucfirst($expense->recurrence_pattern) ?? 'N/A' }}</dd>
                            </div>
                            
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Recurrence End Date</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $expense->recurrence_end_date ? \Carbon\Carbon::parse($expense->recurrence_end_date)->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                            @endif
                            
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Date Added</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ \Carbon\Carbon::parse($expense->created_at)->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- NEW: Payment Confirmation Section --}}
                {{-- ========================================================= --}}

                {{-- This entire section only appears if the expense is waiting for payment. --}}
                @if ($expense->status === 'pending')
                    <div class="mt-6 border-t theme-dl-divider pt-4">

                        {{-- This view is for the NON-PAYER --}}
                        @if (auth()->id() !== $expense->payer_id)
                            @php
                                // Check if the current logged-in user has already confirmed their payment.
                                $hasConfirmed = $expense->confirmations->contains('user_id', auth()->id());
                            @endphp

                            @if ($hasConfirmed)
                                {{-- If they have confirmed, show a success message --}}
                                <div class="p-4 bg-green-100 border border-green-200 rounded-md">
                                    <p class="font-semibold text-green-800">Payment Confirmed!</p>
                                    <p class="text-sm text-green-700 mt-1">
                                        You marked your share as paid on
                                        {{ $expense->confirmations->firstWhere('user_id', auth()->id())->created_at->format('M d, Y') }}.
                                    </p>
                                </div>
                            @else
                                {{-- If they haven't confirmed, show the confirmation button --}}
                                <div class="p-4 bg-yellow-100 border border-yellow-200 rounded-md">
                                    <p class="font-semibold text-yellow-800">Action Required</p>
                                    <p class="text-sm text-yellow-700 mt-1">Have you paid your share to
                                        {{ $expense->payer->name }}? Please confirm below.</p>
                                    <form action="{{ route('payments.confirm', $expense) }}" method="POST"
                                        class="mt-3">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-primary">
                                            I Have Paid My Share
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        {{-- This view is for the PAYER --}}
                        @if (auth()->id() === $expense->payer_id)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <h4 class="font-semibold theme-dt-text">Reimbursement Status</h4>
                                <p class="text-xs text-gray-600 mb-3">This list shows who has confirmed they have paid
                                    their share to you.</p>
                                <ul class="list-disc list-inside space-y-1">
                                    {{-- Loop through everyone who has a share of the expense --}}
                                    @foreach ($expense->splits as $split)
                                        {{-- Don't show the payer their own status --}}
                                        @if ($split->user_id !== $expense->payer_id)
                                            <li>
                                                <span class="font-medium">{{ $split->user->name }}:</span>
                                                @if ($expense->confirmations->contains('user_id', $split->user_id))
                                                    <span class="ml-2 font-semibold text-green-700">Payment
                                                        Confirmed</span>
                                                @else
                                                    <span class="ml-2 text-yellow-700">Awaiting Confirmation</span>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                <p class="mt-3 text-xs text-gray-600">Once everyone has paid, you can <a
                                        href="{{ route('expenses.edit', $expense) }}" class="font-bold underline">edit
                                        this expense</a> and change the status to "Paid".</p>
                            </div>
                        @endif
                    </div>
                @endif


                {{-- Action Buttons (Edit, Delete, etc.) --}}
                <div class="mt-6 flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('expenses.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-secondary">
                        Back to List
                    </a>

                    @can('update', $expense)
                        <a href="{{ route('expenses.edit', $expense) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-primary">
                            Edit
                        </a>
                    @endcan

                    @can('delete', $expense)
                        <button type="button" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-danger"
                            onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this expense?')) { document.getElementById('delete-expense-{{ $expense->id }}').submit(); }">
                            Delete
                        </button>
                        <form id="delete-expense-{{ $expense->id }}" action="{{ route('expenses.destroy', $expense) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
