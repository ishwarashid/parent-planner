<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfessionalProfileController extends Controller
{
    public function create()
    {
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

        return view('professional.create-profile', compact('continents', 'services'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has a professional profile
        if ($user->professional) {
            return redirect()->route('dashboard')->with('error', 'You already have a professional profile.');
        }

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
            'continent' => ['required', 'string', Rule::in(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'])],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url'],
            'facebook' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
        ]);

        // If 'Other' is selected, append the custom service to the services array
        $services = $request->services;
        if (in_array('Other', $services) && $request->custom_service) {
            // Replace 'Other' with the custom service value
            $key = array_search('Other', $services);
            $services[$key] = $request->custom_service;
        }

        $professional = Professional::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'services' => $services,
            'phone_number' => $request->phone_number,
            'continent' => $request->continent,
            'country' => $request->country,
            'city' => $request->city,
            'website' => $request->website,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
        ]);

        // Update user to indicate they have a professional profile
        $user->update([
            'has_professional_profile' => true
        ]);

        // Assign professional role if they don't have it
        if (!$user->hasRole('Professional')) {
            $user->assignRole('Professional');
        }

        return redirect()->route('dashboard')->with('success', 'Professional profile created successfully!');
    }

    public function edit()
    {
        $user = Auth::user();
        
        if (!$user->professional) {
            return redirect()->route('professional.profile.create')->with('error', 'You need to create a professional profile first.');
        }

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

        // Check if the user has a custom service in their services array
        $customService = null;
        $userServices = $user->professional->services ?: [];
        foreach ($userServices as $userService) {
            if (!in_array($userService, $services)) {
                $customService = $userService;
                break;
            }
        }

        return view('professional.edit-profile', compact('user', 'continents', 'services', 'customService'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->professional) {
            return redirect()->route('professional.profile.create')->with('error', 'You need to create a professional profile first.');
        }

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
            'continent' => ['required', 'string', Rule::in(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'])],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url'],
            'facebook' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
        ]);

        // If 'Other' is selected, append the custom service to the services array
        $services = $request->services;
        if (in_array('Other', $services) && $request->custom_service) {
            // Replace 'Other' with the custom service value
            $key = array_search('Other', $services);
            $services[$key] = $request->custom_service;
        }

        $user->professional->update([
            'business_name' => $request->business_name,
            'services' => $services,
            'phone_number' => $request->phone_number,
            'continent' => $request->continent,
            'country' => $request->country,
            'city' => $request->city,
            'website' => $request->website,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
        ]);

        return redirect()->route('dashboard')->with('success', 'Professional profile updated successfully!');
    }
    
    public function addParentCapabilities()
    {
        $user = Auth::user();
        
        // If user doesn't have any parent-related Spatie roles, assign Main Parent role
        if (!$user->hasRole(['Main Parent', 'Invited User', 'Co-Parent'])) {
            $user->assignRole('Main Parent');
        }
        
        // Update the basic role field to 'parent' if it's currently 'professional'
        if ($user->role === 'professional') {
            $user->update([
                'role' => 'parent'  // This allows them to access parent-specific features in controllers
            ]);
        }

        // Check if user also has professional role but no professional subscription
        $hasProfessionalRole = $user->hasRole('Professional');
        $hasProfessionalProfile = $user->has_professional_profile;
        $hasProfessionalSubscription = $user->subscribed('professional');
        $hasParentSubscription = $user->subscribed('default');
        
        // Redirect to appropriate page based on subscription status
        if ($hasProfessionalRole && $hasProfessionalProfile && !$hasProfessionalSubscription) {
            // The user has professional capabilities but no subscription for them
            // Check if they have parent subscription but need professional subscription
            if ($hasParentSubscription) {
                // They have parent subscription, need to add professional subscription
                return redirect()->route('professional.pricing')->with('success', 'Parent capabilities added successfully! Now set up your professional subscription.');
            } else {
                // They have neither subscription - they might want to set up parent subscription first
                return redirect()->route('dashboard')->with('success', 'Parent capabilities added successfully!');
            }
        } else {
            return redirect()->route('dashboard')->with('success', 'Parent capabilities added successfully!');
        }
    }
    
    public function addProfessionalSubscription()
    {
        $user = Auth::user();
        
        // Check if user has parent roles but not professional
        $hasParentRole = $user->hasRole(['Main Parent', 'Invited User', 'Co-Parent']);
        $hasProfessionalRole = $user->hasRole('Professional');
        $hasParentSubscription = $user->subscribed('default');
        $hasProfessionalSubscription = $user->subscribed('professional');
        
        // If the user doesn't have the Professional role, assign it
        if (!$hasProfessionalRole) {
            $user->assignRole('Professional');
        }
        
        // Update the basic role field to allow professional features if they didn't have any role-based access yet
        if ($user->role === 'parent' || $user->role === 'co-parent') {
            // Keep the role as parent since they're primarily a parent user adding professional capabilities
        }
        
        // Check if they need to be redirected to professional subscription
        if ($hasParentRole && !$hasProfessionalSubscription) {
            return redirect()->route('professional.pricing')->with('success', 'Professional role added successfully! Now set up your professional subscription.');
        }
        
        return redirect()->route('dashboard')->with('success', 'Professional role added successfully!');
    }
}
