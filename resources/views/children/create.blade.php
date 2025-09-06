<style>
    /* Theme color variables from your new palette */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-label: #1D2951;
        /* Muted Navy */
        --theme-input-border: #AFEEEE;
        /* Pale Turquoise */
        --theme-input-focus-border: #40E0D0;
        /* Turquoise */
        --theme-required-star: #FF6F61;
        /* Coral */

        /* Buttons */
        --theme-button-primary-bg: #FF5722;
        /* Deep Orange */
        --theme-button-primary-text: #FFFFFF;
        --theme-button-primary-bg-hover: #FF6F61;
        /* Coral on hover */

        --theme-button-secondary-bg: #2F4F4F;
        /* Dark Slate Gray */
        --theme-button-secondary-text: #FFFFFF;
        --theme-button-secondary-bg-hover: #008080;
        /* Teal on hover */

        --theme-error-text: #FF6F61;
        /* Coral */
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    .theme-info-text {
        color: var(--theme-label);
    }

    .theme-required-star {
        color: var(--theme-required-star);
    }

    /* Form Inputs & Labels */
    .theme-input-label {
        color: var(--theme-label);
        font-weight: 600;
    }

    .theme-input,
    .theme-textarea {
        border-color: var(--theme-input-border) !important;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .theme-input:focus,
    .theme-textarea:focus {
        border-color: var(--theme-input-focus-border) !important;
        box-shadow: 0 0 0 1px var(--theme-input-focus-border) !important;
        outline: none;
    }

    /* Error Messages */
    .theme-error {
        color: var(--theme-error-text);
    }

    /* Buttons */
    .theme-button {
        font-weight: 700;
        transition: background-color 0.2s;
        border: none;
        text-decoration: none;
        /* For the cancel <a> tag */
    }

    .theme-button-primary {
        background-color: var(--theme-button-primary-bg);
        color: var(--theme-button-primary-text);
    }

    .theme-button-primary:hover {
        background-color: var(--theme-button-primary-bg-hover);
    }

    .theme-button-secondary {
        background-color: var(--theme-button-secondary-bg);
        color: var(--theme-button-secondary-text);
    }

    .theme-button-secondary:hover {
        background-color: var(--theme-button-secondary-bg-hover);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Add New Child') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-sm mb-4 theme-info-text">Fields marked with <span class="theme-required-star">*</span>
                        are required.</p>

                    <!-- Private File Notification -->
                    @if(config('filesystems.disks.do.visibility') === 'private')
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Private File Storage</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Uploaded profile photos are stored privately for security. Links to these files will expire after 5 minutes for security reasons. If you need to view the photo again, please refresh the page.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('children.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" class="theme-input-label">
                                    {{ __('Name') }} <span class="theme-required-star">*</span>
                                </x-input-label>
                                <x-text-input id="name" class="block mt-1 w-full theme-input" type="text"
                                    name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 theme-error" />
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <x-input-label for="dob" class="theme-input-label">
                                    {{ __('Date of Birth') }} <span class="theme-required-star">*</span>
                                </x-input-label>
                                <x-text-input id="dob" class="block mt-1 w-full theme-input" type="date"
                                    name="dob" :value="old('dob')" required />
                                <x-input-error :messages="$errors->get('dob')" class="mt-2 theme-error" />
                            </div>

                            <!-- Gender -->
                            <div>
                                <x-input-label for="gender" class="theme-input-label">
                                    {{ __('Gender') }} <span class="theme-required-star">*</span>
                                </x-input-label>
                                <x-text-input id="gender" class="block mt-1 w-full theme-input" type="text"
                                    name="gender" :value="old('gender')" required />
                                <x-input-error :messages="$errors->get('gender')" class="mt-2 theme-error" />
                            </div>

                            <!-- Blood Type -->
                            <div>
                                <x-input-label for="blood_type" class="theme-input-label">
                                    {{ __('Blood Type') }} <span class="theme-required-star">*</span>
                                </x-input-label>
                                <x-text-input id="blood_type" class="block mt-1 w-full theme-input" type="text"
                                    name="blood_type" :value="old('blood_type')" required />
                                <x-input-error :messages="$errors->get('blood_type')" class="mt-2 theme-error" />
                            </div>

                            <!-- School Name -->
                            <div>
                                <x-input-label for="school_name" class="theme-input-label">
                                    {{ __('School Name') }} <span class="theme-required-star">*</span>
                                </x-input-label>
                                <x-text-input id="school_name" class="block mt-1 w-full theme-input" type="text"
                                    name="school_name" :value="old('school_name')" required />
                                <x-input-error :messages="$errors->get('school_name')" class="mt-2 theme-error" />
                            </div>

                            <!-- School Grade -->
                            <div>
                                <x-input-label for="school_grade" class="theme-input-label" :value="__('School Grade')" />
                                <x-text-input id="school_grade" class="block mt-1 w-full theme-input" type="text"
                                    name="school_grade" :value="old('school_grade')" />
                                <x-input-error :messages="$errors->get('school_grade')" class="mt-2 theme-error" />
                            </div>
                        </div>

                        <!-- Allergies -->
                        <div class="mt-4">
                            <x-input-label for="allergies" class="theme-input-label">
                                {{ __('Allergies') }} <span class="theme-required-star">*</span>
                            </x-input-label>
                            <x-text-area id="allergies" class="block mt-1 w-full theme-textarea" name="allergies"
                                required>{{ old('allergies') }}</x-text-area>
                            <x-input-error :messages="$errors->get('allergies')" class="mt-2 theme-error" />
                        </div>

                        <!-- Primary Residence -->
                        <div class="mt-4">
                            <x-input-label for="primary_residence" class="theme-input-label" :value="__('Primary Residence')" />
                            <x-text-area id="primary_residence" class="block mt-1 w-full theme-textarea"
                                name="primary_residence">{{ old('primary_residence') }}</x-text-area>
                            <x-input-error :messages="$errors->get('primary_residence')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Color -->
                            <div>
                                <x-input-label for="color" class="theme-input-label" :value="__('Associate a Color')" />
                                <input id="color" class="block mt-1 w-20 h-10 p-1 rounded-lg border theme-input"
                                    type="color" name="color" value="{{ old('color', '#40E0D0') }}" />
                                <x-input-error :messages="$errors->get('color')" class="mt-2 theme-error" />
                            </div>

                            <!-- Profile Photo -->
                            <div>
                                <x-input-label for="profile_photo" class="theme-input-label" :value="__('Profile Photo')" />
                                <div class="mt-2 flex items-center space-x-6">
                                    <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label for="profile_photo"
                                            class="cursor-pointer py-2 px-4 rounded-md theme-button theme-button-secondary">Upload
                                            Photo</label>
                                        <span id="file-name-display" class="ml-4 text-sm theme-info-text italic">No file
                                            chosen</span>
                                    </div>
                                </div>
                                <input id="profile_photo" type="file" name="profile_photo" class="hidden">
                                <x-input-error :messages="$errors->get('profile_photo')" class="mt-2 theme-error" />
                            </div>
                        </div>

                        <!-- Other Text Areas -->
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="extracurricular_activities" class="theme-input-label"
                                    :value="__('Extracurricular Activities')" />
                                <x-text-area id="extracurricular_activities" class="block mt-1 w-full theme-textarea"
                                    name="extracurricular_activities">{{ old('extracurricular_activities') }}</x-text-area>
                                <x-input-error :messages="$errors->get('extracurricular_activities')" class="mt-2 theme-error" />
                            </div>
                            <div>
                                <x-input-label for="doctor_info" class="theme-input-label" :value="__('Doctor Info')" />
                                <x-text-area id="doctor_info" class="block mt-1 w-full theme-textarea"
                                    name="doctor_info">{{ old('doctor_info') }}</x-text-area>
                                <x-input-error :messages="$errors->get('doctor_info')" class="mt-2 theme-error" />
                            </div>
                            <div>
                                <x-input-label for="emergency_contact_info" class="theme-input-label"
                                    :value="__('Emergency Contact Info')" />
                                <x-text-area id="emergency_contact_info" class="block mt-1 w-full theme-textarea"
                                    name="emergency_contact_info">{{ old('emergency_contact_info') }}</x-text-area>
                                <x-input-error :messages="$errors->get('emergency_contact_info')" class="mt-2 theme-error" />
                            </div>
                            <div>
                                <x-input-label for="special_needs" class="theme-input-label" :value="__('Special Needs')" />
                                <x-text-area id="special_needs" class="block mt-1 w-full theme-textarea"
                                    name="special_needs">{{ old('special_needs') }}</x-text-area>
                                <x-input-error :messages="$errors->get('special_needs')" class="mt-2 theme-error" />
                            </div>
                            <div>
                                <x-input-label for="other_info" class="theme-input-label" :value="__('Other Info')" />
                                <x-text-area id="other_info" class="block mt-1 w-full theme-textarea"
                                    name="other_info">{{ old('other_info') }}</x-text-area>
                                <x-input-error :messages="$errors->get('other_info')" class="mt-2 theme-error" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('children.index') }}"
                                class="inline-block py-2 px-4 rounded-md text-sm theme-button theme-button-secondary">
                                Cancel
                            </a>
                            <x-primary-button class="theme-button theme-button-primary">
                                {{ __('Add Child') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const fileInput = document.getElementById('profile_photo');
            const fileNameDisplay = document.getElementById('file-name-display');

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = fileInput.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'No file chosen';
                }
            });
        </script>
    @endpush
</x-app-layout>