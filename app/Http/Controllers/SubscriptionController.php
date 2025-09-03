<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Paddle\Cashier;

class SubscriptionController extends Controller
{
    public function pricing()
    {
        $user = Auth::user();
        
        // Check if user has an active subscription in our database
        if ($user->subscribed('default')) {
            return redirect()->route('dashboard');
        }
        
        // Check if user has an active subscription in Paddle (fallback check)
        try {
            if ($user->customer) {
                $response = Cashier::api('GET', "subscriptions?customer_id={$user->customer->paddle_id}&status=active");
                $subscriptions = $response->json()['data'] ?? [];
                
                if (!empty($subscriptions)) {
                    // User has an active subscription in Paddle, redirect to dashboard
                    return redirect()->route('dashboard')->with('status', 'Subscription activated successfully!');
                }
            }
        } catch (\Exception $e) {
            // If there's an error checking Paddle, continue to show pricing page
            \Log::error('Error checking Paddle subscription status: ' . $e->getMessage());
        }
        
        return view('subscriptions.pricing');
    }

    public function checkout(Request $request)
    {
        if (auth()->user()->subscribed('default')) {
            return redirect()->route('dashboard')->with('error', 'You are already subscribed.');
        }
        $plan = $request->input('plan');
        $user = Auth::user();

        $checkout = $user->checkout($plan)
            ->returnTo(route('dashboard'));

        return view('subscriptions.checkout', ['checkout' => $checkout]);
    }

    public function billing()
    {
        return view('subscriptions.billing');
    }

    public function portal()
    {
        return Auth::user()->redirectToBillingPortal(route('dashboard'));
    }

    public function professionalPricing()
    {
        return view('professionals.pricing');
    }
}
