<style>
    /* Theme color variables from your new palette */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-title: #1A237E;
        /* Dark Indigo */
        --theme-subtitle: #2F4F4F;
        /* Dark Slate Gray */
        --theme-dt-text: #000033;
        --theme-dd-text: #1D2951;
        --theme-divider: #AFEEEE;
        /* Pale Turquoise */

        /* Button Colors */
        --theme-button-primary-bg: #FFB300;
        /* Amber */
        --theme-button-primary-text: #000033;
        --theme-button-primary-bg-hover: #FFDAB9;
        /* Peach Puff */

        --theme-button-secondary-bg: #2F4F4F;
        /* Dark Slate Gray */
        --theme-button-secondary-text: #FFFFFF;
        --theme-button-secondary-bg-hover: #008080;
        /* Teal */
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    .theme-title-text {
        color: var(--theme-title);
    }

    .theme-subtitle-text {
        color: var(--theme-subtitle);
    }

    /* Definition List Styling */
    .theme-dl-divider {
        border-color: var(--theme-divider) !important;
    }

    .theme-dt-text {
        color: var(--theme-dt-text);
        font-weight: 500;
    }

    .theme-dd-text {
        color: var(--theme-dd-text);
    }

    /* Button Styling */
    .theme-button {
        font-weight: 700;
        transition: background-color 0.2s;
        border: none;
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
            {{ __('Child Details') }}: {{ $child->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-6">
                        @if ($child->profile_photo_path)
                            <img src="{{ asset('storage/' . $child->profile_photo_path) }}" alt="{{ $child->name }}"
                                class="h-24 w-24 rounded-full object-cover mr-6">
                        @else
                            <div
                                class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-6">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold theme-title-text">{{ $child->name }}</h3>
                            <p class="theme-subtitle-text">Born:
                                {{ \Carbon\Carbon::parse($child->dob)->format('M d, Y') }}
                                ({{ \Carbon\Carbon::parse($child->dob)->age }} years old)</p>
                        </div>
                    </div>

                    <div class="border-t theme-dl-divider pt-4">
                        <dl class="divide-y theme-dl-divider">
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Gender</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->gender ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Blood Type</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->blood_type ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Allergies</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->allergies ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Primary Residence</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->primary_residence ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">School Name</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->school_name ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">School Grade</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->school_grade ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Extracurricular Activities</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->extracurricular_activities ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Doctor Info</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->doctor_info ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Emergency Contact Info</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->emergency_contact_info ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Special Needs</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->special_needs ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Other Info</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->other_info ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Added By</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ $child->user->name }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Created At</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ \Carbon\Carbon::parse($child->created_at)->format('M d, Y H:i') }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Last Updated</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    {{ \Carbon\Carbon::parse($child->updated_at)->format('M d, Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        @can('update', $child)
                            <a href="{{ route('children.edit', $child) }}"
                                class="py-2 px-4 rounded theme-button theme-button-primary">
                                Edit Child
                            </a>
                        @endcan
                        <a href="{{ route('children.index') }}"
                            class="py-2 px-4 rounded theme-button theme-button-secondary">
                            Back to Children List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
