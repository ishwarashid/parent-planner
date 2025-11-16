<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Paddle\Billable;

class Professional extends Model
{
    use HasFactory, Billable;

    const PRO_PLAN_IDS = [
            'pri_01k4m53g0ddw2pt8wgjwsdpjwr', // Professional Monthly
            'pri_01k4m54crw11827hzxp3ngms0j', // Professional Yearly
    ];

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
        'paddle_id'
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
            ->whereHas('subscriptions', function ($sub) {
                $sub->where('status', 'active');
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

     /**
     * Get the email address for Paddle.
     * A professional's billing email is their user's email.
     */
    public function paddleEmail(): ?string
    {
        return $this->user->email;
    }

    /**
     * Check if the professional profile has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        // We will name the professional subscription 'professional'
        $subscription = $this->subscription('professional');

        if (!$subscription || !$subscription->valid()) {
            return false;
        }

        $firstItem = $subscription->items->first();
        if (!$firstItem) {
            return false;
        }
        
        return in_array($firstItem->price_id, this::PRO_PLAN_IDS);
    }

       /**
     * Get the customer instance for the professional.
     *
     * This overrides the default Billable behavior. It defines a relationship
     * where a Professional has one Customer THROUGH their associated User.
     * This establishes the User's customer record as the single source of truth.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function customer()
    {
        return $this->hasOneThrough(
            \Laravel\Paddle\Customer::class,   // The final model we want to access
            \App\Models\User::class,           // The intermediate model
            'id',                            // Foreign key on users table...
            'billable_id',                   // Foreign key on customers table...
            'user_id',                       // Local key on professionals table...
            'id'                             // Local key on users table.
        )->where('customers.billable_type', \App\Models\User::class);
    }
}
