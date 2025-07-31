<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Child') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-sm text-gray-500 mb-4">Fields marked with <span class="text-red-500">*</span> are required.</p>
                    <form method="POST" action="{{ route('children.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Date of Birth -->
                        <div class="mt-4">
                            <x-input-label for="dob">
                                {{ __('Date of Birth') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob')" required />
                            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                        </div>

                        <!-- Gender -->
                        <div class="mt-4">
                            <x-input-label for="gender">
                                {{ __('Gender') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="gender" class="block mt-1 w-full" type="text" name="gender" :value="old('gender')" required />
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <!-- Blood Type -->
                        <div class="mt-4">
                            <x-input-label for="blood_type">
                                {{ __('Blood Type') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="blood_type" class="block mt-1 w-full" type="text" name="blood_type" :value="old('blood_type')" required />
                            <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
                        </div>

                        <!-- Allergies -->
                        <div class="mt-4">
                            <x-input-label for="allergies">
                                {{ __('Allergies') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-area id="allergies" class="block mt-1 w-full" name="allergies" required>{{ old('allergies') }}</x-text-area>
                            <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
                        </div>

                        <!-- Primary Residence -->
                        <div class="mt-4">
                            <x-input-label for="primary_residence" :value="__('Primary Residence')" />
                            <x-text-input id="primary_residence" class="block mt-1 w-full" type="text" name="primary_residence" :value="old('primary_residence')" />
                            <x-input-error :messages="$errors->get('primary_residence')" class="mt-2" />
                        </div>

                        <!-- School Name -->
                        <div class="mt-4">
                            <x-input-label for="school_name">
                                {{ __('School Name') }} <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="old('school_name')" required />
                            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                        </div>

                        <!-- School Grade -->
                        <div class="mt-4">
                            <x-input-label for="school_grade" :value="__('School Grade')" />
                            <x-text-input id="school_grade" class="block mt-1 w-full" type="text" name="school_grade" :value="old('school_grade')" />
                            <x-input-error :messages="$errors->get('school_grade')" class="mt-2" />
                        </div>

                        <!-- Profile Photo -->
                        <div class="mt-4">
                            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
                            <input id="profile_photo" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="profile_photo" />
                            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                        </div>

                        <!-- Extracurricular Activities -->
                        <div class="mt-4">
                            <x-input-label for="extracurricular_activities" :value="__('Extracurricular Activities')" />
                            <x-text-area id="extracurricular_activities" class="block mt-1 w-full" name="extracurricular_activities">{{ old('extracurricular_activities') }}</x-text-area>
                            <x-input-error :messages="$errors->get('extracurricular_activities')" class="mt-2" />
                        </div>

                        <!-- Doctor Info -->
                        <div class="mt-4">
                            <x-input-label for="doctor_info" :value="__('Doctor Info')" />
                            <x-text-area id="doctor_info" class="block mt-1 w-full" name="doctor_info">{{ old('doctor_info') }}</x-text-area>
                            <x-input-error :messages="$errors->get('doctor_info')" class="mt-2" />
                        </div>

                        <!-- Emergency Contact Info -->
                        <div class="mt-4">
                            <x-input-label for="emergency_contact_info" :value="__('Emergency Contact Info')" />
                            <x-text-area id="emergency_contact_info" class="block mt-1 w-full" name="emergency_contact_info">{{ old('emergency_contact_info') }}</x-text-area>
                            <x-input-error :messages="$errors->get('emergency_contact_info')" class="mt-2" />
                        </div>

                        <!-- Special Needs -->
                        <div class="mt-4">
                            <x-input-label for="special_needs" :value="__('Special Needs')" />
                            <x-text-area id="special_needs" class="block mt-1 w-full" name="special_needs">{{ old('special_needs') }}</x-text-area>
                            <x-input-error :messages="$errors->get('special_needs')" class="mt-2" />
                        </div>

                        <!-- Other Info -->
                        <div class="mt-4">
                            <x-input-label for="other_info" :value="__('Other Info')" />
                            <x-text-area id="other_info" class="block mt-1 w-full" name="other_info">{{ old('other_info') }}</x-text-area>
                            <x-input-error :messages="$errors->get('other_info')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Add Child') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>