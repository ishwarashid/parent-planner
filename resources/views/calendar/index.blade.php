<x-app-layout>
    <style>
        /* This style block is identical to your last version and remains correct */
        :root {
            --theme-header: #000033;
            --theme-main-title: #1A237E;
            --theme-section-title: #2F4F4F;
            --theme-body-text: #1D2951;
            --theme-button-primary-bg: #40E0D0;
            --theme-button-primary-text: #000033;
            --theme-button-primary-bg-hover: #48D1CC;
            --theme-label: #1D2951;
            --theme-input-border: #AFEEEE;
            --theme-input-focus-border: #40E0D0;
            --fc-button-bg: #2F4F4F;
            --fc-button-text: #FFFFFF;
            --fc-button-hover-bg: #008080;
            --fc-today-bg: rgba(175, 238, 238, 0.2);
            --fc-highlight-bg: rgba(64, 224, 208, 0.3);
        }

        /* General Styling */
        .theme-header-text {
            color: var(--theme-header);
        }

        .theme-button {
            background-color: var(--theme-button-primary-bg);
            color: var(--theme-button-primary-text);
            font-weight: 700;
            transition: background-color 0.2s;
            border: none;
        }

        .theme-button:hover {
            background-color: var(--theme-button-primary-bg-hover);
        }

        /* Legend Styling */
        .theme-legend-title {
            color: var(--theme-section-title);
            font-size: 1.125rem;
        }

        .theme-legend-subtitle {
            color: var(--theme-body-text);
            font-weight: 600;
            font-size: 0.875rem;
            /* text-sm */
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .theme-legend-text {
            color: var(--theme-body-text);
        }

        /* FullCalendar Overrides */
        #general-calendar .fc-toolbar-title {
            color: var(--fc-button-bg);
        }

        #general-calendar .fc-button {
            background-color: var(--fc-button-bg) !important;
            color: var(--fc-button-text) !important;
            border: none !important;
            box-shadow: none !important;
            transition: background-color 0.2s;
        }

        #general-calendar .fc-button:hover,
        #general-calendar .fc-button.fc-button-active {
            background-color: var(--fc-button-hover-bg) !important;
        }

        #general-calendar .fc-daygrid-day-header {
            border-right: none !important;
            color: var(--theme-body-text);
        }

        #general-calendar .fc-day-today {
            background-color: var(--fc-today-bg) !important;
        }

        #general-calendar .fc-event-title {
            color: var(--theme-body-text) !important;
            font-weight: 500;
        }

        #general-calendar .fc-col-header-cell,
        #general-calendar .fc-daygrid-day {
            border-color: #e5e7eb;
        }

        #general-calendar .fc-highlight {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--fc-highlight-bg) !important;
            border-radius: 0 !important;
        }

        .fc-daygrid-event-dot {
            border-width: 8px !important;
            border-radius: 8px !important;
        }

        /* Modal Styles */
        .theme-modal-label {
            color: var(--theme-label);
            font-weight: 600;
        }

        .theme-modal-input {
            border-color: var(--theme-input-border) !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .theme-modal-input:focus {
            border-color: var(--theme-input-focus-border) !important;
            box-shadow: 0 0 0 1px var(--theme-input-focus-border) !important;
            outline: none;
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl leading-tight theme-header-text">
                {{ __('Calendar') }}
            </h2>
            @can('create', App\Models\Event::class)
                <button x-data="{}" @click="$dispatch('open-modal', 'create-event-modal')"
                    class="py-2 px-4 rounded-md theme-button">
                    Add New Event
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ============================================= -->
            <!-- === UPDATED LEGEND WITH EVENT STATUSES === -->
            <!-- ============================================= -->
            <div class="mb-4 p-4 bg-white rounded-lg shadow-sm">
                <h3 class="font-semibold mb-3 theme-legend-title">Legend</h3>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3">

                    <!-- Event Types Section -->
                    <div class="w-full mb-2">
                        <h4 class="theme-legend-subtitle">Event Types</h4>
                    </div>
                    <div class="flex items-center">
                        <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                            style="background-color: #3788d8;"></span>
                        <span class="ml-2 text-sm theme-legend-text">Scheduled</span>
                    </div>
                    <div class="flex items-center">
                        <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                            style="background-color: #28a745;"></span>
                        <span class="ml-2 text-sm theme-legend-text">Completed</span>
                    </div>
                    <div class="flex items-center">
                        <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                            style="background-color: #808080;"></span>
                        <span class="ml-2 text-sm theme-legend-text">Cancelled</span>
                    </div>

                    <!-- Children Section -->
                    <div class="w-full mt-4 mb-2 border-t pt-4">
                        <h4 class="theme-legend-subtitle">Children</h4>
                    </div>
                    @foreach ($children as $child)
                        @if ($child->color)
                            <div class="flex items-center">
                                <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                                    style="background-color: {{ $child->color }};"></span>
                                <span class="ml-2 text-sm theme-legend-text">{{ $child->name }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id='general-calendar' data-events='@json($events)'></div>
                </div>
            </div>
        </div>
    </div>

    @include('calendar.create-event-modal')
    @include('calendar.edit-event-modal')
</x-app-layout>
