<x-app-layout>
    <style>
        /* Theme color variables from your new palette */
        :root {
            --theme-header: #000033;
            --theme-title: #1A237E;
            --theme-subtitle: #2F4F4F;
            --theme-body-text: #1D2951;
            --theme-divider: #AFEEEE;

            /* Buttons */
            --theme-button-primary-bg: #40E0D0;
            --theme-button-primary-text: #000033;
            --theme-button-primary-bg-hover: #48D1CC;
            --theme-button-secondary-bg: #2F4F4F;
            --theme-button-secondary-text: #FFFFFF;
            --theme-button-secondary-bg-hover: #008080;
            --theme-button-danger-bg: #FF6F61;
            --theme-button-danger-text: #FFFFFF;
            --theme-button-danger-bg-hover: #FF5722;

            /* Alerts & Info Blocks */
            --theme-alert-bg: #AFEEEE;
            --theme-alert-border: #00CED1;
            --theme-alert-text: #2F4F4F;
            --theme-info-block-bg: rgba(175, 238, 238, 0.2);
            --theme-info-block-border: #AFEEEE;
            --theme-warning-block-bg: #FFDAB9;
            --theme-warning-block-border: #FFB300;
            --theme-warning-block-text: #FF5722;

            /* Toggle Switch */
            --toggle-bg-off: #D1D5DB;
            /* gray-300 */
            --toggle-bg-on: var(--theme-button-primary-bg);
            --toggle-dot-bg: #FFFFFF;
        }

        /* General Styling */
        .theme-header-text {
            color: var(--theme-header);
        }

        .theme-title-text {
            color: var(--theme-title);
        }

        .theme-body-text {
            color: var(--theme-body-text);
        }

        .theme-alert-success {
            background-color: var(--theme-alert-bg);
            border-color: var(--theme-alert-border);
            color: var(--theme-alert-text);
        }

        /* Details List */
        .theme-dl-row-odd {
            background-color: #f9fafb;
        }

        .theme-dl-row-even {
            background-color: #ffffff;
        }

        .theme-dt-text {
            color: var(--theme-subtitle);
            font-weight: 500;
        }

        .theme-dd-text {
            color: var(--theme-body-text);
        }

        /* Permissions Section */
        .theme-info-block {
            background-color: var(--theme-info-block-bg);
            border-color: var(--theme-info-block-border);
        }

        .theme-warning-block {
            background-color: var(--theme-warning-block-bg);
            border-color: var(--theme-warning-block-border);
        }

        .theme-warning-text {
            color: var(--theme-warning-block-text);
        }

        /* Toggle Switch */
        .toggle-bg {
            background-color: var(--toggle-bg-off);
        }

        input:checked~.toggle-bg {
            background-color: var(--toggle-bg-on);
        }

        input:checked~.dot {
            transform: translateX(1.5rem);
        }

        /* 24px */

        /* Buttons */
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
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight theme-header-text">
            Manage Invitation & User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 border px-4 py-3 rounded relative theme-alert-success" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4 theme-title-text">Details for {{ $invitation->email }}</h3>

                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 theme-dl-row-odd">
                                <dt class="text-sm font-medium theme-dt-text">Email Address</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 theme-dd-text">{{ $invitation->email }}
                                </dd>
                            </div>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 theme-dl-row-even">
                                <dt class="text-sm font-medium theme-dt-text">Assigned Role</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 theme-dd-text">
                                    {{ Str::title($invitation->role) }}</dd>
                            </div>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 theme-dl-row-odd">
                                <dt class="text-sm font-medium theme-dt-text">Status</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 theme-dd-text">
                                    {{ Str::title($invitation->status) }}</dd>
                            </div>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 theme-dl-row-even">
                                <dt class="text-sm font-medium theme-dt-text">Sent On</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2 theme-dd-text">
                                    {{ $invitation->created_at->format('F d, Y \a\t h:i A') }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if (auth()->user()->isPremium() && $managedUser && ($invitation->status == 'registered'))
                        <div class="border-t-2 border-gray-200 mt-8 pt-6">
                            <h3 class="text-xl font-semibold mb-4 theme-title-text">User Permissions</h3>
                            <form action="{{ route('users.permissions.update', $managedUser) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if ($canPromoteToAdmin)
                                    <div class="p-4 border rounded-lg mb-8 theme-info-block">
                                        <label for="promote_toggle" class="flex items-center cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" id="promote_toggle" name="promote_to_admin"
                                                    class="sr-only" x-model="isAdmin">
                                                <div class="block w-14 h-8 rounded-full toggle-bg"></div>
                                                <div
                                                    class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform">
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <p class="font-semibold text-lg theme-title-text">Promote to Admin
                                                    Co-Parent</p>
                                                <p class="text-sm theme-body-text">This user will have full access to
                                                    all features, identical to you.</p>
                                            </div>
                                        </label>
                                    </div>
                                @endif

                                <div class="flex justify-end mt-8">
                                    <button type="submit" class="theme-button theme-button-primary">Save
                                        Permissions</button>
                                </div>
                            </form>
                        </div>
                    @elseif (auth()->user()->isPremium() && ($invitation->status == 'registered'))
                        <div class="p-4 border rounded-lg mb-8 text-center theme-warning-block">
                            <p class="font-semibold theme-warning-text">You have already assigned the Admin
                                Co-Parent role to another user.</p>
                        </div>
                    @elseif (auth()->user()->isBasicPlan())
                        <div class="p-4 border rounded-lg mb-8 text-center theme-warning-block">
                            <p class="font-semibold theme-warning-text">Please upgrade to Premium Subscription to give
                                full access to all features, to this user.</p>
                        </div>
                    @endif
                    <div class="mt-6 flex justify-end space-x-3 border-t pt-6">
                        @can('resend', $invitation)
                            <form method="POST" action="{{ route('invitations.resend', $invitation) }}">
                                @csrf
                                <button type="submit" class="theme-button theme-button-secondary">Resend
                                    Invitation</button>
                            </form>
                        @endcan

                        @can('delete', $invitation)
                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-invitation-removal')"
                                class="theme-button theme-button-danger">
                                {{ $managedUser ? 'Remove User & Access' : 'Delete Invitation' }}
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="confirm-invitation-removal" focusable>
        <form method="POST" action="{{ route('invitations.destroy', $invitation) }}" class="p-6">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium theme-header-text">Are you sure?</h2>
            <p class="mt-1 text-sm theme-body-text">
                @if ($invitation->status === 'accepted' || $invitation->status === 'registered')
                    This will permanently remove the user <strong
                        class="font-semibold">{{ $invitation->email }}</strong> and all their associated data from your
                    account. They will lose access immediately. This action cannot be undone.
                @else
                    This will permanently delete the pending invitation for <strong
                        class="font-semibold">{{ $invitation->email }}</strong>. This action cannot be undone.
                @endif
            </p>
            <div class="mt-6 flex justify-end space-x-4">
                <button type="button" x-on:click="$dispatch('close-modal')"
                    class="theme-button theme-button-secondary">Cancel</button>
                <button type="submit" class="theme-button theme-button-danger">Confirm & Remove</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
