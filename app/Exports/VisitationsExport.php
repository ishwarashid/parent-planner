<?php

namespace App\Exports;

use App\Models\Visitation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $visitations;

    public function __construct($visitations)
    {
        $this->visitations = $visitations;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->visitations;
    }

    public function headings(): array
    {
        return [
            'Child Name',
            'Parent Name',
            'Start Time',
            'End Time',
            'Is Recurring',
            'Notes',
        ];
    }

    public function map($visitation): array
    {
        return [
            $visitation->child->name,
            $visitation->parent->name,
            \Carbon\Carbon::parse($visitation->date_start)->format('M d, Y H:i A'),
            \Carbon\Carbon::parse($visitation->date_end)->format('M d, Y H:i A'),
            $visitation->is_recurring ? 'Yes' : 'No',
            $visitation->notes,
        ];
    }
}
