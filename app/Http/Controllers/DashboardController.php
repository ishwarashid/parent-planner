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
       $upcomingVisitations = Visitation::query()
                            ->whereBetween('date_start', [Carbon::now(), Carbon::now()->addDays(7)])
                            ->where(function ($q) use ($user, $children) {
                                $q->where('parent_id', $user->id);

                                if ($user->role === 'parent') {
                                    $q->orWhereIn('child_id', $children->pluck('id'));
                                }
                            })
                            ->orderBy('date_start')
                            ->get();

        // Pending Expenses
        $pendingExpenses = Expense::query()
                            ->when(
                                in_array($user->role, ['parent', 'co-parent']),
                                fn($q) => $q->whereIn('child_id', $children->pluck('id')),
                                fn($q) => $q->whereRaw('0=1') // return nothing for others
                            )
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
        $nextVisit = Visitation::query()
                    ->where('date_start', '>=', Carbon::now())
                    ->where(function ($q) use ($user, $children) {
                        $q->where('parent_id', $user->id);

                        if ($user->role === 'parent') {
                            $q->orWhereIn('child_id', $children->pluck('id'));
                        }
                    })
                    ->orderBy('date_start')
                    ->first();

        return view('dashboard', compact('upcomingVisitations', 'pendingExpenses', 'childrenWithUpcomingBirthdays', 'nextVisit'));
    }
}
