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

    /**
     * Get the URL to the receipt file.
     *
     * @return string
     */
    public function getReceiptUrlAttribute($value)
    {
        if (empty($value)) {
            return '';
        }
        
        // If the file is stored in DigitalOcean Spaces with private visibility, generate a temporary URL
        if (config('filesystems.disks.do.visibility') === 'private' && str_contains($value, 'digitaloceanspaces')) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('do')->temporaryUrl($value, now()->addMinutes(5));
            } catch (Exception $e) {
                // Fall back to the helper function if there's an error
                return do_spaces_url($value);
            }
        }
        
        return do_spaces_url($value);
    }
}
