<?php

namespace App\Http\Controllers;

use App\Models\Visitation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VisitationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $visitations = Visitation::whereIn('parent_id', $familyMemberIds)->orderBy('date_start', 'asc')->get();
        $currentUserId = auth()->id();
        return view('visitations.calendar', compact('visitations', 'currentUserId', 'children'));
    }

    public function apiIndex(Request $request)
    {
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();
        $visitations = Visitation::whereIn('parent_id', $familyMemberIds)->with('child', 'parent');

        if ($request->has('child_id') && $request->child_id !== '') {
            $visitations->where('child_id', $request->child_id);
        }

        $visitations = $visitations->get();

        return response()->json($visitations->map(function ($visitation) {
            return [
                'id' => $visitation->id,
                'title' => $visitation->child->name . ' Visitation',
                'start' => $visitation->date_start,
                'end' => $visitation->date_end,
                'url' => route('visitations.show', $visitation),
                'parent_id' => $visitation->parent_id,
                'extendedProps' => [
                    'parent_name' => $visitation->parent->name,
                    'child_name' => $visitation->child->name,
                    'notes' => $visitation->notes,
                    'is_recurring' => $visitation->is_recurring,
                ]
            ];
        }));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        return view('visitations.create', compact('children'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'is_recurring' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validatedData['parent_id'] = auth()->id();

        $visitation = Visitation::create($validatedData);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'visitation' => $visitation]);
        }

        return redirect()->route('visitations.index')->with('success', 'Visitation added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitation $visitation)
    {
        $this->authorize('view', $visitation);
        return view('visitations.show', compact('visitation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitation $visitation)
    {
        $this->authorize('update', $visitation);
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        return view('visitations.edit', compact('visitation', 'children'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitation $visitation)
    {
        $this->authorize('update', $visitation);

        $validatedData = $request->validate([
            'child_id' => 'sometimes|required|exists:children,id',
            'date_start' => 'sometimes|required|date',
            'date_end' => 'sometimes|required|date|after_or_equal:date_start',
            'is_recurring' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $visitation->update($validatedData);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'visitation' => $visitation]);
        }

        return redirect()->route('visitations.index')->with('success', 'Visitation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitation $visitation)
    {
        $this->authorize('delete', $visitation);
        $visitation->delete();
        return redirect()->route('visitations.index')->with('success', 'Visitation deleted successfully.');
    }
}
