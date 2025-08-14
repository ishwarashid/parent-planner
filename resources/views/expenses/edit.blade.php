<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-label: #1D2951;
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
            {{ __('Edit Expense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Child -->
                        <div>
                            <x-input-label for="child_id" class="theme-input-label">
                                {{ __('Child') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="child_id" name="child_id" class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                <option value="">Select a Child</option>
                                @foreach ($children as $child)
                                    <option value="{{ $child->id }}" {{ old('child_id', $expense->child_id) == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('child_id')" class="mt-2 theme-error" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" class="theme-input-label">
                                {{ __('Description') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <x-text-input id="description" class="block mt-1 w-full theme-input" type="text" name="description" :value="old('description', $expense->description)" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2 theme-error" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" class="theme-input-label">
                                {{ __('Amount') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <x-text-input id="amount" class="block mt-1 w-full theme-input" type="number" step="0.01" name="amount" :value="old('amount', $expense->amount)" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2 theme-error" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category" class="theme-input-label">
                                {{ __('Category') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="category" name="category" class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                <option value="">Select a Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ old('category', $expense->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2 theme-error" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" class="theme-input-label">
                                {{ __('Status') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm theme-select" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status', $expense->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2 theme-error" />
                        </div>

                        <!-- Receipt URL -->
                        <div class="mt-4">
                            <x-input-label for="receipt_url" class="theme-input-label" :value="__('Receipt (Image or PDF)')" />
                            @if ($expense->receipt_url)
                                <div class="mt-2 mb-2">
                                    <a href="{{ asset('storage/' . $expense->receipt_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View Current Receipt</a>
                                </div>
                            @endif
                            <input id="receipt_url" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none theme-input" type="file" name="receipt_url" />
                            <x-input-error :messages="$errors->get('receipt_url')" class="mt-2 theme-error" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ml-4 theme-button theme-button-primary">
                                {{ __('Update Expense') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
