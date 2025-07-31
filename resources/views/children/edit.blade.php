<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Child') }}: {{ $child->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('children.update', $child) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $child->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Date of Birth -->
                        <div class="mt-4">
                            <x-input-label for="dob" :value="__('Date of Birth')" />
                            <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob', $child->dob)" required />
                            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                        </div>

                        <!-- Allergies -->
                        <div class="mt-4">
                            <x-input-label for="allergies" :value="__('Allergies')" />
                            <x-text-area id="allergies" class="block mt-1 w-full" name="allergies">{{ old('allergies', $child->allergies) }}</x-text-area>
                            <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                        </div>

                        <!-- School Info -->
                        <div class="mt-4">
                            <x-input-label for="school_info" :value="__('School Info')" />
                            <x-text-input id="school_info" class="block mt-1 w-full" type="text" name="school_info" :value="old('school_info', $child->school_info)" />
                            <x-input-error :messages="$errors->get('school_info')" class="mt-2" />
                        </div>

                        <!-- Profile Photo -->
                        <div class="mt-4">
                            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
                            @if ($child->profile_photo)
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('storage/' . $child->profile_photo) }}" alt="Current Profile Photo" class="h-20 w-20 rounded-full object-cover">
                                </div>
                            @endif
                            <input id="profile_photo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="profile_photo" />
                            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update Child') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>