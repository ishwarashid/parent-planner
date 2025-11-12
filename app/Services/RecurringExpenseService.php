<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;

class RecurringExpenseService
{
    /**
     * Generate recurring expenses for calendar display within a date range
     * 
     * @param int $userId User ID to get family member IDs for
     * @param string $startDate Start date for the calendar range
     * @param string $endDate End date for the calendar range
     * @return array Array of recurring expenses
     */
    public function getRecurringExpensesForCalendar($userId, $startDate, $endDate)
    {
        // Get family member IDs to filter expenses
        $user = \App\Models\User::find($userId);
        $familyMemberIds = $user->getFamilyMemberIds();
        if (!in_array($user->id, $familyMemberIds)) {
            $familyMemberIds[] = $user->id;
        }

        $children = \App\Models\Child::whereIn('user_id', $familyMemberIds)->pluck('id');

        // Get all recurring expenses that belong to the user's family
        $recurringExpenses = Expense::with(['child', 'payer', 'splits.user'])
            ->whereIn('child_id', $children)
            ->where('is_recurring', true)
            ->get();

        $calendarExpenses = [];

        foreach ($recurringExpenses as $expense) {
            $generatedExpenses = $this->generateOccurrences(
                $expense,
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            );

            foreach ($generatedExpenses as $generatedExpense) {
                $calendarExpenses[] = $generatedExpense;
            }
        }

        return $calendarExpenses;
    }

    /**
     * Generate occurrences of a recurring expense within a date range
     * 
     * @param Expense $expense The recurring expense
     * @param Carbon $startDate Start date for the range
     * @param Carbon $endDate End date for the range
     * @return array Array of generated expense occurrences
     */
    private function generateOccurrences($expense, $startDate, $endDate)
    {
        $occurrences = [];
        $currentDate = Carbon::parse($expense->created_at)->startOfDay();
        
        // If expense was created after the calendar start date, start from creation date
        if ($currentDate->lt($startDate)) {
            $currentDate = $startDate;
        }

        // Determine recurrence interval based on pattern
        switch ($expense->recurrence_pattern) {
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
                return $occurrences; // If no pattern, return empty
        }

        // Generate occurrences until end date or recurrence end date (if specified)
        while ($currentDate->lte($endDate)) {
            // Check if recurrence has an end date and we've passed it
            if ($expense->recurrence_end_date && $currentDate->gt(Carbon::parse($expense->recurrence_end_date))) {
                break;
            }

            // For monthly pattern, we need to handle day-of-month specially
            $occurrenceDate = clone $currentDate;
            
            // Create a virtual occurrence for calendar display
            $occurrence = [
                'id' => 'expense-' . $expense->id . '_' . $occurrenceDate->format('Y-m-d'), // Same format as regular expenses
                'title' => 'Expense: ' . $expense->description,
                'start' => $occurrenceDate->format('Y-m-d'),
                'allDay' => true, // Same as regular expenses
                'color' => '#ffc107', // Same color as regular expenses
                'description' => 'Amount: ' . $expense->amount . ' - Category: ' . $expense->category,
                'extendedProps' => [ // Same format as regular expenses with additional recurring info
                    'type' => 'expense',
                    'expense_id' => $expense->id,
                    'is_recurring' => true,
                    'recurrence_pattern' => $expense->recurrence_pattern,
                    'recurrence_end_date' => $expense->recurrence_end_date,
                    'child_name' => $expense->child->name,
                    'amount' => $expense->amount,
                    'category' => $expense->category,
                    'payer_name' => $expense->payer->name,
                ]
            ];

            $occurrences[] = $occurrence;

            // Add the interval to the current date
            if ($expense->recurrence_pattern === 'monthly') {
                // For monthly, we add months which handles month-end dates properly
                $currentDate->addMonth();
            } elseif ($expense->recurrence_pattern === 'yearly') {
                // For yearly, we add years
                $currentDate->addYear();
            } else {
                // For daily and weekly, add the appropriate interval
                $currentDate->add($interval);
            }
        }

        return $occurrences;
    }
}