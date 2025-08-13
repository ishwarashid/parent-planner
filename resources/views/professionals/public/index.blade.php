<x-app-layout>
    <style>
        /* Theme color variables from your new palette */
        :root {
            --theme-header: #000033;
            /* Midnight Blue */
            --theme-body-text: #1D2951;
            /* Muted Navy */
            --theme-label: #1D2951;
            --theme-input-border: #AFEEEE;
            /* Pale Turquoise */
            --theme-input-focus-border: #40E0D0;
            /* Turquoise */

            /* Buttons */
            --theme-button-primary-bg: #f76c42;
            /* Deep Orange for filters */
            --theme-button-primary-text: #FFFFFF;
            --theme-button-primary-bg-hover: #FF6F61;
            --theme-button-card-bg: #40E0D0;
            /* Turquoise for card actions */
            --theme-button-card-text: #000033;
            --theme-button-card-bg-hover: #48D1CC;

            /* Links */
            --theme-link-color: #008080;
            /* Teal */
            --theme-link-hover-color: #2F4F4F;

            /* Card */
            --theme-card-bg: #f9fafb;
            /* Off-white for cards */
            --theme-card-border: #e5e7eb;
            /* gray-200 */
            --theme-card-title: #1A237E;
            /* Dark Indigo */
            --theme-card-subtitle: #2F4F4F;
            --theme-card-footer-bg: #FFFFFF;
        }

        /* General Styling */
        .theme-header-text {
            color: var(--theme-header);
        }

        /* Form Inputs & Labels */
        .theme-input-label {
            color: var(--theme-label);
            font-weight: 600;
        }

        .theme-input,
        .theme-select {
            border-color: var(--theme-input-border) !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .theme-input:focus,
        .theme-select:focus {
            border-color: var(--theme-input-focus-border) !important;
            box-shadow: 0 0 0 1px var(--theme-input-focus-border) !important;
            outline: none;
        }

        /* Buttons */
        .theme-button {
            font-weight: 700;
            transition: background-color 0.2s;
            border: none;
            text-decoration: none;
        }

        .theme-button-primary {
            background-color: var(--theme-button-primary-bg);
            color: var(--theme-button-primary-text);
        }

        .theme-button-primary:hover {
            background-color: var(--theme-button-primary-bg-hover);
        }

        .theme-button-card {
            background-color: var(--theme-button-card-bg);
            color: var(--theme-button-card-text);
        }

        .theme-button-card:hover {
            background-color: var(--theme-button-card-bg-hover);
        }

        /* Links */
        .theme-link {
            color: var(--theme-link-color);
            transition: color 0.2s;
        }

        .theme-link:hover {
            color: var(--theme-link-hover-color);
            text-decoration: underline;
        }

        /* Professional Card */
        .theme-card {
            background-color: var(--theme-card-bg);
            border-color: var(--theme-card-border);
        }

        .theme-card-title {
            color: var(--theme-card-title);
        }

        .theme-card-subtitle {
            color: var(--theme-card-subtitle);
        }

        .theme-card-body-text {
            color: var(--theme-body-text);
        }

        .theme-card-footer {
            background-color: var(--theme-card-footer-bg);
            border-top: 1px solid var(--theme-card-border);
        }

        .theme-card .location-icon {
            color: #9CA3AF;
        }

        /* gray-400 */

        /* Pagination Styling */
        .pagination .page-item .page-link {
            color: var(--theme-link-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--theme-link-color);
            border-color: var(--theme-link-color);
            color: #fff;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Find a Professional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('professionals.public.index') }}" method="GET"
                        class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div class="md:col-span-2">
                                <x-input-label for="search" class="theme-input-label" :value="__('Search Keyword')" />
                                <x-text-input id="search" type="text" name="search"
                                    class="w-full mt-1 theme-input" placeholder="e.g., Wellness, London, Legal..."
                                    value="{{ request('search') }}" />
                            </div>
                            <div>
                                <x-input-label for="continent_filter" class="theme-input-label" :value="__('Filter by Continent')" />
                                <select name="continent_filter" id="continent_filter"
                                    class="w-full mt-1 rounded-md shadow-sm theme-select">
                                    <option value="">All Continents</option>
                                    @foreach ($continents as $continent)
                                        <option value="{{ $continent }}"
                                            {{ request('continent_filter') == $continent ? 'selected' : '' }}>
                                            {{ $continent }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="service_filter" class="theme-input-label" :value="__('Filter by Service')" />
                                <select name="service_filter" id="service_filter"
                                    class="w-full mt-1 rounded-md shadow-sm theme-select">
                                    <option value="">All Services</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service }}"
                                            {{ request('service_filter') == $service ? 'selected' : '' }}>
                                            {{ $service }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center mt-4">
                            <x-primary-button class="theme-button theme-button-primary">
                                {{ __('Apply Filters') }}
                            </x-primary-button>
                            <a href="{{ route('professionals.public.index') }}"
                                class="ms-3 text-sm theme-link">Reset</a>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($professionals as $professional)
                            <div class="border rounded-lg shadow-sm flex flex-col justify-between theme-card">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold theme-card-title">
                                        {{ $professional->business_name }}</h3>
                                    @php
                                        $location = implode(
                                            ', ',
                                            array_filter([
                                                $professional->city,
                                                $professional->country,
                                                $professional->continent,
                                            ]),
                                        );
                                    @endphp
                                    <p class="text-sm mt-1 theme-card-subtitle">
                                        <svg class="inline-block w-4 h-4 mr-1 location-icon" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $location ?: 'Location not provided' }}
                                    </p>
                                    <div class="mt-4">
                                        <p class="text-xs uppercase font-semibold theme-card-subtitle">Services</p>
                                        <p class="text-sm mt-1 theme-card-body-text">
                                            {{ Str::limit(implode(', ', $professional->services ?? []), 120) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="p-4 flex items-center justify-between theme-card-footer">
                                    <a href="{{ route('professionals.public.show', $professional) }}"
                                        class="w-full text-center py-2 px-4 rounded transition duration-300 theme-button theme-button-card">
                                        View Profile
                                    </a>
                                    @if ($professional->website)
                                        <a href="{{ $professional->website }}" target="_blank"
                                            rel="noopener noreferrer" class="ml-2 text-gray-400 hover:text-gray-600"
                                            title="Visit Website">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-10">
                                <p>No professionals found matching your criteria.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $professionals->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
