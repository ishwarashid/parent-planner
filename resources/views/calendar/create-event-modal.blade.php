{{-- resources/views/calendar/create-event-modal.blade.php --}}

<x-modal name="create-event-form" title="Create New Event">
    <form id="createEventForm">
        @csrf

        {{-- Include the shared form fields --}}
        @include('calendar._event-form-fields')

        <div class="flex items-center justify-end mt-6">
            <button type="button" x-on:click="show = false"
                class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
                Cancel
            </button>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Event
            </button>
        </div>
    </form>
</x-modal>