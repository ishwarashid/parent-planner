<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-reports');
    }

    public function view(User $user): bool
    {
        return $user->can('view-reports');
    }

    public function viewCalendar(User $user): bool
    {
        return $user->can('view-calendar-reports');
    }
}