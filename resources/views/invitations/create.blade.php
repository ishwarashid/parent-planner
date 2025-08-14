<x-app-layout>
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

        /* Form Inputs & Labels */
        .theme-input-label {
            color: var(--theme-label);
            font-weight: 600;
        }

        .theme-input,
        .theme-select {
            border-color: var(--theme-input-border) !important;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .theme-input:focus,
        .theme-select:focus {
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

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Send Invitation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Set a max-width for the form for better readability --}}
                    <div class="max-w-2xl mx-auto">
                        <form method="POST" action="{{ route('invitations.store') }}">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" class="theme-input-label" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full theme-input" type="email"
                                    name="email" :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 theme-error" />
                            </div>

                            <!-- Role -->
                            <div class="mt-4">
                                <x-input-label for="role" class="theme-input-label" :value="__('Assign a Role')" />
                                <select id="role" name="role"
                                    class="block mt-1 w-full rounded-md shadow-sm theme-select">
                                    <option value="co-parent">Co-parent</option>
                                    <option value="nanny">Nanny</option>
                                    <option value="grandparent">Grandparent</option>
                                    <option value="guardian">Guardian</option>
                                    <option value="other">Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2 theme-error" />
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-4">
                                <a href="{{ route('invitations.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 theme-button theme-button-secondary">
                                    Cancel
                                </a>
                                <x-primary-button class="theme-button theme-button-primary">
                                    {{ __('Send Invitation') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
