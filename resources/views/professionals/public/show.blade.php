<x-app-layout>
    <style>
        /* Theme color variables from your new palette */
        :root {
            --theme-header: #000033;
            --theme-title: #1A237E;
            --theme-body-text: #1D2951;
            --theme-subtitle: #2F4F4F;
            --theme-divider: #AFEEEE;
            --theme-link-color: #008080;
            --theme-link-hover-color: #2F4F4F;
            --theme-card-bg: #FFFFFF;
            --theme-icon-color: #6B7280;
            /* gray-500 */
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

        .theme-body-text {
            color: var(--theme-body-text);
        }

        .theme-divider {
            border-color: var(--theme-divider);
        }

        .theme-back-link {
            color: var(--theme-subtitle);
            transition: color 0.2s;
            font-weight: 500;
        }

        .theme-back-link:hover {
            color: var(--theme-header);
        }

        /* Profile Card */
        .theme-card-bg {
            background-color: var(--theme-card-bg);
        }

        .theme-icon {
            color: var(--theme-icon-color);
        }

        .theme-contact-link {
            color: var(--theme-link-color);
            transition: color 0.2s;
        }

        .theme-contact-link:hover {
            color: var(--theme-link-hover-color);
            text-decoration: underline;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ $professional->business_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('professionals.public.index') }}" class="text-sm theme-back-link">
                    &larr; Back to all professionals
                </a>
            </div>

            <div class="overflow-hidden shadow-sm sm:rounded-lg theme-card-bg">
                <div class="p-6 md:p-8">
                    {{-- Top Section: Name and Location --}}
                    <h1 class="text-3xl font-bold theme-title-text">{{ $professional->business_name }}</h1>
                    @php
                        $location = implode(
                            ', ',
                            array_filter([$professional->city, $professional->country, $professional->continent]),
                        );
                    @endphp
                    <p class="text-md mt-2 flex items-center theme-subtitle-text">
                        <svg class="w-5 h-5 mr-2 theme-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $location ?: 'Location not provided' }}
                    </p>

                    <hr class="my-6 theme-divider">

                    {{-- Main Content Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Left Column: Services --}}
                        <div class="md:col-span-2">
                            <h2 class="text-xl font-semibold mb-3 theme-title-text">Services Offered</h2>
                            @if ($professional->services && count($professional->services) > 0)
                                <ul class="list-disc list-inside space-y-2 theme-body-text">
                                    @foreach ($professional->services as $service)
                                        <li>{{ $service }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500">No services have been listed.</p>
                            @endif
                        </div>

                        {{-- Right Column: Contact & Links --}}
                        <div class="md:col-span-1">
                            <h2 class="text-xl font-semibold mb-3 theme-title-text">Contact & Links</h2>
                            <div class="space-y-4">
                                {{-- Phone Number --}}
                                <div class="flex items-center theme-body-text">
                                    <svg class="w-5 h-5 mr-3 theme-icon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <span>{{ $professional->phone_number }}</span>
                                </div>

                                {{-- Website --}}
                                @if ($professional->website)
                                    <a href="{{ $professional->website }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center theme-contact-link">
                                        <svg class="w-5 h-5 mr-3 theme-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                        Visit Website
                                    </a>
                                @endif

                                {{-- Social Links with Specific Icons --}}
                                @if ($professional->facebook)
                                    <a href="{{ $professional->facebook }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center theme-contact-link">
                                        <svg class="w-5 h-5 mr-3 theme-icon" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v2.385z" />
                                        </svg>
                                        Facebook Profile
                                    </a>
                                @endif
                                @if ($professional->linkedin)
                                    <a href="{{ $professional->linkedin }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center theme-contact-link">
                                        <svg class="w-5 h-5 mr-3 theme-icon" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.59-11.018-3.714v-2.155z" />
                                        </svg>
                                        LinkedIn Profile
                                    </a>
                                @endif
                                @if ($professional->twitter)
                                    <a href="{{ $professional->twitter }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center theme-contact-link">
                                        <svg class="w-5 h-5 mr-3 theme-icon" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.616 1.911 2.396 3.223 4.491 3.269-1.79 1.406-4.062 2.19-6.522 1.836 2.087 1.343 4.572 2.122 7.23 2.122 7.863 0 12.42-6.22 11.998-12.318.823-.593 1.533-1.332 2.088-2.178z" />
                                        </svg>
                                        Twitter Profile
                                    </a>
                                @endif
                                @if ($professional->instagram)
                                    <a href="{{ $professional->instagram }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center theme-contact-link">
                                        <svg class="w-5 h-5 mr-3 theme-icon" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.012 3.584-.07 4.85c-.148 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.85s.012-3.584.07-4.85c.149-3.225 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.947s-.014-3.667-.072-4.947c-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4s1.791-4 4-4 4 1.79 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                        </svg>
                                        Instagram Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
