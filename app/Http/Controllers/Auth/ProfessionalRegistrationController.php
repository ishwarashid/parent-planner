<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;

class ProfessionalRegistrationController extends Controller
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

        return view('auth.professional-register', compact('continents', 'services'));
    }

    public function store(Request $request)
    {
        $predefinedServices = [
            'Health & Wellness',
            'Education & Learning',
            'Legal & Financial services',
            'Childcare & Parenting',
            'Activities & Enrichment',
            'Household Services',
            'Other'
        ];

        // Check if the email already exists before validation
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->back()
                ->with('errorMessage', 'An account with this email already exists. Please log in first to add professional capabilities.')
                ->withInput(request()->except('password', 'password_confirmation'));
        }

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
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
        ]);

        // If validation passes (email doesn't exist), continue with the original flow
        // If 'Other' is selected, append the custom service to the services array
        $services = $request->services;
        if (in_array('Other', $services) && $request->custom_service) {
            // Replace 'Other' with the custom service value
            $key = array_search('Other', $services);
            $services[$key] = $request->custom_service;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'professional',
        ]);

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

        // event(new Registered($user));

        return redirect()->route('professional.registration.pending');
    }
}
