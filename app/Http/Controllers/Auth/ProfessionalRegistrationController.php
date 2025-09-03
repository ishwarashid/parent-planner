<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Professional;
use Illuminate\Http\Request;
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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'business_name' => ['required', 'string', 'max:255'],
            'services' => ['required', 'array'],
            'services.*' => ['string', Rule::in($predefinedServices)],
            'phone_number' => ['required', 'string', 'max:255'],
            'continent' => ['string', Rule::in(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'])],
            'country' => ['string', 'max:255'],
            'city' => ['string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'professional',
        ]);

        $user->assignRole('Professional');

        $professional = Professional::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'services' => $request->services,
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

        // event(new Registered($user));

        return redirect()->route('professional.registration.pending');
    }
}
