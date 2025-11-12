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
                <button x-data="{}" @click="$dispatch('open-modal', 'create-event-form')"
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
                    <div class="flex items-center">
                        <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                            style="background-color: #dc3545;"></span>
                        <span class="ml-2 text-sm theme-legend-text">Missed</span>
                    </div>
                    <div class="flex items-center">
                        <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                            style="background-color: #ffc107;"></span>
                        <span class="ml-2 text-sm theme-legend-text">Rescheduled</span>
                    </div>
                    <div class="flex items-center">
                        <span class="h-4 w-4 rounded-full inline-block border border-gray-300"
                            style="background-color: #6f42c1;"></span>
                        <span class="ml-2 text-sm theme-legend-text">Other</span>
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
    
    {{-- Visitation Form Modal --}}
    <x-modal name="visitation-form" title="Visitation Details">
        <form id="visitationForm" method="POST" action="{{ route('visitations.store') }}" class="space-y-2">
            @csrf

            <div class="max-h-[70vh] overflow-y-auto pr-2">  <!-- Scrollable container -->
                <!-- Basic Info Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="parent_id" class="block text-sm theme-modal-label">Assign To</label>
                        <select id="parent_id" name="parent_id" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                            <option value="">Select a Family Member</option>
                            @foreach ($familyMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="child_id" class="block text-sm theme-modal-label">Child</label>
                        <select id="child_id" name="child_id" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                            @foreach ($children as $child)
                                <option value="{{ $child->id }}">{{ $child->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="date_start" class="block text-sm theme-modal-label">Start Time</label>
                        <input type="datetime-local" id="date_start" name="date_start"
                            class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                    </div>

                    <div>
                        <label for="date_end" class="block text-sm theme-modal-label">End Time</label>
                        <input type="datetime-local" id="date_end" name="date_end"
                            class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm theme-modal-label">Status</label>
                    <select id="status" name="status"
                        class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                        <option value="Scheduled" selected>Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Missed">Missed</option>
                        <option value="Rescheduled">Rescheduled</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Custom Status Description -->
                <div id="custom-status-description-container" class="mt-4" style="display: none;">
                    <label for="custom_status_description" class="block text-sm theme-modal-label">Custom Status Description</label>
                    <input type="text" id="custom_status_description" name="custom_status_description"
                        class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"
                        maxlength="255">
                    <p class="mt-1 text-sm text-gray-500">Please provide a description for the 'Other' status.</p>
                </div>

                <!-- Recurring Section -->
                <div class="mt-4 border-t pt-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="is_recurring" name="is_recurring" type="checkbox" value="1"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_recurring" class="ml-2 block text-sm theme-modal-label">Is Recurring?</label>
                        </div>
                    </div>

                    <!-- Recurrence Options (shown when recurring is checked) -->
                    <div id="recurrence-fields" class="mt-4 space-y-4" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="recurrence_pattern" class="block text-sm theme-modal-label">Recurrence Pattern</label>
                                <select id="recurrence_pattern" name="recurrence_pattern"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
                                    <option value="">Select Pattern</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>

                            <div>
                                <label for="recurrence_end_date" class="block text-sm theme-modal-label">End Date</label>
                                <input type="date" id="recurrence_end_date" name="recurrence_end_date"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm theme-modal-label">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"></textarea>
                </div>
            </div>

            <!-- Buttons stay outside scrollable area -->
            <div class="flex items-center justify-end mt-6 space-x-4 pt-4 border-t">
                <button type="button" x-on:click="$dispatch('close-modal')"
                    class="py-2 px-4 rounded theme-button theme-button-secondary">
                    Cancel
                </button>
                <button type="submit" class="py-2 px-4 rounded theme-button theme-button-primary">
                    Save Visitation
                </button>
            </div>
        </form>
    </x-modal>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if visitationForm exists before trying to manipulate it
            const visitationForm = document.getElementById('visitationForm');
            if (visitationForm) {
                // Safely get elements, handling cases where they might not exist
                const statusSelect = document.getElementById('status');
                const customDescriptionContainer = document.getElementById('custom-status-description-container');
                const customDescriptionField = document.getElementById('custom_status_description');
                const recurringCheckbox = document.getElementById('is_recurring');
                
                // Make sure to get the correct elements for recurrence
                const recurrenceFields = document.getElementById('recurrence-fields');
                const recurrenceEndDateField = document.querySelector('#recurrence-end-date-field') || document.getElementById('recurrence-end-date-field');

                function toggleCustomDescriptionVisibility() {
                    if (statusSelect && customDescriptionContainer && customDescriptionField) {
                        if (statusSelect.value === 'Other') {
                            customDescriptionContainer.style.display = 'block';
                        } else {
                            customDescriptionContainer.style.display = 'none';
                            customDescriptionField.value = '';
                        }
                    }
                }

                // Toggle recurrence fields visibility based on checkbox
                function toggleRecurrenceFieldsVisibility() {
                    if (recurringCheckbox && recurrenceFields) {
                        if (recurringCheckbox.checked) {
                            recurrenceFields.style.display = 'block';
                            if (recurrenceEndDateField) {
                                recurrenceEndDateField.style.display = 'block';
                            }
                        } else {
                            recurrenceFields.style.display = 'none';
                            if (recurrenceEndDateField) {
                                recurrenceEndDateField.style.display = 'none';
                            }
                            // Clear the values when unchecked
                            const patternField = document.getElementById('recurrence_pattern');
                            const endDateField = document.getElementById('recurrence_end_date');
                            if (patternField) patternField.value = '';
                            if (endDateField) endDateField.value = '';
                        }
                    }
                }

                // Show/hide based on initial values
                if (statusSelect && customDescriptionContainer && customDescriptionField) {
                    toggleCustomDescriptionVisibility();
                }
                if (recurringCheckbox) {
                    toggleRecurrenceFieldsVisibility();
                }

                // Add event listeners
                if (statusSelect) {
                    statusSelect.addEventListener('change', toggleCustomDescriptionVisibility);
                }
                if (recurringCheckbox) {
                    recurringCheckbox.addEventListener('change', toggleRecurrenceFieldsVisibility);
                }
            }
        });
    </script>
</x-app-layout>
