<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessionalController extends Controller
{
    public function dashboard()
    {
        $professional = Auth::user()->professional;
        return view('professionals.dashboard', compact('professional'));
    }

    public function edit()
    {
        $professional = Auth::user()->professional;
        return view('professionals.edit', compact('professional'));
    }

    public function update(Request $request)
    {
        $professional = Auth::user()->professional;

        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'services' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
        ]);

        $professional->update($request->all());

        return redirect()->route('professional.dashboard')->with('success', 'Profile updated successfully.');
    }
}