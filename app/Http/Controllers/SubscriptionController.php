<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function pricing()
    {
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
