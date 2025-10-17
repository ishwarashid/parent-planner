<?php

namespace App\Console\Commands;

use App\Jobs\RetryFailedVerificationEmail;
use App\Models\VerificationAttempt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessFailedVerificationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:retry-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry sending verification emails for failed attempts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing failed verification email attempts...');

        // Get failed verification attempts that are at least 5 minutes old to allow enough time
        // for the initial attempt to be processed
        $failedAttempts = VerificationAttempt::where('status', 'failed')
            ->where('created_at', '<', now()->subMinutes(5))
            ->where('attempt_count', '<', 5) // Only retry if less than 5 attempts made
            ->get();

        $retryCount = 0;
        
        foreach ($failedAttempts as $attempt) {
            // Only retry if the user hasn't verified their email yet
            if (!$attempt->user->hasVerifiedEmail()) {
                // Dispatch the retry job
                RetryFailedVerificationEmail::dispatch($attempt);
                $retryCount++;
                
                $this->info("Queued retry for user {$attempt->user->id} (email: {$attempt->email})");
            }
        }

        $this->info("Queued {$retryCount} retry jobs for failed verification emails.");
        Log::info("Verification retry command completed", ['retries_queued' => $retryCount]);

        return Command::SUCCESS;
    }
}
