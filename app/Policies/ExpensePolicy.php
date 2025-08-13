<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * Determine whether the user can view the list of expenses.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-expenses');
    }

    /**
     * Determine whether the user can view a specific expense.
     * Checks for both permission and account ownership.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->can('view-expenses') && $user->getAccountOwnerId() === $expense->user_id;
    }

    /**
     * Determine whether the user can create expenses.
     */
    public function create(User $user): bool
    {
        return $user->can('create-expenses');
    }

    /**
     * Determine whether the user can update the expense.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $user->can('update-expenses') && $user->getAccountOwnerId() === $expense->user_id;
    }

    /**
     * Determine whether the user can delete the expense.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->can('delete-expenses') && $user->getAccountOwnerId() === $expense->user_id;
    }
}