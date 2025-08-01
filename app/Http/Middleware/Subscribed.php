<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // For parents, check for the default subscription.
            if ($user->role === 'parent' && !$user->subscribed('default')) {
                // Redirect to the main pricing page if not subscribed.
                return redirect()->route('pricing');
            }

            // For professionals, check for the professional subscription.
            // This should only apply after they are approved.
            if ($user->role === 'professional' && $user->professional?->status === 'approved' && !$user->subscribed('professional')) {
                // Redirect to their own dashboard to subscribe.
                return redirect()->route('professional.dashboard')->with('error', 'You must subscribe to list your profile publicly.');
            }
        }

        return $next($request);
    }
}