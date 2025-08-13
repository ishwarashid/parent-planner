<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visitation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class VisitationController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        // This single line connects all controller methods to their corresponding policy methods.
        // e.g., the `store()` method will automatically check the `create()` policy method.
        // the `edit()` method will automatically check the `update()` policy method.
        $this->authorizeResource(Visitation::class, 'visitation');
    }

    public function index()
    {
        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();

        // Data for the main view
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();

        // **NEW**: Get family members for the "Add New" modal dropdown
        $familyMembers = User::whereIn('id', $familyMemberIds)->get();

        // The query to display visitations in the table
        $visitationsQuery = Visitation::with('child', 'parent', 'creator');

        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            $visitationsQuery->whereIn('parent_id', $familyMemberIds);
        } else {
            $visitationsQuery->where('parent_id', $user->id);
        }

        $visitations = $visitationsQuery->orderBy('date_start', 'asc')->get();
        $currentUserId = $user;
        
        // Pass all necessary data to the view
        return view('visitations.index', compact(
            'visitations',
            'children',
            'familyMembers',
            'currentUserId'
        ));
    }

    public function apiIndex(Request $request)
    {
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();

        // MODIFICATION: Conditional query based on user role for API
        $visitationsQuery = Visitation::whereIn('parent_id', $familyMemberIds)->with('child', 'parent');

        if (!$user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            // Other users see only visitations assigned to them
            $visitationsQuery->where('parent_id', $user->id);
        }

        if ($request->has('child_id') && $request->child_id !== '') {
            $visitationsQuery->where('child_id', $request->child_id);
        }

        $visitations = $visitationsQuery->get();

        return response()->json($visitations->map(function ($visitation) {
            return [
                'id' => $visitation->id,
                'title' => $visitation->child->name . ' Visitation',
                'start' => $visitation->date_start,
                'end' => $visitation->date_end,
                'url' => route('visitations.show', $visitation),
                'parent_id' => $visitation->parent_id,
                // Add status to extendedProps
                'extendedProps' => [
                    'parent_name' => $visitation->parent->name,
                    'child_name' => $visitation->child->name,
                    'notes' => $visitation->notes,
                    'is_recurring' => $visitation->is_recurring,
                    'status' => $visitation->status, // <-- ADD THIS
                ],
                // Optional: Color-code calendar events by status
                'backgroundColor' => $visitation->status === 'Cancelled' ? 'grey' : ($visitation->status === 'Completed' ? 'green' : ''),
            ];
        }));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        logger('create');
        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $familyMembers = User::whereIn('id', $familyMemberIds)->get(); // Get users for the "Assign To" dropdown

        return view('visitations.create', compact('children', 'familyMembers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $familyMemberIds = auth()->user()->getFamilyMemberIds();

        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'parent_id' => ['required', Rule::in($familyMemberIds)], // Validate the assigned parent
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'status' => ['required', 'string', Rule::in(['Scheduled', 'Completed', 'Cancelled'])],
            'is_recurring' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // CORE LOGIC: Set creator and handle boolean for recurring
        $validatedData['created_by'] = auth()->id(); // The creator is ALWAYS the logged-in user
        $validatedData['is_recurring'] = $request->has('is_recurring');

        Visitation::create($validatedData);

        return redirect()->route('visitations.index')->with('success', 'Visitation added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitation $visitation)
    {
        $this->authorize('update', $visitation); // Policy check
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $familyMembers = User::whereIn('id', $familyMemberIds)->get();

        return view('visitations.edit', compact('visitation', 'children', 'familyMembers'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitation $visitation)
    {
        $this->authorize('update', $visitation); // Policy check
        $familyMemberIds = auth()->user()->getFamilyMemberIds();

        $validatedData = $request->validate([
            'child_id' => 'sometimes|required|exists:children,id',
            'parent_id' => ['sometimes', 'required', Rule::in($familyMemberIds)],
            'date_start' => 'sometimes|required|date',
            'date_end' => 'sometimes|required|date|after_or_equal:date_start',
            'status' => ['sometimes', 'required', 'string', Rule::in(['Scheduled', 'Completed', 'Cancelled'])],
            'is_recurring' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($request->has('is_recurring')) {
            $validatedData['is_recurring'] = true;
        } else {
            $validatedData['is_recurring'] = false;
        }

        $visitation->update($validatedData);

        return redirect()->route('visitations.index')->with('success', 'Visitation updated successfully.');
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
     * Remove the specified resource from storage.
     */
    public function destroy(Visitation $visitation)
    {
        $this->authorize('delete', $visitation);
        $visitation->delete();
        return redirect()->route('visitations.index')->with('success', 'Visitation deleted successfully.');
    }
}
