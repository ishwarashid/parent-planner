<?php

namespace App\Services;

use App\Models\Visitation;
use Carbon\Carbon;

class RecurringVisitationService
{
    /**
     * Generate recurring visitations for calendar display within a date range
     * 
     * @param int $userId User ID to get family member IDs for
     * @param string $startDate Start date for the calendar range
     * @param string $endDate End date for the calendar range
     * @return array Array of recurring visitations
     */
    public function getRecurringVisitationsForCalendar($userId, $startDate, $endDate)
    {
        // Get family member IDs to filter visitations
        $user = \App\Models\User::find($userId);
        $familyMemberIds = $user->getFamilyMemberIds();
        if (!in_array($user->id, $familyMemberIds)) {
            $familyMemberIds[] = $user->id;
        }

        // Get all recurring visitations that belong to the user's family
        $visitationsQuery = Visitation::with('child', 'parent', 'creator');

        if ($user->hasRole(['Main Parent', 'Admin Co-Parent'])) {
            $visitationsQuery->whereIn('parent_id', $familyMemberIds);
        } else {
            $visitationsQuery->where('parent_id', $user->id);
        }

        $visitationsQuery->where('is_recurring', true);

        $recurringVisitations = $visitationsQuery->get();

        $calendarVisitations = [];

        foreach ($recurringVisitations as $visitation) {
            $generatedVisitations = $this->generateOccurrences(
                $visitation,
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            );

            foreach ($generatedVisitations as $generatedVisitation) {
                $calendarVisitations[] = $generatedVisitation;
            }
        }

        return $calendarVisitations;
    }

    /**
     * Generate occurrences of a recurring visitation within a date range
     * 
     * @param Visitation $visitation The recurring visitation
     * @param Carbon $startDate Start date for the range
     * @param Carbon $endDate End date for the range
     * @return array Array of generated visitation occurrences
     */
    private function generateOccurrences($visitation, $startDate, $endDate)
    {
        $occurrences = [];
        $currentDate = Carbon::parse($visitation->date_start)->startOfDay();
        
        // If the original visitation is before the calendar start date, start from the calendar range
        if ($currentDate->lt($startDate)) {
            $currentDate = $startDate;
        }

        // Determine recurrence interval based on pattern
        $pattern = $visitation->recurrence_pattern ?? 'weekly'; // Default to weekly
        switch ($pattern) {
            case 'daily':
                $interval = '1 day';
                break;
            case 'weekly':
                $interval = '1 week';
                break;
            case 'monthly':
                $interval = '1 month';
                break;
            case 'yearly':
                $interval = '1 year';
                break;
            default:
                $interval = '1 week'; // Default to weekly
                break;
        }

        // Generate occurrences until end date or recurrence end date (if specified)
        while ($currentDate->lte($endDate)) {
            // Check if recurrence has an end date and we've passed it
            if ($visitation->recurrence_end_date && $currentDate->gt(Carbon::parse($visitation->recurrence_end_date))) {
                break;
            }

            // Create a virtual occurrence for calendar display
            // We'll maintain the same time window as the original visitation
            $occurrenceStart = clone $currentDate;
            $occurrenceEnd = clone $currentDate;
            
            // Copy the time components from the original visitation
            $originalStart = Carbon::parse($visitation->date_start);
            $occurrenceStart->hour($originalStart->hour)
                           ->minute($originalStart->minute)
                           ->second($originalStart->second);
                           
            $originalEnd = Carbon::parse($visitation->date_end);
            $occurrenceEnd->hour($originalEnd->hour)
                         ->minute($originalEnd->minute)
                         ->second($originalEnd->second);

            // For monthly pattern, we need to handle day-of-month specially to avoid issues with months that don't have certain days
            if ($pattern === 'monthly') {
                // Adjust day if current month doesn't have the same day (e.g., Feb doesn't have 30th/31st)
                $expectedDay = $originalStart->day;
                $maxDayInMonth = $occurrenceStart->daysInMonth;
                if ($expectedDay > $maxDayInMonth) {
                    $occurrenceStart->day($maxDayInMonth);
                    $occurrenceEnd->day($maxDayInMonth);
                } else {
                    $occurrenceStart->day($expectedDay);
                    $occurrenceEnd->day($expectedDay);
                }
            }

            $occurrence = [
                'id' => 'visitation-' . $visitation->id . '_' . $occurrenceStart->format('Y-m-d'), // Unique ID with date
                'title' => $visitation->child->name . ' Visitation',
                'start' => $occurrenceStart->format('Y-m-d\TH:i:s'),
                'end' => $occurrenceEnd->format('Y-m-d\TH:i:s'),
                'allDay' => false,
                'url' => route('visitations.show', $visitation->id),
                'extendedProps' => [
                    'type' => 'visitation',
                    'parent_id' => $visitation->parent_id,
                    'child_name' => $visitation->child->name,
                    'parent_name' => $visitation->parent->name,
                    'notes' => $visitation->notes,
                    'is_recurring' => true,
                    'original_visitation_id' => $visitation->id,
                    'status' => $visitation->status,
                    'custom_status_description' => $visitation->custom_status_description,
                ],
                'backgroundColor' => $this->getStatusColor($visitation->status), // Color based on status
            ];

            $occurrences[] = $occurrence;

            // Add the interval to the current date
            if ($pattern === 'monthly') {
                $currentDate->addMonth();
            } elseif ($pattern === 'yearly') {
                $currentDate->addYear();
            } else {
                $currentDate->add($interval);
            }
        }

        return $occurrences;
    }
    
    /**
     * Get the color associated with a visitation status
     * 
     * @param string $status The visitation status
     * @return string The color code
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'Completed':
                return '#28a745'; // Green
            case 'Cancelled':
                return '#6c757d'; // Grey
            case 'Missed':
                return '#dc3545'; // Red
            case 'Rescheduled':
                return '#ffc107'; // Yellow
            case 'Other':
                return '#6f42c1'; // Purple
            default:
                return '#3788d8'; // Default blue for Scheduled
        }
    }
}