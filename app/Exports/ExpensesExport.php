<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->expenses;
    }

    public function headings(): array
    {
        return [
            'Child Name',
            'Payer Name',
            'Description',
            'Amount',
            'Category',
            'Status',
            'Date',
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->child->name,
            $expense->payer->name,
            $expense->description,
            number_format($expense->amount, 2),
            $expense->category,
            ucfirst($expense->status),
            \Carbon\Carbon::parse($expense->created_at)->format('M d, Y'),
        ];
    }
}
