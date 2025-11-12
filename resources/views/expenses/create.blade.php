<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-label: #1D2S1;
        /* Muted Navy */
        --theme-input-border: #AFEEEE;
        /* Pale Turquoise */
        --theme-input-focus-border: #40E0D0;
        /* Turquoise */
        --theme-required-star: #FF6F61;
        /* Coral */
        --theme-button-primary-bg: #FF5722;
        /* Deep Orange */
        --theme-button-primary-text: #FFFFFF;
        --theme-button-primary-bg-hover: #FF6F61;
        /* Light Coral */
        --theme-button-secondary-bg: #2F4F4F;
        /* Dark Slate Gray */
        --theme-button-secondary-text: #FFFFFF;
        --theme-button-secondary-bg-hover: #008080;
        /* Teal */
        --theme-error-text: #FF6F61;
        /* Coral */
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    .theme-info-text {
        color: var(--theme-label);
    }

    .theme-required-star {
        color: var(--theme-required-star);
    }

    .theme-input-label {
        color: var(--theme-label);
    }

    .theme-input,
    .theme-select {
        border-color: var(--theme-input-border) !important;
    }

    .theme-input:focus,
    .theme-select:focus {
        border-color: var(--theme-input-focus-border) !important;
        box-shadow: 0 0 0 1px var(--theme-input-focus-border) !important;
    }

    .theme-error {
        color: var(--theme-error-text);
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
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Add New Expense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Private File Notification -->
                        @if(config('filesystems.disks.do.visibility') === 'private')
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Private File Storage</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Uploaded receipt files are stored privately for security. Links to these files will expire after 5 minutes for security reasons. If you need to view the receipt again, please refresh the page.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Child -->
                        <div>
                            <x-input-label for="child_id" class="theme-input-label">
                                {{ __('Child') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="child_id" name="child_id"
                                class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                <option value="">Select a Child</option>
                                @foreach ($children as $child)
                                    <option value="{{ $child->id }}"
                                        {{ old('child_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('child_id')" class="mt-2 theme-error" />
                        </div>

                        <!-- Payer -->
                        {{-- <div class="mt-4">
                            <x-input-label for="payer_id" class="theme-input-label">
                                {{ __('Paid By') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="payer_id" name="payer_id"
                                class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                <option value="">Select a Payer</option>
                                @foreach ($responsibleUsers as $payer)
                                <option value="{{ $payer->id }}"
                                    {{ old('payer_id', auth()->id()) == $payer->id ? 'selected' : '' }}>
                                    {{ $payer->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('payer_id')" class="mt-2 theme-error" />
                        </div> --}}

                        <!-- Payer Information Display -->
                        <div class="mt-4">
                            <x-input-label class="theme-input-label" :value="__('Created by')" />
                            <div class="w-full mt-1 p-3 bg-gray-50 border rounded-md">
                                <p class="text-sm font-semibold theme-info-text">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">As the creator of this expense, you are
                                    automatically assigned as the payer.</p>
                            </div>
                        </div>

                        <!-- Expense Split Section -->
                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-lg font-medium theme-header-text">Expense Responsibility Split</h3>
                            <p class="mt-1 text-sm theme-info-text">Define the percentage of the expense each person is
                                responsible for. Must total 100%.</p>

                            <div id="splits-container" class="mt-4 space-y-3">
                                @foreach ($responsibleUsers as $user)
                                    <div class="flex items-center space-x-4">
                                        <label for="split_{{ $user->id }}"
                                            class="w-1/3 theme-input-label">{{ $user->name }}</label>
                                        <div class="flex-grow flex items-center">
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="splits[{{ $loop->index }}][percentage]"
                                                class="split-percentage-input block w-full theme-input"
                                                placeholder="e.g., 50"
                                                value="{{ old('splits.' . $loop->index . '.percentage', $loop->count == 2 ? 50 : ($loop->count == 1 ? 100 : '')) }}">
                                            <input type="hidden" name="splits[{{ $loop->index }}][user_id]"
                                                value="{{ $user->id }}">
                                            <span class="ml-2 text-gray-500">%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2 text-right">
                                <span class="font-bold theme-info-text">Total: <span id="split-total">0</span>%</span>
                                <p id="split-error" class="text-sm theme-error mt-1" style="display: none;">Total must
                                    be 100%.</p>
                            </div>
                            <x-input-error :messages="$errors->get('splits')" class="mt-2 theme-error" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" class="theme-input-label">
                                {{ __('Description') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <x-text-input id="description" class="block mt-1 w-full theme-input" type="text"
                                name="description" :value="old('description')" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2 theme-error" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" class="theme-input-label">
                                {{ __('Amount') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <x-text-input id="amount" class="block mt-1 w-full theme-input" type="number"
                                step="0.01" name="amount" :value="old('amount')" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2 theme-error" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category" class="theme-input-label">
                                {{ __('Category') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="category" name="category"
                                class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                <option value="">Select a Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}"
                                        {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2 theme-error" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" class="theme-input-label">
                                {{ __('Status') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="status" name="status"
                                class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status', 'pending') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2 theme-error" />
                        </div>

                        <!-- Receipt URL -->
                        <div class="mt-4">
                            <x-input-label for="receipt_url" class="theme-input-label" :value="__('Receipt (Image or PDF)')" />
                            <input id="receipt_url"
                                class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none theme-input"
                                type="file" name="receipt_url" />
                            <x-input-error :messages="$errors->get('receipt_url')" class="mt-2 theme-error" />
                        </div>

                        <!-- Is Recurring -->
                        <div class="mt-4">
                            <div class="flex items-center">
                                <input id="is_recurring" name="is_recurring" type="checkbox" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 theme-input"
                                    {{ old('is_recurring') ? 'checked' : '' }}>
                                <x-input-label for="is_recurring" :value="__('Is Recurring?')" class="ml-2 block text-sm theme-input-label" />
                            </div>
                        </div>

                        <!-- Recurrence Pattern -->
                        <div id="recurrence-fields" class="mt-4" style="{{ old('is_recurring') ? '' : 'display: none;' }}">
                            <x-input-label for="recurrence_pattern" class="theme-input-label">
                                {{ __('Recurrence Pattern') }}
                            </x-input-label>
                            <select id="recurrence_pattern" name="recurrence_pattern"
                                class="block mt-1 w-full rounded-md shadow-sm theme-select">
                                <option value="">Select Pattern</option>
                                <option value="daily" {{ old('recurrence_pattern') == 'daily' ? 'selected' : '' }}>
                                    Daily
                                </option>
                                <option value="weekly" {{ old('recurrence_pattern') == 'weekly' ? 'selected' : '' }}>
                                    Weekly
                                </option>
                                <option value="monthly" {{ old('recurrence_pattern') == 'monthly' ? 'selected' : '' }}>
                                    Monthly
                                </option>
                                <option value="yearly" {{ old('recurrence_pattern') == 'yearly' ? 'selected' : '' }}>
                                    Yearly
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('recurrence_pattern')" class="mt-2 theme-error" />
                        </div>

                        <!-- Recurrence End Date -->
                        <div id="recurrence-end-date-field" class="mt-4" style="{{ old('is_recurring') ? '' : 'display: none;' }}">
                            <x-input-label for="recurrence_end_date" class="theme-input-label">
                                {{ __('Recurrence End Date') }}
                            </x-input-label>
                            <x-text-input id="recurrence_end_date" class="block mt-1 w-full theme-input" type="date"
                                name="recurrence_end_date" :value="old('recurrence_end_date')" />
                            <x-input-error :messages="$errors->get('recurrence_end_date')" class="mt-2 theme-error" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('expenses.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ml-4 theme-button theme-button-primary">
                                {{ __('Add Expense') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('splits-container');
            const totalDisplay = document.getElementById('split-total');
            const errorDisplay = document.getElementById('split-error');
            const inputs = container.querySelectorAll('.split-percentage-input');
            const recurringCheckbox = document.getElementById('is_recurring');
            const recurrenceFields = document.getElementById('recurrence-fields');
            const recurrenceEndDateField = document.getElementById('recurrence-end-date-field');

            function calculateTotal() {
                let total = 0;
                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                totalDisplay.textContent = total.toFixed(2);

                if (Math.abs(total - 100) > 0.01 && total > 0) {
                    totalDisplay.classList.add('theme-error');
                    errorDisplay.style.display = 'block';
                } else {
                    totalDisplay.classList.remove('theme-error');
                    errorDisplay.style.display = 'none';
                }
            }

            inputs.forEach(input => {
                input.addEventListener('input', calculateTotal);
            });

            // Toggle recurrence fields visibility based on checkbox
            recurringCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    recurrenceFields.style.display = 'block';
                    recurrenceEndDateField.style.display = 'block';
                } else {
                    recurrenceFields.style.display = 'none';
                    recurrenceEndDateField.style.display = 'none';
                }
            });

            // Initial calculation on page load
            calculateTotal();
        });
    </script>
</x-app-layout>
