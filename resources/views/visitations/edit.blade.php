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

                        <!-- Child -->
                        <div>
                            <x-input-label for="child_id" :value="__('Child')" />
                            <select id="child_id" name="child_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a Child</option>
                                @foreach ($children as $child)
                                    <option value="{{ $child->id }}" {{ old('child_id', $visitation->child_id) == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('child_id')" class="mt-2" />
                        </div>

                        <!-- Date Start -->
                        <div class="mt-4">
                            <x-input-label for="date_start" :value="__('Start Date and Time')" />
                            <x-text-input id="date_start" class="block mt-1 w-full" type="datetime-local" name="date_start" :value="old('date_start', \Carbon\Carbon::parse($visitation->date_start)->format('Y-m-d\\TH:i'))" required />
                            <x-input-error :messages="$errors->get('date_start')" class="mt-2" />
                        </div>

                        <!-- Date End -->
                        <div class="mt-4">
                            <x-input-label for="date_end" :value="__('End Date and Time')" />
                            <x-text-input id="date_end" class="block mt-1 w-full" type="datetime-local" name="date_end" :value="old('date_end', \Carbon\Carbon::parse($visitation->date_end)->format('Y-m-d\\TH:i'))" required />
                            <x-input-error :messages="$errors->get('date_end')" class="mt-2" />
                        </div>

                        <!-- Is Recurring -->
                        <div class="mt-4 flex items-center">
                            <input id="is_recurring" name="is_recurring" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_recurring', $visitation->is_recurring) ? 'checked' : '' }}>
                            <x-input-label for="is_recurring" :value="__('Is Recurring?')" class="ml-2" />
                            <x-input-error :messages="$errors->get('is_recurring')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <x-text-area id="notes" class="block mt-1 w-full" name="notes">{{ old('notes', $visitation->notes) }}</x-text-area>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update Visitation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>