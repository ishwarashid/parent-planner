<?php

namespace App\Notifications;

use App\Models\VerificationAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        // Check if a recent verification attempt already exists for this user
        $existingAttempt = $notifiable->verificationAttempts()
            ->latest()
            ->first();

        if ($existingAttempt && $existingAttempt->status === 'pending') {
            // Update the existing attempt to increment the count
            $existingAttempt->increment('attempt_count');
            $attempt = $existingAttempt;
        } else {
            // Create a new verification attempt record
            $attempt = $notifiable->verificationAttempts()->create([
                'email' => $notifiable->email,
                'status' => 'pending', // Will be updated based on email sending result
                'attempt_count' => 1,
            ]);
        }

        // Don't use static callback since it's not typically used in this context
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl(mixed $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }


}
