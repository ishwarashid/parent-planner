<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('You have been invited to join Parent Planner by :inviterName as a :role.', ['inviterName' => $invitation->invitedBy->name, 'role' => $invitation->role]) }}
        Please choose to accept or reject this invitation.
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-accept-invitation')"
            class="ms-4">
            {{ __('Accept Invitation') }}
        </x-primary-button>

        <x-danger-button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-reject-invitation')"
            class="ms-4">
            {{ __('Reject Invitation') }}
        </x-danger-button>
    </div>

    <x-modal name="confirm-accept-invitation" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('invitations.accept.process', $invitation) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to accept this invitation?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('By accepting, you will be linked to the account of :inviterName as a :role.', ['inviterName' => $invitation->invitedBy->name, 'role' => $invitation->role]) }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Accept') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="confirm-reject-invitation" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('invitations.reject.process', $invitation) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to reject this invitation?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('This action cannot be undone.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Reject') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-guest-layout>
