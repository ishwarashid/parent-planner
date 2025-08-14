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
        // Get all family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();
        
        // Check if the user has permission and if the expense's child belongs to the family
        return $user->can('view-expenses') && in_array($expense->child->user_id, $familyMemberIds);
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
        // Get all family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();
        
        // Check if the user has permission and if the expense's child belongs to the family
        return $user->can('update-expenses') && in_array($expense->child->user_id, $familyMemberIds);
    }

    /**
     * Determine whether the user can delete the expense.
     */
    public function delete(User $user, Expense $expense): bool
    {
        // Get all family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();
        
        // Check if the user has permission and if the expense's child belongs to the family
        return $user->can('delete-expenses') && in_array($expense->child->user_id, $familyMemberIds);
    }
}