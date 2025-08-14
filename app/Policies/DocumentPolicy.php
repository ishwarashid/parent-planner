<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view the list of documents.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-documents');
    }

    /**
     * Determine whether the user can view a specific document.
     * Checks for both permission and account ownership.
     */
    public function view(User $user, Document $document): bool
    {
        // Get all family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();
        
        // Check if the user has permission and if the document's child belongs to the family
        return $user->can('view-documents') && in_array($document->child->user_id, $familyMemberIds);
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        return $user->can('create-documents');
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document): bool
    {
        // Get all family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();
        
        // Check if the user has permission and if the document's child belongs to the family
        return $user->can('update-documents') && in_array($document->child->user_id, $familyMemberIds);
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        // Get all family member IDs
        $familyMemberIds = $user->getFamilyMemberIds();
        
        // Check if the user has permission and if the document's child belongs to the family
        return $user->can('delete-documents') && in_array($document->child->user_id, $familyMemberIds);
    }
}