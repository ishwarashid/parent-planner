<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
        'continent',
        'country', // Add country here
        'city',
        'approval_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'services' => 'array',
    ];

    public function scopeApprovedAndSubscribed(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')
            ->whereHas('user', function ($q) {
                $q->whereHas('subscriptions', function ($sub) {
                    $sub->where('status', 'active');
                });
            });
    }

       public function scopeFilter(Builder $query, array $filters): Builder
    {
        // General search term filter
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('business_name', 'like', '%' . $search . '%')
                    ->orWhere('services', 'like', '%' . $search . '%')
                    ->orWhere('continent', 'like', '%' . $search . '%')
                    ->orWhere('country', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        });

        // Specific service filter
        $query->when($filters['service_filter'] ?? null, function ($q, $service) {
            $q->whereJsonContains('services', $service);
        });

        // Specific continent filter
        $query->when($filters['continent_filter'] ?? null, function ($q, $continent) {
            $q->where('continent', $continent);
        });

        return $query;
    }
}
