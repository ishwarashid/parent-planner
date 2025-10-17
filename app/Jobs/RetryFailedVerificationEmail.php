<?php

namespace App\Jobs;

use App\Models\VerificationAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetryFailedVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $verificationAttempt;

    /**
     * Create a new job instance.
     */
    public function __construct(VerificationAttempt $verificationAttempt)
    {
        $this->verificationAttempt = $verificationAttempt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $attempt = $this->verificationAttempt;
        
        // Only retry if the attempt is in 'failed' status and the user hasn't verified their email
        if ($attempt->status !== 'failed' || $attempt->user->hasVerifiedEmail()) {
            return;
        }

        // Check if the number of attempts has exceeded the maximum (e.g., 5 attempts)
        if ($attempt->attempt_count >= 5) {
            Log::warning('Maximum retry attempts reached for user verification', [
                'user_id' => $attempt->user_id,
                'email' => $attempt->email,
                'attempt_count' => $attempt->attempt_count
            ]);
            return;
        }

        try {
            // Resend the verification email
            $attempt->user->sendEmailVerificationNotification();
            
            // Update the attempt record
            $attempt->update([
                'status' => 'sent',
                'sent_at' => now(),
                'attempt_count' => $attempt->attempt_count + 1,
            ]);

            Log::info('Verification email retry successful', [
                'user_id' => $attempt->user_id,
                'email' => $attempt->email,
                'attempt_number' => $attempt->attempt_count
            ]);
        } catch (\Exception $e) {
            Log::error('Verification email retry failed', [
                'user_id' => $attempt->user_id,
                'email' => $attempt->email,
                'attempt_number' => $attempt->attempt_count + 1,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update attempt with failure info
            $attempt->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            // If this was the last attempt, notify an admin or take other action
            if ($attempt->attempt_count >= 4) { // This will be the 5th attempt after incrementing
                // In a real app, you might want to notify an admin here
                Log::alert('User unable to receive verification email after multiple attempts', [
                    'user_id' => $attempt->user_id,
                    'email' => $attempt->email
                ]);
            }
        }
    }
}
