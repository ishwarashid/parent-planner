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

    private function getBillableContext(Request $request)
    {
        $user = Auth::user();
        // We determine the context by checking the request path. This is more reliable than role.
        if ($request->is('professional/*')) {
            $professional = $user->professional;
            if (!$professional) {
                abort(403, 'Professional profile not found.');
            }
            return (object)[
                'entity' => $professional,
                'name' => 'professional',
            ];
        }

        // Default to the parent (User) context
        return (object)[
            'entity' => $user,
            'name' => 'default',
        ];
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $context = $this->getBillableContext($request); // ADDED: Get the context

        // CHANGED: Get the subscription from the correct entity and with the correct name.
        $subscription = $context->entity->subscription($context->name);
        \Log::info($subscription);

        // Role-based plan IDs
        if ($context->name === 'professional') { // CHANGED: Check context name
            $priceIds = [
                'pri_01k4m53g0ddw2pt8wgjwsdpjwr', // Professional Monthly
                'pri_01k4m54crw11827hzxp3ngms0j'  // Professional Yearly
            ];
        } else {
            $priceIds = [
                'pri_01k4m4zrc1w8qjqrdvsj309r9h', // Basic Monthly
                'pri_01k4m50qe4tspwqzzcb5pj21fs', // Basic Yearly
                'pri_01k4m51sqtkav7fp4fesrxpjmz', // Premium Monthly
                'pri_01k4m52mb2br3rj9nfn8pbpez6'  // Premium Yearly
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
        return view('subscription.show', compact('subscription', 'plans', 'planName', 'context'));
    }

    public function cancel(Request $request)
    {
        $context = $this->getBillableContext($request);
        $subscription = $context->entity->subscription($context->name);

        if (!$subscription) {
            return back()->with('error', 'You do not have an active subscription.');
        }

        $subscription->cancel();
        return back()->with('status', 'Your subscription has been canceled.');
    }

    public function resume(Request $request)
    {
        $context = $this->getBillableContext($request);
        $subscription = $context->entity->subscription($context->name);

        if (!$subscription) {
            return back()->with('error', 'You do not have a subscription.');
        }

        $subscription->resume();
        return back()->with('status', 'Your subscription has been resumed.');
    }

    public function swap(Request $request)
    {
        $request->validate(['plan' => 'required']);
        
        // CHANGED: Whole method updated to use context
        $context = $this->getBillableContext($request);
        $subscription = $context->entity->subscription($context->name);

        if (!$subscription) {
            return back()->with('error', 'You do not have a subscription.');
        }

        $subscription->swapAndInvoice($request->plan);
        return back()->with('status', 'Your subscription has been updated.');
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
        $plan = $request->input('plan');
        $user = Auth::user();

        $type = $request->input('type');

        if ($type === 'professional') {
            $billableEntity = $user->professional;
            if (!$billableEntity) {
                abort(403, 'Professional profile not found.');
            }
            $subscriptionName = 'professional';
            $returnRoute = route('professional.dashboard');
        } else {
            $billableEntity = $user;
            $subscriptionName = 'default';
            $returnRoute = route('dashboard');
        }

        if ($billableEntity->subscribed($subscriptionName)) {
            return redirect($returnRoute)->with('error', 'You are already subscribed.');
        }

        $checkout = $billableEntity->checkout($plan)->returnTo($returnRoute);

        return view('subscriptions.checkout', ['checkout' => $checkout]);
    }

    public function portal()
    {
        return redirect()->route('subscription.show');
    }

    public function updatePaymentMethod()
    {
        $context = $this->getBillableContext($request);
        $subscription = $context->entity->subscription($context->name);

        if (!$subscription) {
            return back()->with('error', 'You do not have an active subscription.');
        }
        
        // The API call is generic and works fine
        $response = Cashier::api('GET', "subscriptions/{$subscription->paddle_id}");
        $subscriptionData = $response['data'] ?? null;
        
        if (!$subscriptionData || !isset($subscriptionData['management_urls']['update_payment_method'])) {
            return back()->with('error', 'Unable to update payment method.');
        }
        
        return redirect($subscriptionData['management_urls']['update_payment_method']);
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