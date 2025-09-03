<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Paddle\Cashier;
use Exception;

class SubscriptionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // Check if user has a subscription
        $subscription = $user->subscription();
        
        // Get available plans (using example Paddle sandbox plan IDs)
        $plans = [
            [
                'id' => 'pri_01k479ewfx5kh4x8yqy2zcaneq', // Example Basic Monthly
                'name' => 'Basic Plan (Monthly)',
                'price' => '$3/month'
            ],
            [
                'id' => 'pri_01k479h0xtvns9g9rtbw41h373', // Example Basic Yearly
                'name' => 'Basic Plan (Yearly)',
                'price' => '$25/year'
            ],
            [
                'id' => 'pri_01k479kysbxxcsmndz9gzzp5dt', // Example Premium Monthly
                'name' => 'Premium Plan (Monthly)',
                'price' => '$5/month'
            ],
            [
                'id' => 'pri_01k479mb6cdegmhzyt71r00yem', // Example Premium Yearly
                'name' => 'Premium Plan (Yearly)',
                'price' => '$48/year'
            ]
        ];
        
        // Get the plan name if subscription exists
        $planName = 'No Subscription';
        if ($subscription && $subscription->items->count() > 0) {
            $firstItem = $subscription->items->first();
            // Find the plan name from our plans array
            foreach ($plans as $plan) {
                if ($plan['id'] === $firstItem->price_id) {
                    $planName = $plan['name'];
                    break;
                }
            }
            
            // If we didn't find it in our plans, try to get it from Paddle
            if ($planName === 'No Subscription') {
                try {
                    $response = Cashier::api('GET', "prices/{$firstItem->price_id}");
                    $price = $response['data'] ?? null;
                    if ($price) {
                        $planName = $price['description'] ?? $price['name'] ?? 'Unknown Plan';
                    }
                } catch (Exception $e) {
                    // If we can't get the plan name from Paddle, use a generic name
                    $planName = 'Subscription Plan';
                }
            }
        }
        
        return view('subscription.show', compact('subscription', 'plans', 'planName'));
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Check if user has a subscription
            if (!$user->subscription()) {
                return redirect()->route('subscription.show')->with('error', 'You do not have an active subscription.');
            }
            
            // Cancel the subscription at period end
            $user->subscription()->cancel();
            
            return redirect()->route('subscription.show')->with('status', 'Your subscription has been canceled and will end at the end of the billing period.');
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    public function resume(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Check if user has a subscription
            if (!$user->subscription()) {
                return redirect()->route('subscription.show')->with('error', 'You do not have a subscription.');
            }
            
            // Resume the subscription
            $user->subscription()->resume();
            
            return redirect()->route('subscription.show')->with('status', 'Your subscription has been resumed.');
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->with('error', 'Failed to resume subscription: ' . $e->getMessage());
        }
    }

    public function swap(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'plan' => 'required'
        ]);
        
        try {
            // Check if user has a subscription
            if (!$user->subscription()) {
                return redirect()->route('subscription.show')->with('error', 'You do not have a subscription.');
            }
            
            // Swap the plan
            $user->subscription()->swap($request->plan);
            
            return redirect()->route('subscription.show')->with('status', 'Your subscription has been updated.');
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->with('error', 'Failed to update subscription: ' . $e->getMessage());
        }
    }

    // Existing methods...
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
        $user = Auth::user();
        
        // Check if the user has a Paddle customer ID
        if (!$user->customer) {
            // If not, redirect back with an error
            return redirect()->back()->with('error', 'Unable to access billing portal.');
        }
        
        try {
            // Get the customer's portal URL from Paddle API
            $response = \Laravel\Paddle\Cashier::api('GET', "customers/{$user->customer->paddle_id}");
            $portalUrl = $response['data']['portal_url'] ?? null;
            
            // Check if we got a portal URL
            if (!$portalUrl) {
                return redirect()->back()->with('error', 'Unable to access billing portal.');
            }
            
            // Redirect to the portal URL
            return redirect($portalUrl);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error accessing Paddle billing portal: ' . $e->getMessage());
            
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Unable to access billing portal.');
        }
    }

    public function portal()
    {
        $user = Auth::user();
        
        // Check if the user has a Paddle customer ID
        if (!$user->customer) {
            // If not, redirect back with an error
            return redirect()->back()->with('error', 'Unable to access billing portal.');
        }
        
        try {
            // Get the customer's portal URL from Paddle API
            $response = \Laravel\Paddle\Cashier::api('GET', "customers/{$user->customer->paddle_id}");
            $portalUrl = $response['data']['portal_url'] ?? null;
            
            // Check if we got a portal URL
            if (!$portalUrl) {
                return redirect()->back()->with('error', 'Unable to access billing portal.');
            }
            
            // Redirect to the portal URL
            return redirect($portalUrl);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error accessing Paddle billing portal: ' . $e->getMessage());
            
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Unable to access billing portal.');
        }
    }

    public function professionalPricing()
    {
        return view('professionals.pricing');
    }
}