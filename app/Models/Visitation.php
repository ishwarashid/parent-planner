<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'parent_id',
        'date_start',
        'date_end',
        'is_recurring',
        'notes',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
