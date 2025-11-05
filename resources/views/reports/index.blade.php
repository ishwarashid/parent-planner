<x-app-layout>
    <style>
        /* Theme color variables from your new palette */
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

            /* Buttons */
            --theme-button-primary-bg: #FF5722;
            /* Deep Orange */
            --theme-button-primary-text: #FFFFFF;
            --theme-button-primary-bg-hover: #FF6F61;
            /* Coral on hover */

            --theme-button-secondary-bg: #2F4F4F;
            /* Dark Slate Gray */
            --theme-button-secondary-text: #FFFFFF;
            --theme-button-secondary-bg-hover: #008080;
            /* Teal on hover */

            --theme-error-text: #FF6F61;
            /* Coral */
        }

        /* General Styling */
        .theme-header-text {
            color: var(--theme-header);
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d5db; /* gray-300 */
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--theme-button-primary-bg);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .theme-info-text {
            color: var(--theme-label);
        }

        .theme-required-star {
            color: var(--theme-required-star);
        }

        /* Form Inputs & Labels */
        .theme-input-label {
            color: var(--theme-label);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .theme-input,
        .theme-select {
            width: 100%;
            border-radius: 0.375rem;
            border: 1px solid var(--theme-input-border);
            padding: 0.5rem;
            color: var(--theme-label);
            background-color: white;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .theme-input:focus,
        .theme-select:focus {
            border-color: var(--theme-input-focus-border);
            box-shadow: 0 0 0 1px var(--theme-input-focus-border);
            outline: none;
        }

        /* Buttons */
        .theme-button {
            font-weight: 700;
            transition: background-color 0.2s;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .theme-button-primary {
            background-color: var(--theme-button-primary-bg);
            color: var(--theme-button-primary-text);
        }

        .theme-button-primary:hover {
            background-color: var(--theme-button-primary-bg-hover);
        }

        /* Form Card Styling */
        .theme-form-card {
            background-color: #f9fafb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Section Titles */
        .theme-section-title {
            color: var(--theme-header);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.125rem;
            line-height: 1.75rem;
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
                        <h3 class="theme-section-title">Generate Calendar Report</h3>

                        <form method="GET" action="{{ route('reports.calendar') }}" class="mb-4">
                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-3 gap-4">
                                <div>
                                    <label for="child_id_calendar" class="theme-input-label">{{ __('Child') }}</label>
                                    <select id="child_id_calendar" name="child_id" class="theme-select">
                                        <option value="">{{ __('All Children') }}</option>
                                        @foreach ($children as $child)
                                            <option value="{{ $child->id }}"
                                                {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="start_date_calendar"
                                        class="theme-input-label">{{ __('Start Date') }}</label>
                                    <input id="start_date_calendar" class="theme-input" type="date" name="start_date"
                                        value="{{ request('start_date') }}" />
                                </div>

                                <div>
                                    <label for="end_date_calendar"
                                        class="theme-input-label">{{ __('End Date') }}</label>
                                    <input id="end_date_calendar" class="theme-input" type="date" name="end_date"
                                        value="{{ request('end_date') }}" />
                                </div>
                            </div>

                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="type_calendar" class="theme-input-label">{{ __('Type') }}</label>
                                    <select id="type_calendar" name="type" class="theme-select">
                                        <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>
                                            {{ __('Events and Visitations') }}</option>
                                        <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>
                                            {{ __('Events Only') }}</option>
                                        <option value="visitation"
                                            {{ request('type') == 'visitation' ? 'selected' : '' }}>
                                            {{ __('Visitations Only') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="assigned_to_calendar"
                                        class="theme-input-label">{{ __('Assigned To') }}</label>
                                    <select id="assigned_to_calendar" name="assigned_to" class="theme-select">
                                        <option value="">{{ __('All Users') }}</option>
                                        @foreach (auth()->user()->getFamilyMemberIds() as $userId)
                                            @php
                                                $user = \App\Models\User::find($userId);
                                            @endphp
                                            @if ($user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="format_calendar" class="theme-input-label">{{ __('Format') }}</label>
                                    <select id="format_calendar" name="format" class="theme-select" required>
                                        <option value="pdf" {{ request('format') == 'pdf' ? 'selected' : '' }}>PDF
                                        </option>
                                        <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="theme-button theme-button-primary">
                                    {{ __('Generate Calendar Report') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Divider -->
                    <div class="theme-divider"></div>

                    <!-- Expense Report Section -->
                    <div class="theme-form-card">
                        <h3 class="theme-section-title">Generate Expense Report</h3>

                        <form method="GET" action="{{ route('reports.expenses') }}">
                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-3 gap-4">
                                <div>
                                    <label for="child_id_expense" class="theme-input-label">{{ __('Child') }}</label>
                                    <select id="child_id_expense" name="child_id" class="theme-select">
                                        <option value="">{{ __('All Children') }}</option>
                                        @foreach ($children as $child)
                                            <option value="{{ $child->id }}"
                                                {{ request('child_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="start_date_expense"
                                        class="theme-input-label">{{ __('Start Date') }}</label>
                                    <input id="start_date_expense" class="theme-input" type="date" name="start_date"
                                        value="{{ request('start_date') }}" />
                                </div>

                                <div>
                                    <label for="end_date_expense"
                                        class="theme-input-label">{{ __('End Date') }}</label>
                                    <input id="end_date_expense" class="theme-input" type="date" name="end_date"
                                        value="{{ request('end_date') }}" />
                                </div>
                            </div>

                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="category_expense"
                                        class="theme-input-label">{{ __('Category') }}</label>
                                    <select id="category_expense" name="category" class="theme-select">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach (['Healthcare', 'Education', 'Childcare', 'Food', 'Clothing', 'Activities', 'Other'] as $category)
                                            <option value="{{ $category }}"
                                                {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="status_expense" class="theme-input-label">{{ __('Status') }}</label>
                                    <select id="status_expense" name="status" class="theme-select">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        @foreach (['pending', 'paid', 'disputed'] as $status)
                                            <option value="{{ $status }}"
                                                {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="theme-grid theme-grid-cols-1 md:theme-grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="format_expense" class="theme-input-label">{{ __('Format') }}</label>
                                    <select id="format_expense" name="format" class="theme-select" required>
                                        <option value="pdf" {{ request('format') == 'pdf' ? 'selected' : '' }}>PDF
                                        </option>
                                        <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="include_receipts" class="flex items-center theme-input-label cursor-pointer">
                                        <div class="toggle-switch">
                                            <input 
                                                id="include_receipts" 
                                                type="checkbox" 
                                                name="include_receipts" 
                                                value="1" 
                                                {{ request('include_receipts') ? 'checked' : '' }}
                                            >
                                            <span class="toggle-slider"></span>
                                        </div>
                                        <span class="ml-3">{{ __('Include Receipts') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="theme-button theme-button-primary">
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
