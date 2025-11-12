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
        $this->authorizeResource(Visitation::class, 'visitation');
    }

    public function index()
    {
        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $familyMembers = User::whereIn('id', $familyMemberIds)->get();
        $visitationsQuery = Visitation::with('child', 'parent', 'creator');

        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            $visitationsQuery->whereIn('parent_id', $familyMemberIds);
        } else {
            $visitationsQuery->where('parent_id', $user->id);
        }

        $visitations = $visitationsQuery->orderBy('date_start', 'asc')->get();
        $currentUserId = $user;

        return view('visitations.index', compact('visitations', 'children', 'familyMembers', 'currentUserId'));
    }

    public function apiIndex(Request $request)
    {
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();
        $visitationsQuery = Visitation::whereIn('parent_id', $familyMemberIds)->with('child', 'parent');

        if (!$user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
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
                'extendedProps' => [
                    'parent_name' => $visitation->parent->name,
                    'child_name' => $visitation->child->name,
                    'notes' => $visitation->notes,
                    'is_recurring' => $visitation->is_recurring,
                    'recurrence_pattern' => $visitation->recurrence_pattern,
                    'recurrence_end_date' => $visitation->recurrence_end_date,
                    'status' => $visitation->status,
                    'custom_status_description' => $visitation->custom_status_description,
                ],
                'backgroundColor' => $visitation->status === 'Cancelled' ? 'grey' : ($visitation->status === 'Completed' ? 'green' : ($visitation->status === 'Missed' ? '#dc3545' : ($visitation->status === 'Rescheduled' ? '#ffc107' : ($visitation->status === 'Other' ? '#6f42c1' : '')))), // Added Missed, Rescheduled, and Other colors
            ];
        }));
    }

    public function create()
    {
        $user = auth()->user();
        $familyMemberIds = $user->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $familyMembers = User::whereIn('id', $familyMemberIds)->get();

        return view('visitations.create', compact('children', 'familyMembers'));
    }

    public function store(Request $request)
    {
        $familyMemberIds = auth()->user()->getFamilyMemberIds();

        // Modify request to remove recurrence fields if is_recurring is not checked
        $requestData = $request->all();
        if (!$request->boolean('is_recurring')) {
            unset($requestData['recurrence_pattern']);
            unset($requestData['recurrence_end_date']);
            $request->replace($requestData);
        }

        $validatedData = $request->validate([
            'child_id' => 'required|exists:children,id',
            'parent_id' => ['required', Rule::in($familyMemberIds)],
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            // ADDED "Missed", "Rescheduled", and "Other" to the list of allowed statuses
            'status' => ['required', 'string', Rule::in(['Scheduled', 'Completed', 'Cancelled', 'Missed', 'Rescheduled', 'Other'])],
            'custom_status_description' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn () => $request->status === 'Other')],
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'required_if:is_recurring,1|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $validatedData['created_by'] = auth()->id();
        $validatedData['is_recurring'] = $request->boolean('is_recurring');

        Visitation::create($validatedData);

        return redirect()->route('visitations.index')->with('success', 'Visitation added successfully.');
    }

    public function edit(Visitation $visitation)
    {
        $this->authorize('update', $visitation);
        $familyMemberIds = auth()->user()->getFamilyMemberIds();
        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->get();
        $familyMembers = User::whereIn('id', $familyMemberIds)->get();

        return view('visitations.edit', compact('visitation', 'children', 'familyMembers'));
    }

    public function update(Request $request, Visitation $visitation)
    {
        $this->authorize('update', $visitation);
        $familyMemberIds = auth()->user()->getFamilyMemberIds();

        // Modify request to remove recurrence fields if is_recurring is not checked
        $requestData = $request->all();
        if (!$request->boolean('is_recurring')) {
            unset($requestData['recurrence_pattern']);
            unset($requestData['recurrence_end_date']);
            $request->replace($requestData);
        }

        $validatedData = $request->validate([
            'child_id' => 'sometimes|required|exists:children,id',
            'parent_id' => ['sometimes', 'required', Rule::in($familyMemberIds)],
            'date_start' => 'sometimes|required|date',
            'date_end' => 'sometimes|required|date|after_or_equal:date_start',
            // ADDED "Missed", "Rescheduled", and "Other" to the list of allowed statuses
            'status' => ['sometimes', 'required', 'string', Rule::in(['Scheduled', 'Completed', 'Cancelled', 'Missed', 'Rescheduled', 'Other'])],
            'custom_status_description' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn () => $request->status === 'Other')],
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'required_if:is_recurring,1|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'required_if:is_recurring,1|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $validatedData['is_recurring'] = $request->boolean('is_recurring');

        $visitation->update($validatedData);

        return redirect()->route('visitations.index')->with('success', 'Visitation updated successfully.');
    }

    public function show(Visitation $visitation)
    {
        $this->authorize('view', $visitation);
        return view('visitations.show', compact('visitation'));
    }

    public function destroy(Visitation $visitation)
    {
        $this->authorize('delete', $visitation);
        $visitation->delete();
        return redirect()->route('visitations.index')->with('success', 'Visitation deleted successfully.');
    }
}
