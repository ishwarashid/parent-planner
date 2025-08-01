<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class PublicProfessionalsController extends Controller
{
    public function index(Request $request)
    {
        $query = Professional::query()
            ->where('approval_status', 'approved')
            ->whereHas('user', function ($q) {
                $q->whereHas('subscriptions', function ($sub) {
                    $sub->where('stripe_status', 'active');
                });
            });

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('business_name', 'like', "%{$searchTerm}%")
                  ->orWhere('services', 'like', "%{$searchTerm}%")
                  ->orWhere('country', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        $professionals = $query->paginate(10);

        return view('professionals.public.index', compact('professionals'));
    }
}