<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CalendarReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $events;
    protected $visitations;
    protected $type;

    public function __construct($events, $visitations, $type)
    {
        $this->events = $events;
        $this->visitations = $visitations;
        $this->type = $type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $combined = collect();

        // Add events if requested
        if ($this->type === 'event' || $this->type === 'both') {
            foreach ($this->events as $event) {
                $combined->push([
                    'type' => 'Event',
                    'title' => $event->title,
                    'child' => $event->child->name ?? 'N/A',
                    'assigned_to' => $event->assignee ? $event->assignee->name . ($event->assignee->roles->count() > 0 ? ' (' . $event->assignee->roles->first()->name . ')' : '') : 'Unassigned',
                    'start' => $event->start ? formatUserTimezone($event->start) : 'N/A',
                    'end' => $event->end ? formatUserTimezone($event->end) : 'N/A',
                    'created_by' => $event->user->name ?? 'N/A',
                    'created_at' => formatUserTimezone($event->created_at),
                ]);
            }
        }

        // Add visitations if requested
        if ($this->type === 'visitation' || $this->type === 'both') {
            foreach ($this->visitations as $visitation) {
                $combined->push([
                    'type' => 'Visitation',
                    'title' => 'Visitation', // Visitations don't have titles, so we use a generic label
                    'child' => $visitation->child->name,
                    'assigned_to' => $visitation->parent->name . ($visitation->parent->roles->count() > 0 ? ' (' . $visitation->parent->roles->first()->name . ')' : ''),
                    'start' => $visitation->date_start ? formatUserTimezone($visitation->date_start) : 'N/A',
                    'end' => $visitation->date_end ? formatUserTimezone($visitation->date_end) : 'N/A',
                    'created_by' => 'N/A', // Visitations don't track creator separately
                    'created_at' => formatUserTimezone($visitation->created_at),
                ]);
            }
        }

        return $combined;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Title',
            'Child',
            'Assigned To',
            'Start Time',
            'End Time',
            'Created By',
            'Created At',
        ];
    }

    public function map($row): array
    {
        return [
            $row['type'],
            $row['title'],
            $row['child'],
            $row['assigned_to'],
            $row['start'],
            $row['end'],
            $row['created_by'],
            $row['created_at'],
        ];
    }
}