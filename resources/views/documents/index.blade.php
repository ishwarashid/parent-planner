<style>
    /* Theme color variables */
    :root {
        --theme-header: #000033;
        /* Midnight Blue */
        --theme-body-text: #1D2951;
        /* Muted Navy */
        --theme-button-bg: #40E0D0;
        /* Turquoise */
        --theme-button-text: #000033;
        --theme-button-bg-hover: #48D1CC;
        /* Medium Turquoise */

        --theme-alert-bg: #AFEEEE;
        /* Pale Turquoise */
        --theme-alert-border: #00CED1;
        /* Dark Turquoise */
        --theme-alert-text: #2F4F4F;
        /* Dark Slate Gray */

        /* Action Link Colors */
        --theme-action-view: #008080;
        /* Teal */
        --theme-action-edit: #3F51B5;
        /* Indigo */
        --theme-action-delete: #FF6F61;
        /* Coral */
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

    .theme-button {
        background-color: var(--theme-button-bg);
        color: var(--theme-button-text);
        font-weight: 700;
        transition: background-color 0.2s;
    }

    .theme-button:hover {
        background-color: var(--theme-button-bg-hover);
    }

    /* Success Alert */
    .theme-alert-success {
        background-color: var(--theme-alert-bg);
        border-color: var(--theme-alert-border);
        color: var(--theme-alert-text);
    }

    /* Table Styling */
    .theme-table-header {
        background-color: #f9fafb;
    }

    .theme-table-header-text {
        color: var(--theme-body-text);
    }

    .theme-table-row-text {
        color: var(--theme-body-text);
    }

    tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Table Action Links */
    .theme-action-link {
        font-weight: 500;
        transition: all 0.2s;
    }

    .theme-action-link:hover {
        text-decoration: underline;
        opacity: 0.8;
    }

    .theme-action-view {
        color: var(--theme-action-view);
    }

    .theme-action-edit {
        color: var(--theme-action-edit);
    }

    .theme-action-delete {
        color: var(--theme-action-delete);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Documents') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('documents.create') }}" class="py-2 px-4 rounded theme-button">
                            Upload New Document
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="border px-4 py-3 rounded relative mb-4 theme-alert-success" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="theme-table-header">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Child
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Uploaded By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Notes
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        File
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($documents as $document)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium theme-table-row-text">{{ $document->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $document->child->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $document->category }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $document->uploadedBy->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $document->notes ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @can('view', $document)
                                                <a href="{{ asset('storage/' . $document->file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View File</a>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endcan
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('view', $document)
                                                <a href="{{ route('documents.show', $document) }}" class="mr-3 theme-action-link theme-action-view">View</a>
                                            @endcan
                                            @can('update', $document)
                                                <a href="{{ route('documents.edit', $document) }}" class="mr-3 theme-action-link theme-action-edit">Edit</a>
                                            @endcan
                                            @can('delete', $document)
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="theme-action-link theme-action-delete" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            No documents found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>