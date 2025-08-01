<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'services',
        'phone_number',
        'website',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',
        'country',
        'city',
        'approval_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}