<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Child;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Visitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CalendarController extends Controller
{
    public function __construct()
    {
        // This authorization is for the custom 'Event' model and is correct.
        // We will handle Visitation authorization manually in the index method.
        $this->authorizeResource(Event::class, 'event');
    }

    public function index()
    {
        $user = Auth::user();
        $familyMemberIds = $user->getFamilyMemberIds();
        $events = [];

        // ==========================================================
        // ** MODIFIED VISITATION LOGIC **
        // ==========================================================

        // 1. Build the base query
        $visitationsQuery = Visitation::with('child', 'parent');

        // 2. Apply role-based authorization to the query
        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            // Main/Admin parents see all visitations within the family unit
            $visitationsQuery->whereIn('parent_id', $familyMemberIds);
        } else {
            // Other users see only visitations that are assigned to them
            $visitationsQuery->where('parent_id', $user->id);
        }

        $visitations = $visitationsQuery->get();

        // 3. Process visitations into calendar events with new details
        foreach ($visitations as $visitation) {
            // Set event color based on the status
            $color = '#3788d8'; // Default for Scheduled
            if ($visitation->status === 'Completed') $color = '#28a745';
            elseif ($visitation->status === 'Cancelled') $color = '#6c757d'; // A better grey
            elseif ($visitation->status === 'Missed') $color = '#dc3545';
            elseif ($visitation->status === 'Rescheduled') $color = '#ffc107';
            elseif ($visitation->status === 'Other') $color = '#6f42c1';

            $events[] = [
                'id' => 'visitation-' . $visitation->id, // Prevents ID conflicts with other event types
                'title' => 'Visitation: ' . $visitation->child->name . ' (' . $visitation->parent->name . ')',
                'start' => $visitation->date_start,
                'end' => $visitation->date_end,
                'allDay' => false,
                'color' => $color,
                'url' => route('visitations.show', $visitation), // Makes the event clickable
                'description' => $visitation->notes ?? 'No notes', // Add description field for tooltip
                'extendedProps' => [ // Pass custom data to the frontend if needed
                    'type' => 'visitation',
                    'status' => $visitation->status,
                    'notes' => $visitation->notes,
                ]
            ];
        }

        // ==========================================================
        // ** Unchanged Logic for Other Event Types **
        // ==========================================================

        // Get Child Birthdays
        $children = Child::whereIn('user_id', $familyMemberIds)->get();
        foreach ($children as $child) {
            if ($child->dob) {
                $birthdate = new \DateTime($child->dob);
                $currentYear = date('Y');
                $birthdayThisYear = new \DateTime($currentYear . '-' . $birthdate->format('m-d'));

                $events[] = [
                    'title' => 'Birthday: ' . $child->name,
                    'start' => $birthdayThisYear->format('Y-m-d'),
                    'allDay' => true,
                    'color' => '#17a2b8', // Changed to a different color
                    'description' => 'Happy Birthday!',
                ];
            }
        }

        // Get Custom Events
        $customEvents = Event::with('child')
                        ->whereIn('user_id', $familyMemberIds)
                        ->when($user->role !== 'parent', fn($q) => 
                            $q->where(fn($q2) => 
                                $q2->whereNull('assigned_to')
                                ->orWhere('assigned_to', $user->id)
                            )
                        )
                        ->get();

        $defaultColors = ['#F87171', '#FBBF24', '#34D399', '#60A5FA', '#A78BFA'];
        foreach ($customEvents as $event) {
            // Set event color based on the status, with child color as fallback
            $color = '#3788d8'; // Default for Scheduled
            if ($event->status === 'Completed') $color = '#28a745';
            elseif ($event->status === 'Cancelled') $color = '#6c757d';
            elseif ($event->status === 'Missed') $color = '#dc3545';
            elseif ($event->status === 'Rescheduled') $color = '#ffc107';
            elseif ($event->status === 'Other') $color = '#6f42c1';
            else $color = $event->child->color ?? $defaultColors[array_rand($defaultColors)];

            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'allDay' => false,
                'color' => $color,
                'description' => $event->description,
                'child_id' => $event->child_id,
                'assigned_to' => $event->assigned_to,
                'status' => $event->status,
                'custom_status_description' => $event->custom_status_description,
            ];
        }

        // Get Expenses
        $expenses = Expense::when(
                    in_array($user->role, ['parent', 'co-parent']),
                    fn($q) => $q->whereIn('payer_id', $familyMemberIds),
                    fn($q) => $q->whereRaw('0 = 1') // return nothing
                    )->get();

        foreach ($expenses as $expense) {
            $events[] = [
                'id' => 'expense-' . $expense->id, // Add ID with prefix to prevent conflicts
                'title' => 'Expense: ' . $expense->description,
                'start' => $expense->created_at->format('Y-m-d'),
                'allDay' => true,
                'color' => '#ffc107',
                'description' => 'Amount: ' . $expense->amount . ' - Category: ' . $expense->category,
                'extendedProps' => [ // Add extendedProps to distinguish expenses
                    'type' => 'expense',
                    'expense_id' => $expense->id,
                ]
            ];
        }

        return view('calendar.index', compact('events', 'children'));
    }

    public function store(Request $request)
    {
        logger('store');
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
            'child_id' => 'nullable|exists:children,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|string|in:Scheduled,Completed,Missed,Cancelled,Rescheduled,Other',
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'child_id' => $request->child_id,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status ?? 'Scheduled',
        ]);

        $childColor = $event->child ? $event->child->color : '#dc3545';

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start,
            'end' => $event->end,
            'description' => $event->description,
            'child_id' => $event->child_id,
            'assigned_to' => $event->assigned_to,
            'status' => $event->status,
            'color' => $childColor,
        ]);

        // return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        logger('update');

        $this->authorize('update', $event);

        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
            'child_id' => 'nullable|exists:children,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|string|in:Scheduled,Completed,Missed,Cancelled,Rescheduled,Other',
            'custom_status_description' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn () => $request->status === 'Other')],
        ]);

        $event->update($request->only([
            'title', 'description', 'start', 'end', 'child_id', 'assigned_to', 'status', 'custom_status_description'
        ]));

        // return response()->json([
        //     'id' => $event->id,
        //     'title' => $event->title,
        //     'start' => $event->start,
        //     'end' => $event->end,
        //     'description' => $event->description,
        //     'child_id' => $event->child_id,
        //     'color' => $childColor,
        // ]);
        logger($event->fresh()->load('child'));
        return new EventResource($event->fresh()->load('child'));
        // return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json(['status' => 'success']);
    }

    // In app/Http/Controllers/EventController.php

    public function updateStatus(Request $request, Event $event)
    {
        // Authorize the action (we will create this policy rule next)
        $this->authorize('updateStatus', $event);

        $validated = $request->validate([
            // Define the allowed statuses
            'status' => 'required|string|in:Scheduled,Completed,Missed,Cancelled,Rescheduled,Other',
            'custom_status_description' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn () => $request->status === 'Other')],
        ]);

        $updateData = ['status' => $validated['status']];
        
        if (isset($validated['custom_status_description'])) {
            $updateData['custom_status_description'] = $validated['custom_status_description'];
        }

        $event->update($updateData);

        // Return a success response, which is useful for AJAX calls
        return response()->json([
            'message' => 'Event status updated successfully.',
            'status' => $event->status,
        ]);
    }
}
