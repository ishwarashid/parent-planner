<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Visitation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('visitations.update', $visitation) }}">
                        @csrf
                        @method('PUT')

                        <!-- NEW: Assign To -->
                        <div>
                            <x-input-label for="parent_id" :value="__('Assign To')" />
                            <select id="parent_id" name="parent_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="">Select a Family Member</option>
                                @foreach ($familyMembers as $member)
                                    {{-- Pre-select the currently assigned parent, or the old input if validation failed --}}
                                    <option value="{{ $member->id }}"
                                        {{ old('parent_id', $visitation->parent_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                        </div>

                        <!-- Child -->
                        <div class="mt-4">
                            <x-input-label for="child_id" :value="__('Child')" />
                            <select id="child_id" name="child_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="">Select a Child</option>
                                @foreach ($children as $child)
                                    <option value="{{ $child->id }}"
                                        {{ old('child_id', $visitation->child_id) == $child->id ? 'selected' : '' }}>
                                        {{ $child->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('child_id')" class="mt-2" />
                        </div>

                        <!-- Date Start -->
                        <div class="mt-4">
                            <x-input-label for="date_start" :value="__('Start Date and Time')" />
                            <x-text-input id="date_start" class="block mt-1 w-full" type="datetime-local"
                                name="date_start" :value="old(
                                    'date_start',
                                    \Carbon\Carbon::parse($visitation->date_start)->format('Y-m-d\TH:i'),
                                )" required />
                            <x-input-error :messages="$errors->get('date_start')" class="mt-2" />
                        </div>

                        <!-- Date End -->
                        <div class="mt-4">
                            <x-input-label for="date_end" :value="__('End Date and Time')" />
                            <x-text-input id="date_end" class="block mt-1 w-full" type="datetime-local" name="date_end"
                                :value="old(
                                    'date_end',
                                    \Carbon\Carbon::parse($visitation->date_end)->format('Y-m-d\TH:i'),
                                )" required />
                            <x-input-error :messages="$errors->get('date_end')" class="mt-2" />
                        </div>

                        <!-- NEW: Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="Scheduled"
                                    {{ old('status', $visitation->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled
                                </option>
                                <option value="Completed"
                                    {{ old('status', $visitation->status) == 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="Cancelled"
                                    {{ old('status', $visitation->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                                <option value="Missed"
                                    {{ old('status', $visitation->status) == 'Missed' ? 'selected' : '' }}>Missed
                                </option>
                                <option value="Rescheduled"
                                    {{ old('status', $visitation->status) == 'Rescheduled' ? 'selected' : '' }}>Rescheduled
                                </option>
                                <option value="Other"
                                    {{ old('status', $visitation->status) == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Custom Status Description (shown only when "Other" is selected) -->
                        <div id="custom-status-description-container" class="mt-4" 
                             style="{{ old('status', $visitation->status) == 'Other' ? 'display: block;' : 'display: none;' }}">
                            <x-input-label for="custom_status_description" :value="__('Custom Status Description')" />
                            <x-text-input id="custom_status_description" class="block mt-1 w-full" type="text"
                                name="custom_status_description" :value="old('custom_status_description', $visitation->custom_status_description)" maxlength="255" />
                            <x-input-error :messages="$errors->get('custom_status_description')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Please provide a description for the 'Other' status.</p>
                        </div>

                        <!-- Is Recurring -->
                        <div class="mt-4 flex items-center">
                            <input id="is_recurring" name="is_recurring" type="checkbox" value="1"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                {{ old('is_recurring', $visitation->is_recurring) ? 'checked' : '' }}>
                            <x-input-label for="is_recurring" :value="__('Is Recurring?')" class="ml-2" />
                        </div>

                        <!-- Recurrence Pattern -->
                        <div id="recurrence-fields" class="mt-4" style="{{ old('is_recurring', $visitation->is_recurring) ? '' : 'display: none;' }}">
                            <x-input-label for="recurrence_pattern" :value="__('Recurrence Pattern')" />
                            <select id="recurrence_pattern" name="recurrence_pattern"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Pattern</option>
                                <option value="daily" {{ old('recurrence_pattern', $visitation->recurrence_pattern) == 'daily' ? 'selected' : '' }}>
                                    Daily
                                </option>
                                <option value="weekly" {{ old('recurrence_pattern', $visitation->recurrence_pattern) == 'weekly' ? 'selected' : '' }}>
                                    Weekly
                                </option>
                                <option value="monthly" {{ old('recurrence_pattern', $visitation->recurrence_pattern) == 'monthly' ? 'selected' : '' }}>
                                    Monthly
                                </option>
                                <option value="yearly" {{ old('recurrence_pattern', $visitation->recurrence_pattern) == 'yearly' ? 'selected' : '' }}>
                                    Yearly
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('recurrence_pattern')" class="mt-2" />
                        </div>

                        <!-- Recurrence End Date -->
                        <div id="recurrence-end-date-field" class="mt-4" style="{{ old('is_recurring', $visitation->is_recurring) ? '' : 'display: none;' }}">
                            <x-input-label for="recurrence_end_date" :value="__('Recurrence End Date')" />
                            <x-text-input id="recurrence_end_date" class="block mt-1 w-full" type="date"
                                name="recurrence_end_date" :value="old('recurrence_end_date', $visitation->recurrence_end_date)" />
                            <x-input-error :messages="$errors->get('recurrence_end_date')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <x-text-area id="notes" class="block mt-1 w-full"
                                name="notes">{{ old('notes', $visitation->notes) }}</x-text-area>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('visitations.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Update Visitation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const customDescriptionContainer = document.getElementById('custom-status-description-container');
            const customDescriptionField = document.getElementById('custom_status_description');
            const recurringCheckbox = document.getElementById('is_recurring');
            const recurrenceFields = document.getElementById('recurrence-fields');
            const recurrenceEndDateField = document.getElementById('recurrence-end-date-field');

            function toggleCustomDescriptionVisibility() {
                if (statusSelect.value === 'Other') {
                    customDescriptionContainer.style.display = 'block';
                } else {
                    customDescriptionContainer.style.display = 'none';
                    // Only clear the field if it wasn't previously 'Other'
                    if (customDescriptionField.value && statusSelect.value !== 'Other') {
                        customDescriptionField.value = '';
                    }
                }
            }

            // Toggle recurrence fields visibility based on checkbox
            function toggleRecurrenceFieldsVisibility() {
                if (recurringCheckbox.checked) {
                    recurrenceFields.style.display = 'block';
                    recurrenceEndDateField.style.display = 'block';
                } else {
                    recurrenceFields.style.display = 'none';
                    recurrenceEndDateField.style.display = 'none';
                }
            }

            // Show/hide custom description based on initial value
            toggleCustomDescriptionVisibility();

            // Show/hide recurrence fields based on initial value
            toggleRecurrenceFieldsVisibility();

            // Add event listener to status select
            statusSelect.addEventListener('change', toggleCustomDescriptionVisibility);

            // Add event listener to recurring checkbox
            recurringCheckbox.addEventListener('change', toggleRecurrenceFieldsVisibility);
        });
    </script>
</x-app-layout>
