<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view the list of events.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-events');
    }

    /**
     * Determine whether the user can view a specific event.
     * Checks for both the permission and that the event belongs to the user's family account.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->can('view-events') && $user->getAccountOwnerId() === $event->user_id;
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->can('create-events');
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->can('update-events') && $user->getAccountOwnerId() === $event->user_id;
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->can('delete-events') && $user->getAccountOwnerId() === $event->user_id;
    }
}