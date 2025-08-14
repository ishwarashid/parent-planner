<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-title: #1A237E;
        /* Indigo */
        --theme-body-text: #1D2951;
        /* Muted Navy */
        --theme-subtitle: #2F4F4F;
        /* Dark Slate Gray */
        --theme-dt-text: #000033;
        /* Dark Navy */
        --theme-dd-text: #1D2951;
        /* Muted Navy */
        --theme-divider: #AFEEEE;
        /* Pale Turquoise */
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

    .theme-dl-divider {
        border-color: var(--theme-divider) !important;
    }

    .theme-dt-text {
        color: var(--theme-dt-text);
    }

    .theme-dd-text {
        color: var(--theme-dd-text);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Document Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold theme-title-text">{{ $document->name }}</h3>
                        <p class="theme-subtitle-text">For: {{ $document->child->name }}</p>
                    </div>

                    <div class="border-t theme-dl-divider pt-4">
                        <dl class="divide-y theme-dl-divider">
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Category</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">{{ $document->category }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Uploaded By</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">{{ $document->uploadedBy->name }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Notes</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">{{ $document->notes ?? 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">File</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">
                                    @can('view', $document)
                                        <a href="{{ asset('storage/' . $document->file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View File</a>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endcan
                                </dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Uploaded At</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">{{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y H:i A') }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 theme-dt-text">Last Updated</dt>
                                <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0 theme-dd-text">{{ \Carbon\Carbon::parse($document->updated_at)->format('M d, Y H:i A') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-end items-center space-x-2">
                        @can('update', $document)
                            <a href="{{ route('documents.edit', $document) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Document
                            </a>
                        @endcan
                        @can('delete', $document)
                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Are you sure you want to delete this document?')">
                                    Delete
                                </button>
                            </form>
                        @endcan
                        <a href="{{ route('documents.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Documents List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
