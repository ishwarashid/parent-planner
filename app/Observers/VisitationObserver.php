<?php

namespace App\Observers;

use App\Models\Visitation;
use App\Notifications\VisitationCreatedNotification;
use App\Notifications\VisitationUpdatedNotification;

class VisitationObserver
{
    /**
     * Handle the Visitation "created" event.
     */
    public function created(Visitation $visitation): void
    {
        // Send notification to the parent assigned to this visitation and all coparents
        if ($visitation->child && $visitation->child->user) {
            $familyMemberIds = $visitation->child->user->getFamilyMemberIds();
            foreach ($familyMemberIds as $userId) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $user->notify(new VisitationCreatedNotification($visitation));
                }
            }
        } else {
            // If no child/user relationship exists, just notify the assigned parent
            $visitation->parent->notify(new VisitationCreatedNotification($visitation));
        }
    }

    /**
     * Handle the Visitation "updated" event.
     */
    public function updated(Visitation $visitation): void
    {
        // Send notification to the parent assigned to this visitation and all coparents
        if ($visitation->child && $visitation->child->user) {
            $familyMemberIds = $visitation->child->user->getFamilyMemberIds();
            foreach ($familyMemberIds as $userId) {
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $user->notify(new VisitationUpdatedNotification($visitation));
                }
            }
        } else {
            // If no child/user relationship exists, just notify the assigned parent
            $visitation->parent->notify(new VisitationUpdatedNotification($visitation));
        }
    }

    /**
     * Handle the Visitation "deleted" event.
     */
    public function deleted(Visitation $visitation): void
    {
        //
    }

    /**
     * Handle the Visitation "restored" event.
     */
    public function restored(Visitation $visitation): void
    {
        //
    }

    /**
     * Handle the Visitation "force deleted" event.
     */
    public function forceDeleted(Visitation $visitation): void
    {
        //
    }
}