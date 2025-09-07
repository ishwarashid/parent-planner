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
        $subscription = $user->subscription();

        // Role-based plan IDs
        if ($user->role == 'professional') {
            $priceIds = [
                'pri_01k4f4cp96rcgxyf15gg167pex', // Professional Monthly
                'pri_01k4f4dh6rz19qsf5djr73y0mg'  // Professional Yearly
            ];
        } else {
            $priceIds = [
                'pri_01k479ewfx5kh4x8yqy2zcaneq', // Basic Monthly
                'pri_01k479h0xtvns9g9rtbw41h373', // Basic Yearly
                'pri_01k479kysbxxcsmndz9gzzp5dt', // Premium Monthly
                'pri_01k479mb6cdegmhzyt71r00yem'  // Premium Yearly
            ];
        }

        // Fetch only those prices
        $prices = $this->paddleService->fetchPrices($priceIds);

        // Build flat plans array for the view
        $plans = [];
        foreach ($prices as $price) {
            $interval = str_contains(strtolower($price['name'] ?? ''), 'year') ? 'yearly' : 'monthly';

            $plans[] = [
                'id'       => $price['id'],
                'name'     => $price['name'] ?? ($price['description'] ?? 'Unknown Plan'),
                'price'    => $price['price'] ?? 'N/A',
                'interval' => $interval,
                'type'     => $user->role === 'professional'
                    ? 'professional'
                    : (str_contains(strtolower($price['name'] ?? ''), 'premium') ? 'premium' : 'basic')
            ];
        }

        // Default plan name
        $planName = 'No Subscription';

        // Resolve current plan name if user has subscription
        if ($subscription && $subscription->items->count() > 0) {
            $firstItem = $subscription->items->first();

            foreach ($plans as $plan) {
                if ($plan['id'] === $firstItem->price_id) {
                    $planName = $plan['name'];
                    break;
                }
            }

            // Fallback: fetch from Paddle API if not matched
            if ($planName === 'No Subscription') {
                try {
                    $response = Cashier::api('GET', "prices/{$firstItem->price_id}");
                    $price = $response['data'] ?? null;
                    if ($price) {
                        $planName = $price['description'] ?? $price['name'] ?? 'Unknown Plan';
                    }
                } catch (Exception $e) {
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
            $user->subscription()->swapAndInvoice($request->plan);
            
            return redirect()->route('subscription.show')->with('status', 'Your subscription has been updated.');
        } catch (Exception $e) {
            return redirect()->route('subscription.show')->with('error', 'Failed to update subscription: ' . $e->getMessage());
        }
    }

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
        
        // Fetch prices from Paddle
        $priceIds = [
            'pri_01k479ewfx5kh4x8yqy2zcaneq', // Basic Monthly
            'pri_01k479h0xtvns9g9rtbw41h373', // Basic Yearly
            'pri_01k479kysbxxcsmndz9gzzp5dt', // Premium Monthly
            'pri_01k479mb6cdegmhzyt71r00yem'  // Premium Yearly
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
            if (strpos($price['id'], 'pri_01k479ewfx5kh4x8yqy2zcaneq') !== false || 
                strpos($price['name'], 'Basic') !== false && strpos($price['name'], 'Monthly') !== false) {
                $plans['basic']['monthly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k479h0xtvns9g9rtbw41h373') !== false || 
                      strpos($price['name'], 'Basic') !== false && strpos($price['name'], 'Yearly') !== false) {
                $plans['basic']['yearly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k479kysbxxcsmndz9gzzp5dt') !== false || 
                      strpos($price['name'], 'Premium') !== false && strpos($price['name'], 'Monthly') !== false) {
                $plans['premium']['monthly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k479mb6cdegmhzyt71r00yem') !== false || 
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
            // Get the subscription
            $subscription = $user->subscription();
            
            if (!$subscription) {
                return redirect()->route('subscription.show')->with('error', 'You do not have an active subscription.');
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
            'pri_01k4f4cp96rcgxyf15gg167pex', // Professional Monthly
            'pri_01k4f4dh6rz19qsf5djr73y0mg'  // Professional Yearly
        ];
        
        $prices = $this->paddleService->fetchPrices($priceIds);
        
        // Organize prices by type (monthly/yearly)
        $plans = [
            'monthly' => null,
            'yearly' => null
        ];
        
        foreach ($prices as $price) {
            if (strpos($price['id'], 'pri_01k4f4cp96rcgxyf15gg167pex') !== false) {
                $plans['monthly'] = $price;
            } elseif (strpos($price['id'], 'pri_01k4f4dh6rz19qsf5djr73y0mg') !== false) {
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