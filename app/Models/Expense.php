<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'payer_id',
        'description',
        'amount',
        'category',
        'status',
        'receipt_url',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function splits()
    {
        return $this->hasMany(ExpenseSplit::class);
    }
    
    public function confirmations()
    {
        return $this->hasMany(PaymentConfirmation::class);
    }
}
