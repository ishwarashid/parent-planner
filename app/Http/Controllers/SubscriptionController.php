<?php

namespace App\Http\Controllers;

use App\Services\PaddleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Paddle\Cashier;
use Exception;

class SubscriptionController extends Controller
{
    protected $paddleService;

    public function __construct(PaddleService $paddleService)
    {
        $this->paddleService = $paddleService;
    }

    public function show()
    {
        $user = Auth::user();
        
        // Determine if user has both professional and parent roles
        $hasParentRole = $user->hasRole(['Main Parent', 'Invited User', 'Co-Parent']);
        $hasProfessionalRole = $user->hasRole('Professional');
        
        // Get both subscriptions if they exist
        $parentSubscription = $user->subscribed('default') ? $user->subscription('default') : null;
        $professionalSubscription = $user->subscribed('professional') ? $user->subscription('professional') : null;
        
        // Determine which types of plans to show based on user's roles
        $priceIds = [];
        
        // Always include parent plans if user has parent role or no professional-only role
        if ($hasParentRole || (!$hasProfessionalRole || $hasProfessionalRole && $hasParentRole)) {
            $priceIds = array_merge($priceIds, [
                'pri_01k4m4zrc1w8qjqrdvsj309r9h', // Basic Monthly
                'pri_01k4m50qe4tspwqzzcb5pj21fs', // Basic Yearly
                'pri_01k4m51sqtkav7fp4fesrxpjmz', // Premium Monthly
                'pri_01k4m52mb2br3rj9nfn8pbpez6'  // Premium Yearly
            ]);
        }
        
        // Include professional plans if user has professional role
        if ($hasProfessionalRole) {
            $priceIds = array_merge($priceIds, [
                'pri_01k4m53g0ddw2pt8wgjwsdpjwr', // Professional Monthly
                'pri_01k4m54crw11827hzxp3ngms0j'  // Professional Yearly
            ]);
        }

        // Fetch all relevant prices
        $prices = $this->paddleService->fetchPrices($priceIds);

        // Build flat plans array for the view with type information
        $plans = [];
        foreach ($prices as $price) {
            $interval = str_contains(strtolower($price['name'] ?? ''), 'year') ? 'yearly' : 'monthly';

            // Determine plan type based on ID or name
            $planType = 'basic'; // default
            if (str_contains($price['name'] ?? '', 'Premium')) {
                $planType = 'premium';
            } elseif (str_contains($price['name'] ?? '', 'Professional')) {
                $planType = 'professional';
            }

            $plans[] = [
                'id'       => $price['id'],
                'name'     => $price['name'] ?? ($price['description'] ?? 'Unknown Plan'),
                'price'    => $price['price'] ?? 'N/A',
                'interval' => $interval,
                'type'     => $planType
            ];
        }

        // Prepare subscription information for the view
        $subscriptionData = [
            'parent' => $parentSubscription,
            'professional' => $professionalSubscription,
        ];
        
        // Get current plan names for each subscription type
        $parentPlanName = 'No Subscription';
        $professionalPlanName = 'No Subscription';

        // Resolve parent plan name if user has parent subscription
        if ($parentSubscription && $parentSubscription->items->count() > 0) {
            $firstItem = $parentSubscription->items->first();
            foreach ($plans as $plan) {
                if ($plan['id'] === $firstItem->price_id) {
                    $parentPlanName = $plan['name'];
                    break;
                }
            }

            // Fallback: fetch from Paddle API if not matched
            if ($parentPlanName === 'No Subscription') {
                try {
                    $response = Cashier::api('GET', "prices/{$firstItem->price_id}");
                    $price = $response['data'] ?? null;
                    if ($price) {
                        $parentPlanName = $price['description'] ?? $price['name'] ?? 'Unknown Plan';
                    }
                } catch (Exception $e) {
                    $parentPlanName = 'Parent Subscription Plan';
                }
            }
        }
        
        // Resolve professional plan name if user has professional subscription
        if ($professionalSubscription && $professionalSubscription->items->count() > 0) {
            $firstItem = $professionalSubscription->items->first();
            foreach ($plans as $plan) {
                if ($plan['id'] === $firstItem->price_id) {
                    $professionalPlanName = $plan['name'];
                    break;
                }
            }

            // Fallback: fetch from Paddle API if not matched
            if ($professionalPlanName === 'No Subscription') {
                try {
                    $response = Cashier::api('GET', "prices/{$firstItem->price_id}");
                    $price = $response['data'] ?? null;
                    if ($price) {
                        $professionalPlanName = $price['description'] ?? $price['name'] ?? 'Unknown Plan';
                    }
                } catch (Exception $e) {
                    $professionalPlanName = 'Professional Subscription Plan';
                }
            }
        }
        
        // Determine which subscription to use for the existing variables to maintain backward compatibility
        $subscription = $parentSubscription ?: $professionalSubscription;
        $planName = $parentPlanName !== 'No Subscription' ? $parentPlanName : $professionalPlanName;
        
        // Also pass all the new data to the view
        return view('subscription.show', compact(
            'subscription', 
            'plans', 
            'planName',
            'parentSubscription',
            'professionalSubscription', 
            'parentPlanName', 
            'professionalPlanName',
            'hasParentRole',
            'hasProfessionalRole'
        ));
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Determine which subscription to cancel based on the form
            $subscriptionType = $request->input('subscription_type', 'default');
            
            // Check if user has the specific subscription
            if (!$user->subscribed($subscriptionType)) {
                return redirect()->route('subscription.show')->with('error', "You do not have an active {$subscriptionType} subscription.");
            }
            
            // Get the subscription instance
            $subscription = $user->subscription($subscriptionType);
            
            // Cancel the subscription at period end
            $subscription->cancel();
            
            return redirect()->route('subscription.show')->with('status', "Your {$subscriptionType} subscription has been canceled and will end at the end of the billing period.");
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    public function resume(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Determine which subscription to resume based on the form
            $subscriptionType = $request->input('subscription_type', 'default');
            
            // Check if user has the specific subscription
            if (!$user->subscribed($subscriptionType)) {
                return redirect()->route('subscription.show')->with('error', "You do not have a {$subscriptionType} subscription.");
            }
            
            // Get the subscription instance
            $subscription = $user->subscription($subscriptionType);
            
            // Resume the subscription
            $subscription->resume();
            
            return redirect()->route('subscription.show')->with('status', "Your {$subscriptionType} subscription has been resumed.");
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
            // Determine which subscription to update based on the form
            $subscriptionType = $request->input('subscription_type', 'default');
            
            // Check if user has the specific subscription
            if (!$user->subscribed($subscriptionType)) {
                return redirect()->route('subscription.show')->with('error', "You do not have a {$subscriptionType} subscription.");
            }
            
            // Get the subscription instance
            $subscription = $user->subscription($subscriptionType);
            
            // Swap the plan
            $subscription->swapAndInvoice($request->plan);
            
            return redirect()->route('subscription.show')->with('status', "Your {$subscriptionType} subscription has been updated.");
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->with('error', 'Failed to update subscription: ' . $e->getMessage());
        }
    }

    public function pricing()
    {
        $user = Auth::user();
        
        // Check if user has an active subscription in our database
        if ($user != null && $user->subscribed('default')) {
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
        
        // Fetch prices from Paddle
        $priceIds = [
                'pri_01k4m4zrc1w8qjqrdvsj309r9h', // Basic Monthly
                'pri_01k4m50qe4tspwqzzcb5pj21fs', // Basic Yearly
                'pri_01k4m51sqtkav7fp4fesrxpjmz', // Premium Monthly
                'pri_01k4m52mb2br3rj9nfn8pbpez6'  // Premium Yearly
        ];
        
        $prices = $this->paddleService->fetchPrices($priceIds);
        
        // Organize prices by type (monthly/yearly)
        $plans = [
            'basic' => [
                'monthly' => null,
                'yearly' => null
            ],
            'premium' => [
                'monthly' => null,
                'yearly' => null
            ]
        ];
        
        foreach ($prices as $price) {
            // Determine plan type based on ID or name
            if (strpos($price['id'], 'pri_01k4m4zrc1w8qjqrdvsj309r9h') !== false || 
                strpos($price['name'], 'Basic') !== false && strpos($price['name'], 'Monthly') !== false) {
                $plans['basic']['monthly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k4m50qe4tspwqzzcb5pj21fs') !== false || 
                      strpos($price['name'], 'Basic') !== false && strpos($price['name'], 'Yearly') !== false) {
                $plans['basic']['yearly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k4m51sqtkav7fp4fesrxpjmz') !== false || 
                      strpos($price['name'], 'Premium') !== false && strpos($price['name'], 'Monthly') !== false) {
                $plans['premium']['monthly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k4m52mb2br3rj9nfn8pbpez6') !== false || 
                      strpos($price['name'], 'Premium') !== false && strpos($price['name'], 'Yearly') !== false) {
                $plans['premium']['yearly'] = $price;
            }
        }
        
        // Calculate savings for display
        $basicSavings = 0;
        $premiumSavings = 0;
        
        if ($plans['basic']['monthly'] && $plans['basic']['yearly']) {
            // Extract numeric values from price strings (e.g., "$3.00" -> 3.00)
            $basicMonthlyPrice = floatval(preg_replace('/[^\d.]/', '', $plans['basic']['monthly']['price']));
            $basicYearlyPrice = floatval(preg_replace('/[^\d.]/', '', $plans['basic']['yearly']['price']));
            // Calculate savings: (monthly price * 12) - yearly price
            $basicSavings = ($basicMonthlyPrice * 12) - $basicYearlyPrice;
        }
        
        if ($plans['premium']['monthly'] && $plans['premium']['yearly']) {
            // Extract numeric values from price strings (e.g., "$5.00" -> 5.00)
            $premiumMonthlyPrice = floatval(preg_replace('/[^\d.]/', '', $plans['premium']['monthly']['price']));
            $premiumYearlyPrice = floatval(preg_replace('/[^\d.]/', '', $plans['premium']['yearly']['price']));
            // Calculate savings: (monthly price * 12) - yearly price
            $premiumSavings = ($premiumMonthlyPrice * 12) - $premiumYearlyPrice;
        }
        
        // Use the higher savings value for display
        $savingsAmount = max($basicSavings, $premiumSavings);
        
        return view('subscriptions.pricing', compact('plans', 'savingsAmount'));
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

    public function portal()
    {
        return redirect()->route('subscription.show');
    }

    public function updatePaymentMethod()
    {
        $user = Auth::user();
        
        // Check if the user has a Paddle customer ID
        if (!$user->customer) {
            return redirect()->route('subscription.show')->with('error', 'Unable to update payment method.');
        }
        
        try {
            // Get the default subscription (or any active subscription) to get the update URL
            // For users with multiple subscriptions, Paddle typically uses the customer's main subscription for payment updates
            $subscription = $user->subscription('default') ?: $user->subscription('professional');
            
            if (!$subscription) {
                // If no active subscriptions exist, we can still allow payment method update as user is a customer
                // We'll redirect to customer update payment method URL instead of subscription-specific
                $response = \Laravel\Paddle\Cashier::api('GET', "customers/{$user->customer->paddle_id}");
                $customerData = $response['data'] ?? null;
                
                if (!$customerData || !isset($customerData['management_urls']['update_payment_method'])) {
                    return redirect()->route('subscription.show')->with('error', 'Unable to update payment method.');
                }
                
                $updatePaymentMethodUrl = $customerData['management_urls']['update_payment_method'];
                
                // Redirect to the update payment method URL
                return redirect($updatePaymentMethodUrl);
            }
            
            // Get the update payment method URL from Paddle
            $response = \Laravel\Paddle\Cashier::api('GET', "subscriptions/{$subscription->paddle_id}");
            $subscriptionData = $response['data'] ?? null;
            
            if (!$subscriptionData || !isset($subscriptionData['management_urls']['update_payment_method'])) {
                return redirect()->route('subscription.show')->with('error', 'Unable to update payment method.');
            }
            
            $updatePaymentMethodUrl = $subscriptionData['management_urls']['update_payment_method'];
            
            // Redirect to the update payment method URL
            return redirect($updatePaymentMethodUrl);
        } catch (\Exception $e) {
            \Log::error('Error updating payment method: ' . $e->getMessage());
            return redirect()->route('subscription.show')->with('error', 'Failed to update payment method: ' . $e->getMessage());
        }
    }

    public function professionalPricing()
    {
        // Fetch professional plan prices from Paddle
        $priceIds = [
            'pri_01k4m53g0ddw2pt8wgjwsdpjwr', // Professional Monthly
            'pri_01k4m54crw11827hzxp3ngms0j'  // Professional Yearly
        ];
        
        $prices = $this->paddleService->fetchPrices($priceIds);
        
        // Organize prices by type (monthly/yearly)
        $plans = [
            'monthly' => null,
            'yearly' => null
        ];
        
        foreach ($prices as $price) {
            if (strpos($price['id'], 'pri_01k4m53g0ddw2pt8wgjwsdpjwr') !== false) {
                $plans['monthly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k4m54crw11827hzxp3ngms0j') !== false) {
                $plans['yearly'] = $price;
            }
        }
        
        // Calculate savings for display
        $savingsAmount = 0;
        
        if ($plans['monthly'] && $plans['yearly']) {
            // Extract numeric values from price strings (e.g., "$14.00" -> 14.00)
            $monthlyPrice = floatval(preg_replace('/[^\d.]/', '', $plans['monthly']['price']));
            $yearlyPrice = floatval(preg_replace('/[^\d.]/', '', $plans['yearly']['price']));
            // Calculate savings: (monthly price * 12) - yearly price
            $savingsAmount = ($monthlyPrice * 12) - $yearlyPrice;
        }
        
        return view('professionals.pricing', compact('plans', 'savingsAmount'));
    }
}