<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConfirmation extends Model
{
    protected $fillable = ['expense_id', 'user_id', 'confirmed_at'];
}
