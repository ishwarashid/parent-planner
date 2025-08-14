<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'uploaded_by',
        'name',
        'category',
        'file_url',
        'notes',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
