<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * This policy enforces a clear separation of concerns:
     * - Viewing an expense is open to any authorized family member.
     * - Modifying (updating or deleting) an expense is restricted to the original payer.
     */

    /**
     * Determine whether the user can view the list of expenses.
     */
    public function viewAny(User $user): bool
    {
        // This remains the same. A user needs the general permission to see the index page.
        return $user->can('view-expenses');
    }

    /**
     * Determine whether the user can view a specific expense.
     */
    public function view(User $user, Expense $expense): bool
    {
        // This also remains the same. Any parent in the family can view a shared expense.
        $familyMemberIds = $user->getFamilyMemberIds();

        // The user must have the permission and the expense must be related to their family.
        return $user->can('view-expenses') && in_array($expense->child->user_id, $familyMemberIds);
    }

    /**
     * Determine whether the user can create expenses.
     */
    public function create(User $user): bool
    {
        // This remains the same. A user needs the permission to access the create form.
        return $user->can('create-expenses');
    }

    /**
     * Determine whether the user can update the expense.
     * NEW LOGIC: Only the original payer can update the expense.
     */
    public function update(User $user, Expense $expense): bool
    {
        // The user must have the general permission to update AND they must be the payer.
        return $user->can('update-expenses') && $user->id === $expense->payer_id;
    }

    /**
     * Determine whether the user can delete the expense.
     * NEW LOGIC: Only the original payer can delete the expense.
     */
    public function delete(User $user, Expense $expense): bool
    {
        // The user must have the general permission to delete AND they must be the payer.
        return $user->can('delete-expenses') && $user->id === $expense->payer_id;
    }
}
