<x-app-layout>
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

            --theme-button-danger-bg: #FF6F61;
            /* Coral */
            --theme-button-danger-text: #FFFFFF;
            --theme-button-danger-bg-hover: #FF5722;
            /* Deep Orange */

            --theme-button-disabled-bg: #D1D5DB;
            /* gray-300 */
            --theme-button-disabled-text: #6B7280;
            /* gray-500 */

            --theme-alert-bg: #AFEEEE;
            --theme-alert-border: #00CED1;
            --theme-alert-text: #2F4F4F;
            --theme-warning-text: #2F4F4F;
            /* Dark Slate Gray for plan limits */

            /* Action Link Colors */
            --theme-action-resend: #3F51B5;
            /* Indigo */
            --theme-action-details: #008080;
            /* Teal */
            --theme-action-delete: #FF6F61;
            /* Coral */

            /* Status Badge Colors */
            --theme-status-accepted-bg: #1A237E;
            /* Dark Indigo */
            --theme-status-accepted-text: #FFFFFF;
            --theme-status-rejected-bg: #FFDAB9;
            /* Peach Puff */
            --theme-status-rejected-text: #FF5722;
            /* Deep Orange */
            --theme-status-pending-bg: #AFEEEE;
            /* Pale Turquoise */
            --theme-status-pending-text: #008080;
            /* Teal */
        }

        /* General Styles */
        .theme-header-text {
            color: var(--theme-header);
        }

        .theme-button {
            font-weight: 700;
            transition: background-color 0.2s;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
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

        .theme-button-danger {
            background-color: var(--theme-button-danger-bg);
            color: var(--theme-button-danger-text);
        }

        .theme-button-danger:hover {
            background-color: var(--theme-button-danger-bg-hover);
        }

        .theme-button-disabled {
            background-color: var(--theme-button-disabled-bg);
            color: var(--theme-button-disabled-text);
            cursor: not-allowed;
        }

        .theme-alert-success {
            background-color: var(--theme-alert-bg);
            border-color: var(--theme-alert-border);
            color: var(--theme-alert-text);
        }

        .theme-warning-text {
            color: var(--theme-warning-text);
            font-style: italic;
        }

        /* Table & Actions */
        .theme-table-header {
            background-color: #f9fafb;
        }

        .theme-table-header-text,
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

        .theme-action-resend {
            color: var(--theme-action-resend);
        }

        .theme-action-details {
            color: var(--theme-action-details);
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

        .status-accepted {
            background-color: var(--theme-status-accepted-bg);
            color: var(--theme-status-accepted-text);
        }

        .status-rejected {
            background-color: var(--theme-status-rejected-bg);
            color: var(--theme-status-rejected-text);
        }

        .status-pending {
            background-color: var(--theme-status-pending-bg);
            color: var(--theme-status-pending-text);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            {{ __('Invitations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold theme-header-text">Sent Invitations</h3>
                        @if (auth()->user()->isBasicPlan())
                            <p class="text-sm mt-1 theme-warning-text">You can invite a maximum of 1 user with the Basic
                                Plan.</p>
                        @endif
                    </div>

                    @can('create', App\Models\Invitation::class)
                        @if (auth()->user()->canInvite())
                            <a href="{{ route('invitations.create') }}" class="theme-button theme-button-primary">
                                Send Invitation
                            </a>
                        @else
                            <button class="theme-button theme-button-disabled" disabled>
                                Invitation Limit Reached
                            </button>
                        @endif
                    @endcan
                </div>

                @if (session('status'))
                    <div class="border px-4 py-3 rounded relative my-4 theme-alert-success" role="alert">
                        <span class="block sm:inline">{{ session('status') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="theme-table-header">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                    Email</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                    Role</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                    Sent At</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider theme-table-header-text">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse (auth()->user()->sentInvitations as $invitation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm theme-table-row-text">
                                        {{ $invitation->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm theme-table-row-text">
                                        {{ $invitation->role }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm theme-table-row-text">
                                        {{ $invitation->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="status-badge 
                                            @if ($invitation->status === 'accepted' || $invitation->status === 'registered') status-accepted
                                            @elseif ($invitation->status === 'rejected') status-rejected
                                            @else status-pending @endif">
                                            {{ $invitation->status === 'registered' ? 'Accepted' : ucfirst($invitation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @can('resend', $invitation)
                                            <form method="POST" action="{{ route('invitations.resend', $invitation) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="theme-action-link theme-action-resend">Resend</button>
                                            </form>
                                        @endcan

                                        @can('delete', $invitation)
                                            <button x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-invitation-deletion-{{ $invitation->id }}')"
                                                class="ml-3 theme-action-link theme-action-delete">
                                                Delete
                                            </button>
                                        @endcan

                                        <a href="{{ route('invitations.details', $invitation) }}"
                                            class="ml-3 theme-action-link theme-action-details">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                                <x-modal name="confirm-invitation-deletion-{{ $invitation->id }}" focusable>
                                    <form method="POST" action="{{ route('invitations.destroy', $invitation) }}"
                                        class="p-6">
                                        @csrf
                                        @method('delete')
                                        <h2 class="text-lg font-medium theme-header-text">{{ __('Are you sure?') }}
                                        </h2>
                                        <p class="mt-1 text-sm theme-body-text">
                                            {{ __('Once this invitation is deleted, it cannot be recovered.') }}</p>
                                        <div class="mt-6 flex justify-end space-x-4">
                                            <button type="button" x-on:click="$dispatch('close-modal')"
                                                class="theme-button theme-button-secondary">
                                                {{ __('Cancel') }}
                                            </button>
                                            <button type="submit" class="theme-button theme-button-danger">
                                                {{ __('Delete Invitation') }}
                                            </button>
                                        </div>
                                    </form>
                                </x-modal>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        You have not sent any invitations yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
