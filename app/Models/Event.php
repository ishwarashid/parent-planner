<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start',
        'end',
        'child_id',
        'assigned_to',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the user this event is assigned to.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
