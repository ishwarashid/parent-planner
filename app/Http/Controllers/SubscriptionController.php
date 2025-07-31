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
        $plan = $request->input('plan');
        $user = Auth::user();

        return $user->newSubscription('default', $plan)
            ->checkout([
                'success_url' => route('dashboard'),
                'cancel_url' => route('pricing'),
            ]);
    }

    public function billing()
    {
        return view('subscriptions.billing', [
            'intent' => Auth::user()->createSetupIntent(),
        ]);
    }

    public function portal()
    {
        return Auth::user()->redirectToBillingPortal(route('dashboard'));
    }
}
