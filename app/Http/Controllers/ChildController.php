<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = Child::whereIn('user_id', $familyMemberIds)->get();
        return view('children.index', compact('children'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('children.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|max:255',
            'blood_type' => 'required|string|max:255',
            'allergies' => 'required|string',
            'primary_residence' => 'nullable|string|max:255',
            'school_name' => 'required|string|max:255',
            'school_grade' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'extracurricular_activities' => 'nullable|string',
            'doctor_info' => 'nullable|string',
            'emergency_contact_info' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'other_info' => 'nullable|string',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validatedData['profile_photo_path'] = $path;
        }

        $request->user()->children()->create($validatedData);

        return redirect()->route('children.index')->with('success', 'Child added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Child $child)
    {
        $this->authorize('view', $child);
        return view('children.show', compact('child'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Child $child)
    {
        $this->authorize('update', $child);
        return view('children.edit', compact('child'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Child $child)
    {
        $this->authorize('update', $child);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|max:255',
            'blood_type' => 'required|string|max:255',
            'allergies' => 'required|string',
            'primary_residence' => 'nullable|string|max:255',
            'school_name' => 'required|string|max:255',
            'school_grade' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'extracurricular_activities' => 'nullable|string',
            'doctor_info' => 'nullable|string',
            'emergency_contact_info' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'other_info' => 'nullable|string',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($child->profile_photo_path) {
                Storage::disk('public')->delete($child->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validatedData['profile_photo_path'] = $path;
        }

        $child->update($validatedData);

        return redirect()->route('children.index')->with('success', 'Child updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Child $child)
    {
        $this->authorize('delete', $child);
        $child->delete();
        return redirect()->route('children.index')->with('success', 'Child deleted successfully.');
    }
}
