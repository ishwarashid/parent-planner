<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExportWithReceipts implements FromCollection, WithHeadings
{
    protected Collection $expenses;

    public function __construct(Collection $expenses)
    {
        $this->expenses = $expenses;
    }

    /**
     * Transform the initial collection of expenses into a new collection
     * where each row represents a single person's share of an expense.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $reportData = new Collection();

        foreach ($this->expenses as $expense) {
            if ($expense->splits->isEmpty()) {
                // Handle case where an expense might not have splits (optional, for robustness)
                continue;
            }

            foreach ($expense->splits as $split) {
                // For each split, we create a new row of data for the report.
                $reportData->push([
                    'expense_date' => $expense->created_at,
                    'child_name' => $expense->child->name ?? 'N/A',
                    'description' => $expense->description,
                    'category' => $expense->category,
                    'total_amount' => $expense->amount,
                    'status' => $expense->status,
                    'payer_name' => $expense->payer->name ?? 'N/A',
                    'responsible_person' => $split->user->name ?? 'N/A',
                    'percentage' => $split->percentage,
                    'share_amount' => $expense->amount * ($split->percentage / 100),
                    'payment_confirmed' => $expense->confirmations->contains('user_id', $split->user_id) ? 'Yes' : 'No',
                    'receipt_url' => $expense->receipt_url ?? 'N/A',
                ]);
            }
        }

        return $reportData;
    }

    /**
     * Define the column headings for the report.
     */
    public function headings(): array
    {
        return [
            'Expense Date',
            'Child Name',
            'Description',
            'Category',
            'Total Amount',
            'Expense Status',
            'Paid By',
            'Responsible Person',
            'Responsibility (%)',
            'Share Amount',
            'Payment Confirmed?',
            'Receipt URL',
        ];
    }
}