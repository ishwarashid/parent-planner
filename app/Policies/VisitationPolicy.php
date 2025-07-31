<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visitation;
use Illuminate\Auth\Access\Response;

class VisitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Visitation $visitation): bool
    {
        return $user->id === $visitation->parent_id || $user->id === $visitation->child->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['parent', 'co-parent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Visitation $visitation): bool
    {
        return in_array($user->role, ['parent', 'co-parent']) && ($user->id === $visitation->parent_id || $user->id === $visitation->child->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Visitation $visitation): bool
    {
        return in_array($user->role, ['parent', 'co-parent']) && ($user->id === $visitation->parent_id || $user->id === $visitation->child->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Visitation $visitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Visitation $visitation): bool
    {
        return false;
    }
}
