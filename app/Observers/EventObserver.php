<?php

namespace App\Observers;

use App\Models\Event;
use App\Notifications\EventCreatedNotification;
use App\Notifications\EventUpdatedNotification;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        // Send notification to the assigned user, creator, and all coparents if child is associated
        $usersToNotify = collect();
        
        // Add the assigned user if one exists
        if ($event->assignee) {
            $usersToNotify->push($event->assignee);
        }
        
        // Add the creator
        $usersToNotify->push($event->user);
        
        // If the event is associated with a child, add all coparents
        if ($event->child && $event->child->user) {
            $familyMemberIds = $event->child->user->getFamilyMemberIds();
            foreach ($familyMemberIds as $userId) {
                $user = \App\Models\User::find($userId);
                if ($user && !$usersToNotify->contains($user)) {
                    $usersToNotify->push($user);
                }
            }
        }
        
        // Send notification to all unique users
        foreach ($usersToNotify as $user) {
            $user->notify(new EventCreatedNotification($event));
        }
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Send notification to the assigned user, creator, and all coparents if child is associated
        $usersToNotify = collect();
        
        // Add the assigned user if one exists
        if ($event->assignee) {
            $usersToNotify->push($event->assignee);
        }
        
        // Add the creator
        $usersToNotify->push($event->user);
        
        // If the event is associated with a child, add all coparents
        if ($event->child && $event->child->user) {
            $familyMemberIds = $event->child->user->getFamilyMemberIds();
            foreach ($familyMemberIds as $userId) {
                $user = \App\Models\User::find($userId);
                if ($user && !$usersToNotify->contains($user)) {
                    $usersToNotify->push($user);
                }
            }
        }
        
        // Send notification to all unique users
        foreach ($usersToNotify as $user) {
            $user->notify(new EventUpdatedNotification($event));
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}