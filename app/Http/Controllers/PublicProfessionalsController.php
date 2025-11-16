<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class PublicProfessionalsController extends Controller
{
    public function index(Request $request)
    {
        // Define the lists for our filter dropdowns
        $continents = ['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'];
        $services = [
            'Health & Wellness',
            'Education & Learning',
            'Legal & Financial services',
            'Childcare & Parenting',
            'Activities & Enrichment',
            'Household Services',
            'Other'
        ];

        $professionals = Professional::query()
            // Start with our base scope for visible professionals
            ->approvedAndSubscribed()
            // Apply all filters from the request using our filter scope
            ->filter($request->only(['search', 'service_filter', 'continent_filter']))
            // Order by most recent and paginate
            ->latest()
            ->paginate(9)
            ->withQueryString(); // Keep filters on pagination links

        // Pass the data to the view
        return view('professionals.public.index', compact('professionals', 'continents', 'services'));
    }

    public function show(Professional $professional)
    {
        // Eager load the user relationship for the subscription check
        $professional->load('user');

        // IMPORTANT: Prevent direct access to profiles that are not approved or subscribed
        abort_if(
            $professional->approval_status !== 'approved' || !$professional->hasActiveSubscription(),
            404
        );

        return view('professionals.public.show', compact('professional'));
    }
}
