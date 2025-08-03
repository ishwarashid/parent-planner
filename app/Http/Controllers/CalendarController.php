<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Visitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $familyMemberIds = $user->getFamilyMemberIds();
        $events = [];

        // Get Visitations
        $visitations = Visitation::with('child')->whereIn('parent_id', $familyMemberIds)->get();
        foreach ($visitations as $visitation) {
            $events[] = [
                'title' => 'Visitation: ' . $visitation->child->name,
                'start' => $visitation->date_start,
                'end' => $visitation->date_end,
                'allDay' => false,
                'color' => '#3788d8',
                'description' => $visitation->notes,
            ];
        }

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
                    'color' => '#28a745',
                    'description' => 'Happy Birthday!',
                ];
            }
        }

        // Get Custom Events
        $customEvents = Event::where('user_id', $user->id)->get();
        foreach ($customEvents as $event) {
            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'allDay' => false,
                'color' => '#dc3545',
                'description' => $event->description,
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

        return view('calendar.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
        ]);

        $event->update($request->all());

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json(['status' => 'success']);
    }
}