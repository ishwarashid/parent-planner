<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitation;
use App\Models\Expense;
use App\Models\Child;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('invitedUsers');
        $familyMemberIds = $user->getFamilyMemberIds();

        $children = Child::whereIn('user_id', $familyMemberIds)->get();

        // Upcoming Visitations (e.g., next 7 days)
        $upcomingVisitations = Visitation::whereIn('child_id', $children->pluck('id'))
                                        ->where('date_start', '>=', Carbon::now())
                                        ->where('date_start', '<=', Carbon::now()->addDays(7))
                                        ->orderBy('date_start')
                                        ->get();

        // Pending Expenses
        $pendingExpenses = Expense::whereIn('child_id', $children->pluck('id'))
                                ->where('status', 'pending')
                                ->get();

        // Children with upcoming birthdays (next 30 days)
        $childrenWithUpcomingBirthdays = $children->filter(function ($child) {
            $dob = Carbon::parse($child->dob);
            $now = Carbon::now();
            $nextBirthday = $dob->copy()->year($now->year);

            if ($nextBirthday->isPast()) {
                $nextBirthday->addYear();
            }

            return $nextBirthday->diffInDays($now) <= 30;
        })->sortBy(function ($child) {
            $dob = Carbon::parse($child->dob);
            $now = Carbon::now();
            $nextBirthday = $dob->copy()->year($now->year);

            if ($nextBirthday->isPast()) {
                $nextBirthday->addYear();
            }
            return $nextBirthday;
        });

        // Next visit countdown
        $nextVisit = Visitation::whereIn('child_id', $children->pluck('id'))
                                ->where('date_start', '>=', Carbon::now())
                                ->orderBy('date_start')
                                ->first();

        return view('dashboard', compact('upcomingVisitations', 'pendingExpenses', 'childrenWithUpcomingBirthdays', 'nextVisit'));
    }
}
