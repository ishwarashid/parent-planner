<style>
    /* Theme color variables from your new palette */
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
            {{ __('Children') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        @can('create', App\Models\Child::class)
                            <a href="{{ route('children.create') }}" class="py-2 px-4 rounded theme-button">
                                Add New Child
                            </a>
                        @endcan
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
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Photo</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Name</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Date of Birth</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Color</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Allergies</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        School Info</th>
                                     <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($children as $child)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($child->profile_photo_path)
                                                <img src="{{ asset('storage/' . $child->profile_photo_path) }}"
                                                    alt="{{ $child->name }}"
                                                    class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium theme-table-row-text">{{ $child->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                {{ \Carbon\Carbon::parse($child->dob)->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($child->color)
                                                <div class="h-6 w-6 rounded-full border"
                                                    style="background-color: {{ $child->color }}"></div>
                                            @else
                                                <div class="h-6 w-6 rounded-full border bg-gray-200"></div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $child->allergies ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $child->school_name ?? 'N/A' }}
                                                ({{ $child->school_grade ?? 'N/A' }})</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('view', $child)
                                                <a href="{{ route('children.show', $child) }}"
                                                    class="mr-3 theme-action-link theme-action-view">View</a>
                                            @endcan
                                            @can('update', $child)
                                                <a href="{{ route('children.edit', $child) }}"
                                                    class="mr-3 theme-action-link theme-action-edit">Edit</a>
                                            @endcan
                                            @can('delete', $child)
                                                <form action="{{ route('children.destroy', $child) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="theme-action-link theme-action-delete"
                                                        onclick="return confirm('Are you sure you want to delete this child\'s record?')">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            No children found.
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
