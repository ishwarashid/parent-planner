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
        return $user->can('view-documents') && $user->getAccountOwnerId() === $document->user_id;
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
        return $user->can('update-documents') && $user->getAccountOwnerId() === $document->user_id;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->can('delete-documents') && $user->getAccountOwnerId() === $document->user_id;
    }
}