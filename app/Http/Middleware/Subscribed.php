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
            // Check if user has both parent and professional roles
            $hasParentRole = $user->hasRole(['Main Parent', 'Invited User', 'Co-Parent']);
            $hasProfessionalRole = $user->hasRole('Professional');
            
            // For users with parent roles, check for the default subscription.
            if ($hasParentRole && $user->role !== 'professional' && !$user->subscribed('default')) {
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

            // For users with professional roles, check for the professional subscription.
            if ($hasProfessionalRole && $user->professional?->approval_status === 'approved' && !$user->subscribed('professional')) {
                // Redirect to the professional pricing page to subscribe.
                return redirect()->route('professional.pricing');
            }
            
            // For users who have both parent and professional roles, check for both subscriptions
            if ($hasParentRole && $hasProfessionalRole && $user->professional?->approval_status === 'approved') {
                // Check if user has a default (parent) subscription
                $hasParentSubscription = $user->subscribed('default');
                // Check if user has a professional subscription
                $hasProfessionalSubscription = $user->subscribed('professional');
                
                // If user has neither subscription, redirect to parent pricing first
                if (!$hasParentSubscription && !$hasProfessionalSubscription) {
                    return redirect()->route('pricing');
                }
                
                // If user has parent subscription but no professional subscription, redirect to professional pricing
                if ($hasParentSubscription && !$hasProfessionalSubscription) {
                    return redirect()->route('professional.pricing');
                }
                
                // If user has professional subscription but no parent subscription, redirect to parent pricing
                if (!$hasParentSubscription && $hasProfessionalSubscription) {
                    return redirect()->route('pricing');
                }
            }
        }

        return $next($request);
    }
}