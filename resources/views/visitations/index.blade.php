<style>
    /* Theme color variables from your new palette */
    :root {
        --theme-header: #000033;
        --theme-body-text: #1D2951;
        --theme-button-primary-bg: #40E0D0;
        --theme-button-primary-text: #000033;
        --theme-button-primary-bg-hover: #48D1CC;

        --theme-button-secondary-bg: #2F4F4F;
        --theme-button-secondary-text: #FFFFFF;
        --theme-button-secondary-bg-hover: #008080;

        --theme-alert-bg: #AFEEEE;
        --theme-alert-border: #00CED1;
        --theme-alert-text: #2F4F4F;

        --theme-input-border: #AFEEEE;
        --theme-input-focus-border: #40E0D0;

        /* Action Link Colors */
        --theme-action-view: #008080;
        /* Teal */
        --theme-action-edit: #3F51B5;
        /* Indigo */
        --theme-action-delete: #FF6F61;
        /* Coral */

        /* Status Badge Colors */
        --theme-status-scheduled-bg: #AFEEEE;
        /* Pale Turquoise */
        --theme-status-scheduled-text: #008080;
        /* Teal */
        --theme-status-completed-bg: #1A237E;
        /* Dark Indigo */
        --theme-status-completed-text: #FFFFFF;
        --theme-status-cancelled-bg: #6c757d;
        /* Standard Gray */
        --theme-status-cancelled-text: #FFFFFF;
        --theme-status-missed-bg: #dc3545;
        /* Red */
        --theme-status-missed-text: #FFFFFF;
        --theme-status-rescheduled-bg: #ffc107;
        /* Yellow */
        --theme-status-rescheduled-text: #212529;
        --theme-status-other-bg: #6f42c1;
        /* Purple */
        --theme-status-other-text: #FFFFFF;
    }

    /* General Styling */
    .theme-header-text {
        color: var(--theme-header);
    }

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

    /* Alert */
    .theme-alert-success {
        background-color: var(--theme-alert-bg);
        border-color: var(--theme-alert-border);
        color: var(--theme-alert-text);
    }

    /* Table */
    .theme-table-header {
        background-color: #f9fafb;
    }

    .theme-table-header-text {
        color: var(--theme-body-text);
    }

    .theme-table-row-text {
        color: var(--theme-body-text);
    }

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

    /* Status Badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
    }

    .status-scheduled {
        background-color: var(--theme-status-scheduled-bg);
        color: var(--theme-status-scheduled-text);
    }

    .status-completed {
        background-color: var(--theme-status-completed-bg);
        color: var(--theme-status-completed-text);
    }

    .status-cancelled {
        background-color: var(--theme-status-cancelled-bg);
        color: var(--theme-status-cancelled-text);
    }
    
    .status-missed {
        background-color: var(--theme-status-missed-bg);
        color: var(--theme-status-missed-text);
    }
    
    .status-rescheduled {
        background-color: var(--theme-status-rescheduled-bg);
        color: var(--theme-status-rescheduled-text);
    }
    
    .status-other {
        background-color: var(--theme-status-other-bg);
        color: var(--theme-status-other-text);
    }

    /* Modal Form Inputs */
    .theme-modal-label {
        color: var(--theme-label);
        font-weight: 600;
    }

    .theme-modal-input {
        border-color: var(--theme-input-border) !important;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .theme-modal-input:focus {
        border-color: var(--theme-input-focus-border) !important;
        box-shadow: 0 0 0 1px var(--theme-input-focus-border) !important;
        outline: none;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Visitation Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium theme-header-text">Upcoming Visitations</h3>
                        @can('create', App\Models\Visitation::class)
                            <button x-data="{}" @click="$dispatch('open-modal', 'visitation-form')"
                                class="py-2 px-4 rounded theme-button theme-button-primary">
                                Add New Visitation
                            </button>
                        @endcan
                    </div>

                    {{-- MODAL FORM --}}
                    <x-modal name="visitation-form" title="Visitation Details">
                        <form id="visitationForm" method="POST" action="{{ route('visitations.store') }}"
                            class="space-y-2">
                            @csrf

                            <div>
                                <label for="parent_id" class="block text-sm theme-modal-label">Assign To</label>
                                <select id="parent_id" name="parent_id"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                                    <option value="">Select a Family Member</option>
                                    @foreach ($familyMembers as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="child_id" class="block text-sm theme-modal-label">Child</label>
                                <select id="child_id" name="child_id"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                                    @foreach ($children as $child)
                                        <option value="{{ $child->id }}">{{ $child->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_start" class="block text-sm theme-modal-label">Start Time</label>
                                <input type="datetime-local" id="date_start" name="date_start"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                            </div>

                            <div>
                                <label for="date_end" class="block text-sm theme-modal-label">End Time</label>
                                <input type="datetime-local" id="date_end" name="date_end"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                            </div>

                            <div>
                                <label for="status" class="block text-sm theme-modal-label">Status</label>
                                <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
                                    <option value="Scheduled" selected>Scheduled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Missed">Missed</option>
                                    <option value="Rescheduled">Rescheduled</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Custom Status Description (shown only when "Other" is selected) -->
                            <div id="custom-status-description-container" class="mt-4" style="display: none;">
                                <label for="custom_status_description" class="block text-sm theme-modal-label">Custom Status Description</label>
                                <input type="text" id="custom_status_description" name="custom_status_description" 
                                    class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" 
                                    maxlength="255">
                                <p class="mt-1 text-sm text-gray-500">Please provide a description for the 'Other' status.</p>
                            </div>

                            <div class="flex items-center">
                                <input id="is_recurring" name="is_recurring" type="checkbox" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="is_recurring" class="ml-2 block text-sm theme-modal-label">Is
                                    Recurring?</label>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm theme-modal-label">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"></textarea>
                            </div>

                            <div class="flex items-center justify-end mt-6 space-x-4">
                                <button type="button" x-on:click="$dispatch('close-modal')"
                                    class="py-2 px-4 rounded theme-button theme-button-secondary">
                                    Cancel
                                </button>
                                <button type="submit" class="py-2 px-4 rounded theme-button theme-button-primary">
                                    Save Visitation
                                </button>
                            </div>
                        </form>
                    </x-modal>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const statusSelect = document.getElementById('status');
                            const customDescriptionContainer = document.getElementById('custom-status-description-container');
                            const customDescriptionField = document.getElementById('custom_status_description');
                            
                            function toggleCustomDescriptionVisibility() {
                                if (statusSelect.value === 'Other') {
                                    customDescriptionContainer.style.display = 'block';
                                } else {
                                    customDescriptionContainer.style.display = 'none';
                                    customDescriptionField.value = '';
                                }
                            }
                            
                            // Show/hide custom description based on initial value
                            toggleCustomDescriptionVisibility();
                            
                            // Add event listener to status select
                            statusSelect.addEventListener('change', toggleCustomDescriptionVisibility);
                        });
                    </script>

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
                                        Child</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Assigned To</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Start Time</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        End Time</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                        Created By</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($visitations as $visitation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium theme-table-row-text">
                                                {{ $visitation->child->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">{{ $visitation->parent->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                {{ \Carbon\Carbon::parse($visitation->date_start)->format('M d, Y H:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm theme-table-row-text">
                                                {{ \Carbon\Carbon::parse($visitation->date_end)->format('M d, Y H:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="status-badge 
                                                @if ($visitation->status == 'Scheduled') status-scheduled
                                                @elseif($visitation->status == 'Completed') status-completed
                                                @elseif($visitation->status == 'Cancelled') status-cancelled
                                                @elseif($visitation->status == 'Missed') status-missed
                                                @elseif($visitation->status == 'Rescheduled') status-rescheduled
                                                @elseif($visitation->status == 'Other') status-other @endif">
                                                {{ $visitation->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $visitation->creator->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('view', $visitation)
                                                <a href="{{ route('visitations.show', $visitation) }}"
                                                    class="mr-3 theme-action-link theme-action-view">View</a>
                                            @endcan
                                            @can('update', $visitation)
                                                <a href="{{ route('visitations.edit', $visitation) }}"
                                                    class="mr-3 theme-action-link theme-action-edit">Edit</a>
                                            @endcan
                                            @can('delete', $visitation)
                                                <form action="{{ route('visitations.destroy', $visitation) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="theme-action-link theme-action-delete"
                                                        onclick="return confirm('Are you sure you want to delete this visitation?')">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            No visitations found.
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
