{{-- resources/views/calendar/edit-event-modal.blade.php --}}

<x-modal name="edit-event-form" title="Edit Event">
    <form id="editEventForm">
        @csrf
        @method('PUT')
        <input type="hidden" id="edit_event_id" name="event_id">

        {{-- Include the shared form fields --}}
        @include('calendar._event-form-fields', ['children' => $children])

        <div class="flex items-center justify-between mt-6">
            {{-- Delete button is on the left --}}
            <button type="button" id="deleteEventButton"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Delete
            </button>

            {{-- Cancel and Save buttons are on the right --}}
            <div>
                <button type="button" x-on:click="$dispatch('close')"
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
