{{-- resources/views/calendar/edit-event-modal.blade.php --}}

<x-modal name="edit-event-form" title="Edit Event">
    <form id="editEventForm">
        @csrf
        @method('PUT')
        <input type="hidden" id="edit_event_id" name="event_id">

        {{-- Include the shared form fields --}}
        @include('calendar._event-form-fields', ['children' => $children])
        
        <div class="mt-4 border-t pt-4">
            <label for="edit_status" class="block text-sm theme-modal-label">Status</label>
            <p class="text-xs text-gray-500 mb-2">Update the status of this event.</p>
            <select id="edit_status" name="status"
                class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
                <option value="Scheduled">Scheduled</option>
                <option value="Completed">Completed</option>
                <option value="Missed">Missed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <div class="flex items-center justify-between mt-6">
            {{-- Delete button is on the left --}}
            @can('delete-events')
                <button type="button" id="deleteEventButton"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            @endcan

            {{-- Cancel and Save buttons are on the right --}}
            <div>
                <button type="button" x-on:click="show = false"
                    class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</x-modal>
