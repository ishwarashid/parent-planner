<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-body-text: #1D2951;
        /* Muted Navy */
        --theme-button-bg: #40E0D0;
        /* Turquoise */
        --theme-button-text: #000033;
        --theme-button-bg-hover: #48D1CC;
        /* Medium Turquoise */
        --theme-card-bg: rgba(64, 224, 208, 0.15);
        /* Light Turquoise for card backgrounds */
        --theme-section-title: #000033;
        /* Dark Navy for section titles */
    }

    /* Header Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    /* Section Titles */
    .theme-section-title {
        color: var(--theme-section-title);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    /* Form Card Styling */
    .theme-form-card {
        background-color: var(--theme-card-bg);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Form Labels */
    .theme-form-label {
        color: var(--theme-body-text);
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Form Inputs */
    .theme-form-input,
    .theme-form-select {
        width: 100%;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        padding: 0.5rem;
        color: var(--theme-body-text);
        background-color: white;
    }

    .theme-form-input:focus,
    .theme-form-select:focus {
        outline: none;
        ring: 2px solid #40E0D0;
        border-color: #40E0D0;
    }

    /* Buttons */
    .theme-button {
        background-color: var(--theme-button-bg);
        color: var(--theme-button-text);
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .theme-button:hover {
        background-color: var(--theme-button-bg-hover);
    }

    /* Divider */
    .theme-divider {
        border-top: 1px solid #e5e7eb;
        margin: 2rem 0;
    }

    /* Grid */
    .theme-grid {
        display: grid;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .theme-grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .theme-grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .theme-grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Calendar Report Section -->
                    <div class="theme-form-card">
                        <h3 class="text-lg font-medium theme-section-title">Generate Calendar Report</h3>
                        
                        <form method="GET" action="{{ route('reports.calendar') }}" class="mb-4">
                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-3 gap-4">
                                <div>
                                    <label for="child_id_calendar" class="theme-form-label">{{ __('Child') }}</label>
                                    <select id="child_id_calendar" name="child_id" class="theme-form-select">
                                        <option value="">{{ __('All Children') }}</option>
                                        @foreach ($children as $child)
                                            <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="start_date_calendar" class="theme-form-label">{{ __('Start Date') }}</label>
                                    <input id="start_date_calendar" class="theme-form-input" type="date" name="start_date" 
                                           value="{{ request('start_date') }}" />
                                </div>
                                
                                <div>
                                    <label for="end_date_calendar" class="theme-form-label">{{ __('End Date') }}</label>
                                    <input id="end_date_calendar" class="theme-form-input" type="date" name="end_date" 
                                           value="{{ request('end_date') }}" />
                                </div>
                            </div>
                            
                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="type_calendar" class="theme-form-label">{{ __('Type') }}</label>
                                    <select id="type_calendar" name="type" class="theme-form-select">
                                        <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>{{ __('Events and Visitations') }}</option>
                                        <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>{{ __('Events Only') }}</option>
                                        <option value="visitation" {{ request('type') == 'visitation' ? 'selected' : '' }}>{{ __('Visitations Only') }}</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="assigned_to_calendar" class="theme-form-label">{{ __('Assigned To') }}</label>
                                    <select id="assigned_to_calendar" name="assigned_to" class="theme-form-select">
                                        <option value="">{{ __('All Users') }}</option>
                                        @foreach (auth()->user()->getFamilyMemberIds() as $userId)
                                            @php
                                                $user = \App\Models\User::find($userId);
                                            @endphp
                                            @if ($user)
                                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="format_calendar" class="theme-form-label">{{ __('Format') }}</label>
                                    <select id="format_calendar" name="format" class="theme-form-select" required>
                                        <option value="pdf" {{ request('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                        <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="theme-button">
                                    {{ __('Generate Calendar Report') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Divider -->
                    <div class="theme-divider"></div>

                    <!-- Expense Report Section -->
                    <div class="theme-form-card">
                        <h3 class="text-lg font-medium theme-section-title">Generate Expense Report</h3>
                        
                        <form method="GET" action="{{ route('reports.expenses') }}">
                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-3 gap-4">
                                <div>
                                    <label for="child_id_expense" class="theme-form-label">{{ __('Child') }}</label>
                                    <select id="child_id_expense" name="child_id" class="theme-form-select">
                                        <option value="">{{ __('All Children') }}</option>
                                        @foreach ($children as $child)
                                            <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="start_date_expense" class="theme-form-label">{{ __('Start Date') }}</label>
                                    <input id="start_date_expense" class="theme-form-input" type="date" name="start_date" 
                                           value="{{ request('start_date') }}" />
                                </div>
                                
                                <div>
                                    <label for="end_date_expense" class="theme-form-label">{{ __('End Date') }}</label>
                                    <input id="end_date_expense" class="theme-form-input" type="date" name="end_date" 
                                           value="{{ request('end_date') }}" />
                                </div>
                            </div>
                            
                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="category_expense" class="theme-form-label">{{ __('Category') }}</label>
                                    <select id="category_expense" name="category" class="theme-form-select">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach (['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'] as $category)
                                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="status_expense" class="theme-form-label">{{ __('Status') }}</label>
                                    <select id="status_expense" name="status" class="theme-form-select">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        @foreach (['pending', 'paid', 'disputed'] as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="format_expense" class="theme-form-label">{{ __('Format') }}</label>
                                <select id="format_expense" name="format" class="theme-form-select" required>
                                    <option value="pdf" {{ request('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="theme-button">
                                    {{ __('Generate Expense Report') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>