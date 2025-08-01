<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class ProfessionalRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.professional-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'business_name' => ['required', 'string', 'max:255'],
            'services' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'professional',
        ]);

        $professional = Professional::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'services' => $request->services,
            'phone_number' => $request->phone_number,
            'country' => $request->country,
            'city' => $request->city,
            'website' => $request->website,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
        ]);

        event(new Registered($user));

        // TODO: Redirect to a page informing the professional that their application is under review.

        return redirect('/login')->with('status', 'Registration successful. Your application is under review.');
    }
}