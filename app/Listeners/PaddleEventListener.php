<?php

namespace App\Listeners;

use Laravel\Paddle\Events\WebhookReceived;

class PaddleEventListener
{   
    /**
     * The Paddle Price IDs that are considered "Professional" plans.
     * 
     * IMPORTANT: You MUST put your actual Professional Price IDs here.
     */
    const PROFESSIONAL_PLAN_IDS = [
        'pri_01k4f4cp96rcgxyf15gg167pex', // Professional Monthly
        'pri_01k4m54crw11827hzxp3ngms0j',  // Professional Yearly
    ];

    /**
     * Handle received Paddle webhooks.
     */
    public function handle(WebhookReceived $event): void
    {
        if (($event->payload['event_type'] ?? null) !== 'subscription.created') {
            return;
        }

        // 1. Get the customer ID from the webhook payload.
        $paddleCustomerId = $event->payload['data']['customer_id'] ?? null;
        if (!$paddleCustomerId) {
            return; // No customer ID in payload, can't proceed.
        }

        // // 2. Find the User in our database who is associated with this Paddle customer ID.
        // // This is the user that Cashier is about to (or has just) attached the subscription to.
        $user = \App\Models\User::whereHas('customer', function ($query) use ($paddleCustomerId) {
                $query->where('paddle_id', $paddleCustomerId);
            })->first();


        // If we can't find a user, or they don't have a professional profile, do nothing.
        if (!$user || !$user->professional) {
            return;
        }

        // Find the subscription that was just created in the database.
        $subscriptionPaddleId = $event->payload['data']['id'];
        
        $professional = $user->professional;
        
        if (($event->payload['event_type'] ?? null) === 'subscription.cancelled') {
            $professional->update([
                'paddle_id' => null
            ]);   
        } else {
            $professional->update([
                'paddle_id' => $subscriptionPaddleId
            ]);
        }

        \Log::info("Re-assignment complete.");
    }
}