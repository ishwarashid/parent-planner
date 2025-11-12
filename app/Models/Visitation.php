<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'parent_id',      // The user this visitation is ASSIGNED TO
        'created_by',     // The user who CREATED this record
        'date_start',
        'date_end',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_end_date',
        'notes',
        'status',
        'custom_status_description',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the parent (assignee) for the visitation.
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the user who created the visitation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
