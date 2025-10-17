<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\VerificationAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Get the latest verification attempt to provide context to the user
        $latestAttempt = $request->user()->verificationAttempts()->latest()->first();
        
        // Check if there's a recent failed attempt
        $recentFailedAttempt = $request->user()->verificationAttempts()
            ->where('status', 'failed')
            ->where('created_at', '>', now()->subHours(1))
            ->latest()
            ->first();

        try {
            $request->user()->sendEmailVerificationNotification();
            
            // Update the latest verification attempt status to 'sent'
            $latestAttempt = $request->user()->verificationAttempts()->latest()->first();
            if ($latestAttempt) {
                $latestAttempt->update([
                    'status' => 'sent',
                    'sent_at' => now()
                ]);
            }

            // Provide different feedback based on previous attempts
            if ($recentFailedAttempt) {
                // If there was a recent failure, let them know we tried again
                return back()->with('status', 'verification-link-sent')
                           ->with('message', 'A new verification email has been sent. Please check your inbox and spam folder.');
            } else {
                return back()->with('status', 'verification-link-sent');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to send email verification notification', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update the latest verification attempt status to 'failed'
            $latestAttempt = $request->user()->verificationAttempts()->latest()->first();
            if ($latestAttempt) {
                $latestAttempt->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }

            // Provide more specific error feedback
            $errorMessage = $this->getSpecificErrorMessage($e->getMessage());
            return back()->with('error', $errorMessage)
                         ->withInput();
        }
    }

    /**
     * Provide more specific error messages to users.
     */
    private function getSpecificErrorMessage(string $errorMessage): string
    {
        // Check for common email errors and provide user-friendly messages
        if (str_contains(strtolower($errorMessage), 'connection')) {
            return 'Unable to connect to the email server. Please try again later.';
        } elseif (str_contains(strtolower($errorMessage), 'timeout')) {
            return 'Email sending timed out. Please try again later.';
        } elseif (str_contains(strtolower($errorMessage), 'smtp')) {
            return 'There was an issue with the email service. Please try again later.';
        } elseif (str_contains(strtolower($errorMessage), 'address')) {
            return 'The email address may be invalid. Please verify your email address.';
        } else {
            return 'Failed to send verification email. Please try again later or contact support if the problem persists.';
        }
    }
}
