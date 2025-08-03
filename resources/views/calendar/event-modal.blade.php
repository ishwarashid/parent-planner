<x-modal name="event-form" title="Event Details">
    <form id="eventForm">
        @csrf
        <input type="hidden" id="event_id" name="event_id">

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <!-- Description -->
        <div class="mt-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <!-- Start Time -->
        <div class="mt-4">
            <label for="start" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="datetime-local" id="start" name="start" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <!-- End Time -->
        <div class="mt-4">
            <label for="end" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="datetime-local" id="end" name="end" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="button" x-on:click="$dispatch('close-modal')" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
                Cancel
            </button>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Save Event
            </button>
            <button type="button" id="deleteEventButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
                Delete
            </button>
        </div>
    </form>
</x-modal>
