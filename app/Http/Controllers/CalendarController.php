<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Child;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Visitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $color = '#3788d8'; // Default blue for 'Scheduled'
            if ($visitation->status === 'Completed') {
                $color = '#28a745'; // Green for 'Completed'
            } elseif ($visitation->status === 'Cancelled') {
                $color = '#808080'; // Grey for 'Cancelled'
            }

            $events[] = [
                'id' => 'visitation-' . $visitation->id, // Prevents ID conflicts with other event types
                'title' => 'Visitation: ' . $visitation->child->name . ' (' . $visitation->parent->name . ')',
                'start' => $visitation->date_start,
                'end' => $visitation->date_end,
                'allDay' => false,
                'color' => $color,
                'url' => route('visitations.show', $visitation), // Makes the event clickable
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
        $customEvents = Event::with('child')->whereIn('user_id', $familyMemberIds)->get();
        $defaultColors = ['#F87171', '#FBBF24', '#34D399', '#60A5FA', '#A78BFA'];
        foreach ($customEvents as $event) {
            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'allDay' => false,
                'color' => $event->child->color ?? $defaultColors[array_rand($defaultColors)],
                'description' => $event->description,
                'child_id' => $event->child_id,
            ];
        }

        // Get Expenses
        $expenses = Expense::whereIn('payer_id', $familyMemberIds)->get();
        foreach ($expenses as $expense) {
            $events[] = [
                'title' => 'Expense: ' . $expense->description,
                'start' => $expense->created_at->format('Y-m-d'),
                'allDay' => true,
                'color' => '#ffc107',
                'description' => 'Amount: ' . $expense->amount . ' - Category: ' . $expense->category,
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
        ]);

        // $event = Event::create([
        //     'user_id' => Auth::id(),
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'start' => $request->start,
        //     'end' => $request->end,
        // ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'child_id' => $request->child_id,
        ]);

        $childColor = $event->child ? $event->child->color : '#dc3545';

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start,
            'end' => $event->end,
            'description' => $event->description,
            'child_id' => $event->child_id,
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
        ]);

        $event->update($request->all());

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
}
