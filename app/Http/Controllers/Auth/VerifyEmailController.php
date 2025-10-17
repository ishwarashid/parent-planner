<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            
            // Mark the verification attempt as verified
            $latestAttempt = $request->user()->verificationAttempts()->latest()->first();
            if ($latestAttempt) {
                $latestAttempt->update([
                    'status' => 'verified',
                    'verified_at' => now()
                ]);
            }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
