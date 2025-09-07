<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visitation;

class VisitationPolicy
{
    /**
     * Determine whether the user can view the list of visitations.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-visitations');
    }

    /**
     * Determine whether the user can view a specific visitation.
     */
    // public function view(User $user, Visitation $visitation): bool
    // {
    //     // return $user->can('view-visitations') && $user->getAccountOwnerId() === $visitation->user_id;
    //     return $user->can('view-visitations');
    // }

    public function view(User $user, Visitation $visitation): bool
    {
        // First check if user has permission to view visitations
        if (!$user->can('view-visitations')) {
            return false;
        }

        // Get family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();

        // Check if the visitation's assigned parent belongs to the user's family
        if (!in_array($visitation->parent_id, $familyMemberIds)) {
            return false;
        }

        // Main/Admin Parent can see any visitation in their family
        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            return true;
        }

        // Other users can view visitations if:
        // 1. Assigned to them (parent_id matches user id)
        // 2. Created by them (created_by matches user id)
        return $visitation->parent_id === $user->id || $visitation->created_by === $user->id;
    }

    /**
     * Determine whether the user can create visitations.
     */
    public function create(User $user): bool
    {
        return $user->can('create-visitations');
    }

    /**
     * Determine whether the user can update the visitation.
     */
    public function update(User $user, Visitation $visitation): bool
    {
        // Allow if the user is a Main/Admin parent OR if they are the original creator of the event
        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            return $user->can('update-visitations');
        }

        return $user->can('update-visitations') && $visitation->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the visitation.
     */
    public function delete(User $user, Visitation $visitation): bool
    {
        // Allow if the user is a Main/Admin parent OR if they are the original creator of the event
        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            return $user->can('delete-visitations');
        }

        return $user->can('delete-visitations') && $visitation->created_by === $user->id;
    }
}
