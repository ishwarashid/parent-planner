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
            'custom_service' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) use ($request) {
                $services = $request->input('services', []);
                if (in_array('Other', $services) && empty($value)) {
                    $fail('The custom service field is required when "Other" is selected.');
                }
            }],
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

        // If 'Other' is selected, append the custom service to the services array
        $services = $request->services;
        if (in_array('Other', $services) && $request->custom_service) {
            // Replace 'Other' with the custom service value
            $key = array_search('Other', $services);
            $services[$key] = $request->custom_service;
        }

        $updateData = $validatedData;
        $updateData['services'] = $services;
        
        // Remove custom_service from update data since it's not a real field
        unset($updateData['custom_service']);

        $professional->update($updateData);

        return redirect()->route('professional.dashboard')->with('success', 'Profile updated successfully.');
    }
}
