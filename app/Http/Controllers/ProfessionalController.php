<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfessionalController extends Controller
{
    /**
     * Display the professional's dashboard.
     */
    public function dashboard()
    {
        $professional = Auth::user()->professional;
        return view('professionals.dashboard', compact('professional'));
    }

    /**
     * Show the form for editing the professional's profile.
     */
    public function edit()
    {
        $professional = Auth::user()->professional;
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

        return view('professionals.edit', compact('professional', 'continents', 'services'));
    }

    /**
     * Update the professional's profile in the database.
     */
    public function update(Request $request)
    {
        $professional = Auth::user()->professional;

        $predefinedServices = [
            'Health & Wellness',
            'Education & Learning',
            'Legal & Financial services',
            'Childcare & Parenting',
            'Activities & Enrichment',
            'Household Services',
            'Other'
        ];

        $validatedData = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'services' => ['required', 'array'],
            'services.*' => ['string', Rule::in($predefinedServices)],
            'phone_number' => ['required', 'string', 'max:255'],
            'continent' => ['string', Rule::in(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'])],
            'country' => ['string', 'max:255'],
            'city' => ['string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
        ]);

        $professional->update($validatedData);

        return redirect()->route('professional.dashboard')->with('success', 'Profile updated successfully.');
    }
}
