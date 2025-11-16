<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Paddle\Cashier;
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
                // Fallback check: Check if user has an active subscription in Paddle
                try {
                    if ($user->customer) {
                        $response = Cashier::api('GET', "subscriptions?customer_id={$user->customer->paddle_id}&status=active");
                        $subscriptions = $response->json()['data'] ?? [];
                        
                        if (!empty($subscriptions)) {
                            // User has an active subscription in Paddle
                            // Redirect to pricing page which will handle the redirect to dashboard
                            return redirect()->route('pricing');
                        }
                    }
                } catch (\Exception $e) {
                    // If there's an error checking Paddle, continue with normal flow
                    \Log::error('Error checking Paddle subscription status in middleware: ' . $e->getMessage());
                }
                
                // Redirect to the main pricing page if not subscribed.
                return redirect()->route('pricing');
            }

            // For professionals, check for the professional subscription.
            if ($user->role === 'professional' && $user->professional?->approval_status === 'approved' && !$user->professional?->hasActiveSubscription()) {
                // Redirect to the professional pricing page to subscribe.
                return redirect()->route('professional.pricing');
            }
        }

        return $next($request);
    }
}