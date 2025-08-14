<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

class ChildPolicy
{
    public function viewAny(User $user): bool
    {
        logger('viewAll');
        return $user->can('view-children');
    }

    // The view policy checks both permission AND ownership.
    public function view(User $user, Child $child): bool
    {
        logger($user->getAccountOwnerId());
        logger($child->user_id);

        // Does the user have the permission AND does the child belong to their family account?
        if ($user->can('view-children') && $user->getAccountOwnerId() === $child->user_id) {
            logger('view');
        } else {
            logger('no view');
        }

        return $user->can('view-children') && $user->getAccountOwnerId() === $child->user_id;
    }

    public function create(User $user): bool
    {
        logger('can create children');
        return $user->can('create-children');
    }

    public function update(User $user, Child $child): bool
    {
        return $user->can('update-children') && $user->getAccountOwnerId() === $child->user_id;
    }

    public function delete(User $user, Child $child): bool
    {
        return $user->can('delete-children') && $user->getAccountOwnerId() === $child->user_id;
    }
}
