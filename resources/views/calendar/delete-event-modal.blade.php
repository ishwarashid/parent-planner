<x-modal name="delete-event-confirm" title="Confirm Deletion">
    <div class="p-4">
        <p class="text-sm text-gray-500">
            {{ __('Are you sure you want to delete this event? This action cannot be undone.') }}
        </p>
    </div>
    <div class="flex items-center justify-end p-4 bg-gray-50">
        <button type="button" x-on:click="$dispatch('close-modal')" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded mr-2">
            {{ __('Cancel') }}
        </button>
        <button type="button" id="confirmDeleteButton" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Delete Event') }}
        </button>
    </div>
</x-modal>